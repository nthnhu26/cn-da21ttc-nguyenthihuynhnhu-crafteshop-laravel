<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Order, OrderItem, Cart, UserAddress, Province, Payment, InventoryChange, Promotion};
use Illuminate\Support\Facades\{Auth, DB, Mail};
use App\Mail\OrderPlacedMail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    private function getAvailablePromotions($subtotal)
    {
        // Lấy tất cả khuyến mãi type='code' đang trong thời gian áp dụng
        $promotions = Promotion::where('type', 'fixed')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        // Thêm thông tin có thể áp dụng hay không cho từng khuyến mãi
        foreach ($promotions as $promotion) {
            $promotion->can_apply = ($subtotal >= $promotion->min_amount);
            $promotion->discount_text = $promotion->type === 'percent'
                ? "Giảm {$promotion->value}%"
                : "Giảm " . number_format($promotion->value) . "₫";

            // Nếu áp dụng được, tính số tiền giảm
            if ($promotion->can_apply) {
                $promotion->discount_amount = $promotion->calculateDiscount($subtotal);
            }
        }

        return $promotions;
    }

    public function checkout()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->user_id)->with(['items.product.promotion'])->first();
        $provinces = Province::all();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Vui lòng chọn sản phẩm trước khi thanh toán.');
        }

        $addresses = UserAddress::where('user_id', $user->user_id)->get();
        $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();

        if (!$defaultAddress) {
            return redirect()->route('addresses.create')->with('error', 'Vui lòng thêm địa chỉ giao hàng trước khi thanh toán.');
        }

        $subtotal = $this->calculateSubtotal($cart);
        $shipping = $this->calculateShippingFee($defaultAddress->province);
        $couponCode = session('coupon_code');
        $discount = session('coupon_discount', 0);
        $total = $subtotal + $shipping - $discount;
        $availablePromotions = $this->getAvailablePromotions($subtotal);
        return view('home.checkout.index', compact('cart', 'addresses', 'defaultAddress', 'subtotal', 'shipping', 'total', 'discount', 'provinces', 'couponCode', 'availablePromotions'));
    }

    public function checkCoupon(Request $request)
    {
        $code = $request->input('coupon_code');

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập mã giảm giá'
            ]);
        }

        $promotion = Promotion::where('code', $code)
            ->where('type', 'fixed')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn!'
            ]);
        }

        $cart = Cart::where('user_id', Auth::user()->user_id)->with(['items.product'])->first();
        $subtotal = $this->calculateSubtotal($cart);

        if ($subtotal < $promotion->min_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu!'
            ]);
        }

        $discount = $promotion->calculateDiscount($subtotal);

        if ($discount > 0) {
            session([
                'coupon_code' => $code,
                'coupon_discount' => $discount,
                'promotion_id' => $promotion->promo_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Áp dụng mã giảm giá thành công!',
                'discount' => $discount
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không thể áp dụng mã giảm giá này'
        ]);
    }
    private function calculateShippingFee($province)
    {
        $mekongDeltaProvinces = [
            'An Giang',
            'Bạc Liêu',
            'Bến Tre',
            'Cà Mau',
            'Cần Thơ',
            'Đồng Tháp',
            'Hậu Giang',
            'Kiên Giang',
            'Long An',
            'Sóc Trăng',
            'Tiền Giang',
            'Trà Vinh',
            'Vĩnh Long'
        ];
        return in_array($province, $mekongDeltaProvinces) ? 10000 : 20000;
    }

    // public function placeOrder(Request $request)
    // {
    //     $request->validate([
    //         'selected_address' => 'required|exists:user_addresses,address_id',
    //         'payment_method' => 'required|in:COD,Momo',
    //         'coupon_code' => 'nullable|exists:promotions,code',
    //         'customer_note' => 'nullable|string|max:500'
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         $user = Auth::user();
    //         $cart = Cart::where('user_id', $user->user_id)
    //             ->with(['items.product'])
    //             ->firstOrFail();

    //         if ($cart->items->isEmpty()) {
    //             throw new \Exception('Giỏ hàng của bạn đang trống.');
    //         }

    //         // Kiểm tra tồn kho trước khi tạo đơn hàng
    //         foreach ($cart->items as $item) {
    //             if ($item->quantity > $item->product->stock) {
    //                 throw new \Exception("Sản phẩm {$item->product->product_name} chỉ còn {$item->product->stock} sản phẩm trong kho.");
    //             }
    //         }

    //         // Tính toán giá trị đơn hàng
    //         $address = UserAddress::findOrFail($request->selected_address);
    //         $shipping = $this->calculateShippingFee($address->province);
    //         $subtotal = $this->calculateSubtotal($cart);
    //         $discount = $this->calculateDiscount($request->coupon_code, $subtotal);
    //         $finalAmount = $subtotal + $shipping - $discount;

    //         // Tạo đơn hàng
    //         $order = Order::create([
    //             'user_id' => $user->user_id,
    //             'address' => $this->formatAddress($address),
    //             'shipping_fee' => $shipping,
    //             'total_amount' => $subtotal,
    //             'discount' => $discount,
    //             'final_amount' => $finalAmount,
    //             'customer_note' => $request->customer_note,
    //             'order_status' => 'pending',
    //             'payment_method' => $request->payment_method,
    //         ]);

    //         // Tạo chi tiết đơn hàng và cập nhật tồn kho
    //         foreach ($cart->items as $item) {
    //             $product = $item->product;

    //             OrderItem::create([
    //                 'order_id' => $order->order_id,
    //                 'product_id' => $product->product_id,
    //                 'quantity' => $item->quantity,
    //                 'unit_price' => $product->getDiscountedPrice(),
    //                 'total_price' => $product->getDiscountedPrice() * $item->quantity,
    //             ]);

    //             $product->decrement('stock', $item->quantity);

    //             InventoryChange::recordChange(
    //                 $product->product_id,
    //                 -$item->quantity,
    //                 "Đặt hàng - Mã đơn hàng: {$order->order_id}"
    //             );
    //         }

    //         Payment::create([
    //             'order_id' => $order->order_id,
    //             'amount' => $request->payment_method === 'COD' ? 0 : $finalAmount,
    //             'payment_method' => $request->payment_method,
    //             'status' => 'pending',
    //             'transaction_id' => $request->payment_method === 'Momo' ? $this->generateTransactionId() : null,
    //             'payment_date' => now(),
    //         ]);

    //         $cart->items()->delete();


    //         session()->forget(['coupon_code', 'coupon_discount', 'promotion_id']);

    //         Mail::to($user->email)->queue(new OrderPlacedMail($order));

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'order_id' => $order->order_id
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }

    // public function placeOrder(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $user = Auth::user();
    //         $cart = Cart::where('user_id', $user->user_id)
    //             ->with(['items.product'])
    //             ->firstOrFail();

    //         if ($cart->items->isEmpty()) {
    //             throw new \Exception('Giỏ hàng của bạn đang trống.');
    //         }

    //         // Validate the address
    //         $address = UserAddress::findOrFail($request->selected_address);

    //         // Calculate amounts
    //         $shipping = $this->calculateShippingFee($address->province->name);
    //         $subtotal = $this->calculateSubtotal($cart);
    //         $discount = session('coupon_discount', 0);
    //         $finalAmount = $subtotal + $shipping - $discount;

    //         // Create order
    //         $order = Order::create([
    //             'user_id' => $user->user_id,
    //             'address' => $this->formatAddress($address),
    //             'shipping_fee' => $shipping,
    //             'total_amount' => $subtotal,
    //             'discount' => $discount,
    //             'final_amount' => $finalAmount,
    //             'customer_note' => $request->customer_note,
    //             'order_status' => 'pending',
    //             'payment_method' => $request->payment_method,
    //             'promotion_id' => session('promotion_id'),
    //         ]);

    //         // Create order items
    //         foreach ($cart->items as $item) {
    //             OrderItem::create([
    //                 'order_id' => $order->order_id,
    //                 'product_id' => $item->product->product_id,
    //                 'quantity' => $item->quantity,
    //                 'unit_price' => $item->product->getDiscountedPrice(),
    //                 'total_price' => $item->product->getDiscountedPrice() * $item->quantity,
    //             ]);

    //             // Update product stock
    //             $item->product->decrement('stock', $item->quantity);
    //             InventoryChange::recordChange(
    //                 $item->product->product_id,
    //                 -$item->quantity,
    //                 "Đặt hàng - Mã đơn hàng: {$order->order_id}"
    //             );
    //         }

    //         // Create payment record
    //         Payment::create([
    //             'order_id' => $order->order_id,
    //             'amount' => $request->payment_method === 'COD' ? 0 : $finalAmount,
    //             'payment_method' => $request->payment_method,
    //             'status' => $request->payment_method === 'COD' ? 'pending' : 'pending',
    //             'transaction_id' => $request->payment_method === 'Momo' ? $this->generateTransactionId() : null,
    //         ]);

    //         // Clear cart and session data
    //         $cart->items()->delete();

    //         session()->forget(['coupon_code', 'coupon_discount', 'promotion_id']);

    //         DB::commit();

    //         // Send email in background
    //         Mail::to($user->email)->queue(new OrderPlacedMail($order));

    //         return response()->json([
    //             'success' => true,
    //             'order_id' => $order->order_id,
    //             'message' => 'Đặt hàng thành công!'
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Order placement error: ' . $e->getMessage());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
    //         ], 422);
    //     }
    // }
    public function placeOrder(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $cart = Cart::where('user_id', $user->user_id)
                ->with(['items.product'])
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                throw new \Exception('Giỏ hàng của bạn đang trống.');
            }

            // Validate the address
            $address = UserAddress::findOrFail($request->selected_address);

            // Calculate amounts
            $shipping = $this->calculateShippingFee($address->province->name);
            $subtotal = $this->calculateSubtotal($cart);
            $discount = session('coupon_discount', 0);
            $finalAmount = $subtotal + $shipping - $discount;

            // Create order
            $order = Order::create([
                'user_id' => $user->user_id,
                'address' => $this->formatAddress($address),
                'shipping_fee' => $shipping,
                'total_amount' => $subtotal,
                'discount' => $discount,
                'final_amount' => $finalAmount,
                'customer_note' => $request->customer_note,
                'order_status' => 'pending',
                'payment_method' => $request->payment_method,
                'promotion_id' => session('promotion_id'),
            ]);

            // Create order items and reduce stock
            foreach ($cart->items as $item) {
                $product = $item->product;

                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $product->price,
                    'discount_amount' => $product->getDiscountedPrice(),
                    'total_price' => $product->getDiscountedPrice() * $item->quantity,
                ]);

                // Reduce stock
                if ($product->stock < $item->quantity) {
                    throw new \Exception("Sản phẩm {$product->product_name} không đủ số lượng trong kho.");
                }
                //  $product->decrement('stock', $item->quantity);
                $product->updateStock(-$item->quantity, "Đặt hàng - Mã đơn hàng: {$order->order_id}"); // Gọi phương thức updateStock
                // InventoryChange::recordChange(
                //     $product->product_id,
                //     -$item->quantity,
                //     "Đặt hàng - Mã đơn hàng: {$order->order_id}"
                // );
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->order_id,
                'amount' => $request->payment_method === 'COD' ? 0 : $finalAmount,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method === 'COD' ? 'pending' : 'pending',
                'transaction_id' => $request->payment_method === 'Momo' ? $this->generateTransactionId() : null,
            ]);

            // Clear cart and session data
            $cart->items()->delete();
            session()->forget(['coupon_code', 'coupon_discount', 'promotion_id']);

            DB::commit();

            // Send email
            // Mail::to($user->email)->send(new OrderPlacedMail($order));
            Mail::to($user->email)->queue(new OrderPlacedMail($order));

            return response()->json([
                'success' => true,
                'order_id' => $order->order_id,
                'message' => 'Đặt hàng thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order placement error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 422);
        }
    }

    public function changeAddress(Request $request)
    {
        $request->validate(['address_id' => 'required|exists:user_addresses,address_id']);
        session(['selected_address' => $request->address_id]);
        return redirect()->route('checkout.index');
    }



    private function calculateDiscount($couponCode, $subtotal)
    {
        if (!$couponCode) {
            return 0;
        }

        $promotion = Promotion::where('code', $couponCode)
            ->where('type', 'code')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$promotion) {
            return 0;
        }

        // Kiểm tra điều kiện sử dụng
        if (!$promotion->isValidCode()) {
            return 0;
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($promotion->min_amount && $subtotal < $promotion->min_amount) {
            return 0;
        }

        // Tính giảm giá
        if ($promotion->type === 'percent') {
            $discount = round($subtotal * ($promotion->value / 100), 2);
        } else {
            $discount = $promotion->value;
        }

        // Đảm bảo giảm giá không vượt quá tổng tiền
        return min($discount, $subtotal);
    }

    private function calculateSubtotal($cart)
    {
        return $cart->items->sum(function ($item) {
            return $item->product->getDiscountedPrice() * $item->quantity;
        });
    }


    private function formatAddress($address)
    {
        return implode(', ', [
            $address->name,
            $address->phone,
            $address->address_detail,
            $address->ward->name,
            $address->district->name,
            $address->province->name
        ]);
    }


    private function generateTransactionId()
    {
        return 'TXN' . strtoupper(uniqid());
    }

    public function success($orderId)
    {
        $order = Order::where('order_id', $orderId)
            ->where('user_id', Auth::user()->user_id)
            ->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Đơn hàng không tồn tại.');
        }

        return view('home.orders.success', compact('order'));
    }
}
 // private function calculateShippingFee($province)
    // {
    //     $mekongDeltaProvinces = [
    //         'An Giang', 'Bạc Liêu', 'Bến Tre', 'Cà Mau', 'Cần Thơ',
    //         'Đồng Tháp', 'Hậu Giang', 'Kiên Giang', 'Long An',
    //         'Sóc Trăng', 'Tiền Giang', 'Trà Vinh', 'Vĩnh Long'
    //     ];
    //     return in_array($province, $mekongDeltaProvinces) ? 10000 : 20000;
    // }

    // private function applyCoupon($promotion, $subtotal)
    // {
    //     if (!$promotion || $subtotal < $promotion->min_amount) return 0;

    //     $discount = $promotion->type === 'fixed' ? $promotion->value : ($subtotal * $promotion->value / 100);
    //     return min($discount, $subtotal); // Ensure discount doesn't exceed subtotal
    // }
       // private function calculateSubtotal($cart)
    // {
    //     return $cart->items->sum(function ($item) {
    //         return $item->product->getDiscountedPriceAttribute() * $item->quantity;
    //     });
    // }

    // private function formatAddress($address)
    // {
    //     return implode(', ', [
    //         $address->name,
    //         $address->phone,
    //         $address->address_detail,
    //         $address->ward->name,
    //         $address->district->name,
    //         $address->province->name
    //     ]);
    // }