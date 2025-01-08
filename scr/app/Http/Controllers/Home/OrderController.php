<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Mail\OrderPlacedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\InventoryChange;
use App\Models\Promotion;

class OrderController extends Controller
{

    // Add this at the beginning of your checkout method in the OrderController
    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->user_id)
            ->with(['items.product', 'payment', 'user'])
            ->latest()
            ->get();
        return view('home.orders.index', compact('orders'));
    }

    public function show($orderId)
    {
        $order = Order::with(['items.product'])->findOrFail($orderId);
        if ($order->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        return view('home.orders.show', compact('order'));
    }

    // public function cancel(Order $order)
    // {
    //     if ($order->user_id !== Auth::user()->user_id) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Không thể hủy đơn hàng của người khác'
    //         ]);
    //     }

    //     if (!$order->canBeCancelled()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Không thể hủy đơn hàng ở trạng thái này'
    //         ]);
    //     }

    //     try {
    //         $order->update(['order_status' => 'cancelled']);
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Đã hủy đơn hàng thành công'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Có lỗi xảy ra khi hủy đơn hàng'
    //         ]);
    //     }
    // }



    public function showReviewForm(Request $request, Order $order)
    {
        $productId = $request->query('product_id');
        if (!$productId) {
            return redirect()->route('orders.show', $order)->with('error', 'Sản phẩm không hợp lệ.');
        }

        if ($order->user_id !== Auth::id() || $order->order_status !== 'delivered') {
            return redirect()->route('orders.index')->with('error', 'Không thể đánh giá sản phẩm này.');
        }

        $orderItem = $order->items()->where('product_id', $productId)->first();

        if (!$orderItem || $orderItem->review) {
            return redirect()->route('orders.show', $order)->with('error', 'Sản phẩm này đã được đánh giá hoặc không tồn tại trong đơn hàng.');
        }

        $product = $orderItem->product;
        return view('home.orders.review', compact('order', 'orderItem', 'product'));
    }

    public function submitReview(Request $request, Order $order)
    {
        // Kiểm tra sản phẩm trong request
        $productId = $request->input('product_id');
        if (!$productId) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Sản phẩm không hợp lệ.');
        }

        // Kiểm tra quyền truy cập và trạng thái đơn hàng
        if ($order->user_id !== Auth::id() || $order->order_status !== 'delivered') {
            return redirect()->route('orders.index')
                ->with('error', 'Bạn không thể đánh giá sản phẩm này.');
        }

        // Lấy sản phẩm trong đơn hàng
        $orderItem = $order->items()->where('product_id', $productId)->first();
        if (!$orderItem) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Sản phẩm không tồn tại trong đơn hàng.');
        }

        // Kiểm tra nếu sản phẩm đã được đánh giá
        if ($orderItem->review) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Sản phẩm này đã được đánh giá.');
        }

        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'max:5',
        ], [
            'title.required' => 'Tiêu đề không được để trống.',
            'rating.required' => 'Bạn phải chọn đánh giá từ 1 đến 5 sao.',
            'comment.required' => 'Vui lòng nhập nhận xét của bạn.',
            'images.*.image' => 'Tệp tải lên phải là định dạng ảnh.',
            'images.*.mimes' => 'Chỉ chấp nhận các định dạng: jpeg, png, jpg, gif.',
            'images.*.max' => 'Dung lượng mỗi ảnh không vượt quá 2MB.',
            'images.max' => 'Bạn chỉ được tải lên tối đa 5 ảnh.',
        ]);

        // Lưu trữ hình ảnh
        $mediaUrls = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('review_images', 'public');
                $mediaUrls[] = Storage::url($path);
            }
        }

        // Tạo đánh giá
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'order_item_id' => $orderItem->order_item_id,
            'title' => $validatedData['title'],
            'rating' => $validatedData['rating'],
            'comment' => $validatedData['comment'],
            'media_urls' => json_encode($mediaUrls),
        ]);



        return redirect()->route('orders.show', $order)
            ->with('success', 'Cảm ơn bạn! Đánh giá đã được gửi thành công.');
    }
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::user()->user_id) {
            return back()->with('error', 'Không có quyền hủy đơn hàng này');
        }

        if ($order->order_status !== 'pending') {
            return back()->with('error', 'Chỉ có thể hủy đơn hàng chờ xác nhận');
        }

        try {
            DB::transaction(function () use ($order) {
                // Hoàn lại số lượng sản phẩm
                foreach ($order->items as $item) {
                    $item->product()->increment('stock', $item->quantity);
                }
                $order->update(['order_status' => 'cancelled']);
            });

            return back()->with('success', 'Đã hủy đơn hàng thành công');
        } catch (\Exception $e) {
            Log::error('Order cancellation failed: ' . $e->getMessage());
            return back()->with('error', 'Không thể hủy đơn hàng, vui lòng thử lại');
        }
    }
    public function viewReview($orderId, $productId)
    {
        $review = Review::where('product_id', $productId)
            ->whereHas('orderItem', function ($query) use ($orderId) {
                $query->where('order_id', $orderId);
            })
            ->firstOrFail();

        return view('home.orders.view-review', compact('review'));
    }

    public function editReview(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Không có quyền chỉnh sửa đánh giá này');
        }

        if (Carbon::parse($review->created_at)->diffInHours(now()) > 24) {
            return back()->with('error', 'Chỉ có thể chỉnh sửa đánh giá trong vòng 24 giờ');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'max:5',
        ]);

        $mediaUrls = json_decode($review->media_urls) ?? [];

        if ($request->hasFile('images')) {
            foreach ($mediaUrls as $oldUrl) {
                Storage::delete(str_replace('/storage/', 'public/', $oldUrl));
            }

            $mediaUrls = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('review_images', 'public');
                $mediaUrls[] = Storage::url($path);
            }
        }

        $review->update([
            'title' => $validatedData['title'],
            'rating' => $validatedData['rating'],
            'comment' => $validatedData['comment'],
            'media_urls' => json_encode($mediaUrls),
        ]);

        return back()->with('success', 'Đã cập nhật đánh giá thành công');
    }

    public function deleteReview(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Không có quyền xóa đánh giá này');
        }

        if (Carbon::parse($review->created_at)->diffInHours(now()) > 24) {
            return back()->with('error', 'Chỉ có thể xóa đánh giá trong vòng 24 giờ');
        }

        // Xóa ảnh
        $mediaUrls = json_decode($review->media_urls) ?? [];
        foreach ($mediaUrls as $url) {
            Storage::delete(str_replace('/storage/', 'public/', $url));
        }

        $review->delete();
        return back()->with('success', 'Đã xóa đánh giá thành công');
    }
}
