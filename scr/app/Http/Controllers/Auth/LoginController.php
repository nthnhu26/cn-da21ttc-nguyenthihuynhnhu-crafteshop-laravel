<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Home\CartController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Home\CartMergeService;


class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function authenticate(Request $request)
    {
        $oldSessionId = Session::getId();
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email này không tồn tại trong hệ thống.'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mật khẩu không chính xác.'])->withInput();
        }

        Auth::login($user, $request->has('remember'));
        $request->session()->regenerate();


        // Gộp giỏ hàng với session cũ
        $cartMergeService = new CartMergeService();
        $cartMergeService->mergeCart($oldSessionId);
        return $this->redirectAfterLogin($user);
    }

    public function redirectAfterLogin($user)
    {
        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard.index')->with('success', 'Đăng nhập với quyền Admin!');
        }


        return redirect(route('home'))->with('success', 'Đăng nhập thành công!');
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home'); // Chuyển về trang chủ
    }
}
