<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    // Hiển thị form đánh giá
    public function create($productId)
    {
        $product = Product::findOrFail($productId);

        return view('home.reviews.create', compact('product'));
    }

    // Xử lý lưu đánh giá
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Cập nhật điểm đánh giá nếu đã tồn tại
        $existingReview = $product->reviews()->where('user_id', Auth::id())->first();
        if ($existingReview) {
            $existingReview->update(['rating' => $validated['rating']]);
        } else {
            $product->reviews()->create([
                'user_id' => Auth::id(),
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Đánh giá của bạn đã được lưu.');
    }


}