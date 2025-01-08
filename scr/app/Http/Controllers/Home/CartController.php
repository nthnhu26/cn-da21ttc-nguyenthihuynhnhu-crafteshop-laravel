<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        // Existing authentication check logic remains the same
        if (Auth::check()) {
            $sessionId = session()->getId();
            $user = Auth::user();
            $userCart = Cart::firstOrCreate(['user_id' => $user->id]);
            $guestCart = Cart::where('session_id', $sessionId)->first();

            if ($guestCart) {
                foreach ($guestCart->items as $item) {
                    $existingItem = $userCart->items()->where('product_id', $item->product_id)->first();
                    if ($existingItem) {
                        $existingItem->quantity += $item->quantity;
                        $existingItem->save();
                    } else {
                        $userCart->items()->create([
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                        ]);
                    }
                }
                $guestCart->delete();
            }
            $userCart->session_id = $sessionId;
            $userCart->save();
        }

        $cart = Auth::check()
            ? Cart::where('user_id', Auth::id())->with(['items.product', 'items.product.images'])->first()
            : Cart::where('session_id', session()->getId())->with(['items.product', 'items.product.images'])->first();

        $cartItems = $cart ? $cart->items : collect();
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('home.cart.index', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }



    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($product) {
                    if ($value > $product->stock) {
                        $fail("Số lượng vượt quá hàng tồn kho (còn {$product->stock} sản phẩm)");
                    }
                },
            ]
        ]);

        $cart = Auth::check()
            ? Cart::firstOrCreate(['user_id' => Auth::id()])
            : Cart::firstOrCreate(['session_id' => session()->getId()]);

        $existingItem = $cart->items()->where('product_id', $product->product_id)->first();
        $newQuantity = $validated['quantity'] + ($existingItem ? $existingItem->quantity : 0);

        if ($newQuantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => "Tổng số lượng vượt quá hàng tồn kho (còn {$product->stock} sản phẩm)"
            ], 422);
        }

        $cart->items()->updateOrCreate(
            ['product_id' => $product->product_id],
            ['quantity' => DB::raw("quantity + {$validated['quantity']}")]
        );

        $cartCount = $cart->items()->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Thêm sản phẩm vào giỏ hàng thành công!',
            'cart_count' => $cartCount
        ]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'cart_item_id' => 'required|exists:cart_items,cart_item_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::with('product')->find($validatedData['cart_item_id']);
        $product = $cartItem->product;

        if ($validatedData['quantity'] > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => "Số lượng vượt quá hàng tồn kho (còn {$product->stock} sản phẩm).",
            ], 422);
        }

        $cartItem->quantity = $validatedData['quantity'];
        $cartItem->save();

        $cart = $cartItem->cart;
        $total = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'total' => $total,
        ]);
    }

    public function remove(Request $request)
    {
        $validatedData = $request->validate([
            'cart_item_id' => 'required|exists:cart_items,cart_item_id',
        ]);

        $cartItem = CartItem::find($validatedData['cart_item_id']);
        $cartItem->delete();

        $cart = $cartItem->cart;
        $subtotal = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);
        $total = $subtotal + 10000;

        return response()->json([
            'success' => true,
            'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công.',
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
    }

    public function clearCart()
    {
        $cart = Auth::check()
            ? Cart::where('user_id', Auth::id())->first()
            : Cart::where('session_id', session()->getId())->first();

        if ($cart) {
            $cart->items()->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa tất cả sản phẩm khỏi giỏ hàng!');
    }
    public function getItems()
    {
        try {
            $cart = Auth::check()
                ? Cart::where('user_id', Auth::id())->with('items.product')->first()
                : Cart::where('session_id', session()->getId())->with('items.product')->first();

            $items = $cart ? $cart->items : collect([]);
            $total = $items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            return response()->json([
                'success' => true,
                'items' => $items,
                'total' => $total,
                'count' => $items->sum('quantity')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin giỏ hàng'
            ], 500);
        }
    }
}
