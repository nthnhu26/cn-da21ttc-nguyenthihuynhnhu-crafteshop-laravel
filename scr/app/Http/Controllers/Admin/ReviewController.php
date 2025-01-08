<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;


class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('product', 'user')->latest()->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function hide(Request $request, $id)
    {
        $request->validate([
            'hide_reason' => 'required|string|max:255',
        ]);

        $review = Review::findOrFail($id);
        $review->status = 'hidden';
        $review->hide_reason = $request->hide_reason;
        $review->reviewed_by = Auth::id();
        $review->reviewed_at = now();
        $review->save();

        return redirect()->route('admin.reviews.index')->with('success', 'Đánh giá đã được ẩn.');
    }

    public function show(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->status = 'visible';
        $review->hide_reason = null;
        $review->reviewed_by = Auth::id();
        $review->reviewed_at = now();
        $review->save();

        return redirect()->back()->with('success', 'Đánh giá đã được hiển thị.');
    }
}
