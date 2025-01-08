<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Http\Controllers\Home\CartMergeService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Chuyển hướng người dùng đến trang đăng nhập Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý callback từ Google.
     */
    public function callback()
    {
        try {
            $oldSessionId = Session::getId();
            $googleUser = Socialite::driver('google')->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt(Str::random(16)),
                    'role_id' => 2,
                    'avatar_url' => $googleUser->getAvatar(),
                    'is_email_verified' => true,

                ]
            );

            if ($user->avatar_url !== $googleUser->getAvatar()) {
                $user->update(['avatar_url' => $googleUser->getAvatar()]);
            }

            Auth::login($user);
            // Tạo session mới
            request()->session()->regenerate();

            // Gộp giỏ hàng với session cũ
            $cartMergeService = new CartMergeService();
            $cartMergeService->mergeCart($oldSessionId);

            return $this->redirectAfterLogin($user);
        } catch (\Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Đăng nhập thất bại. Vui lòng thử lại.');
        }
    }

    public function redirectAfterLogin($user)
    {
        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard.index')->with('success', 'Đăng nhập với quyền Admin!');
        }
        return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
    }
}
