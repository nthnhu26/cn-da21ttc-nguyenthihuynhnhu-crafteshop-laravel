<?php

namespace App\Http\Controllers\Home;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartMergeService
{
    /**
     * Merge guest cart with authenticated user's cart
     *
     * @param string|null $oldSessionId Phiên session cũ
     * @return void
     */
    public function mergeCart(?string $oldSessionId = null)
    {
        // Nếu không có người dùng đăng nhập, không thực hiện gộp
        if (!Auth::check()) {
            return;
        }

        // Sử dụng session cũ nếu được truyền vào, ngược lại lấy session hiện tại
        $sessionId = $oldSessionId ?? Session::getId();
        $user = Auth::user();

        // Tìm hoặc tạo giỏ hàng của người dùng
        $userCart = Cart::firstOrCreate([
            'user_id' => $user->user_id
        ]);

        // Tìm giỏ hàng của phiên cũ
        $guestCart = Cart::where('session_id', $sessionId)
            ->where('user_id', null)
            ->first();

        // Nếu không có giỏ hàng khách, thoát
        if (!$guestCart) {
            return;
        }

        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();

        try {
            // Gộp các mục trong giỏ hàng
            foreach ($guestCart->items as $guestItem) {
                // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng người dùng chưa
                $existingCartItem = $userCart->items()
                    ->where('product_id', $guestItem->product_id)
                    ->first();

                if ($existingCartItem) {
                    // Nếu sản phẩm đã tồn tại, cộng dồn số lượng
                    $existingCartItem->quantity += $guestItem->quantity;
                    $existingCartItem->save();
                } else {
                    // Nếu sản phẩm chưa tồn tại, tạo mục mới
                    $userCart->items()->create([
                        'product_id' => $guestItem->product_id,
                        'quantity' => $guestItem->quantity,
                    ]);
                }
            }

            // Xóa giỏ hàng khách sau khi gộp
            $guestCart->delete();

            // Cập nhật session ID cho giỏ hàng người dùng
            $userCart->session_id = $sessionId;
            $userCart->save();

            // Kết thúc transaction
            DB::commit();
        } catch (\Exception $e) {
            // Hoàn tác nếu có lỗi
            DB::rollBack();

            // Ghi log lỗi
            Log::error('Lỗi gộp giỏ hàng: ' . $e->getMessage());
        }
    }
}