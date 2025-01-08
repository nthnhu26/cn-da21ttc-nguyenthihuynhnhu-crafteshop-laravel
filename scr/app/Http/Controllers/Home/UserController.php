<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Support\Facades\Session;
use App\Mail\ResetPasswordEmail;



class UserController extends Controller
{

    public function index()
    {

        $user = Auth::user();
        $provinces = Province::all();
        $addresses = $user->addresses()->with(['province', 'district', 'ward'])->get();

        return view('home.profile.index', compact('provinces', 'addresses'));
    }


    public function updateInfo(Request $request)
    {
        // Khởi tạo Validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => [
                'nullable',
                'string',
                'max:15',
                'regex:/^(0[3|5|7|8|9])+([0-9]{8})$/', // Kiểm tra định dạng
                'unique:users,phone,' . Auth::id() . ',user_id', // Kiểm tra trùng lặp
            ],
        ], [
            'name.required' => 'Họ và tên là bắt buộc.',
            'phone.regex' => 'Số điện thoại không đúng định dạng.',
            'phone.unique' => 'Số điện thoại đã được sử dụng.',
        ]);

        // Kiểm tra nếu lỗi
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cập nhật thông tin người dùng
        $user = Auth::user();
        $user->update($request->only('name', 'phone'));

        return redirect()->back()->with('success', 'Thông tin cá nhân đã được cập nhật thành công!');
    }
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            Session::flash('error', 'Người dùng không tồn tại.');
            return redirect()->back();
        }

        // Xóa các liên kết khác nhưng giữ lại orders và reviews
        $user->addresses()->delete(); // Xóa địa chỉ
        $user->carts()->delete(); // Xóa giỏ hàng

        // Xóa người dùng
        $user->delete();

        Session::flash('success', 'Tài khoản đã được xóa thành công. Đơn hàng và đánh giá vẫn được giữ lại.');
        return redirect()->route('home'); // Điều hướng về trang chính
    }
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        // Xóa ảnh cũ nếu có
        if ($user->avatar_url) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        // Lưu ảnh mới
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar_url' => $avatarPath]);

        return redirect()->back()->with('success', 'Ảnh đại diện đã được cập nhật thành công!');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.'])->withInput();
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }

    public function setPassword(Request $request)
    {
        $user = Auth::user();

        // Nếu người dùng đã có mật khẩu, yêu cầu xác nhận mật khẩu hiện tại
        if ($user->hasPassword()) {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
            }
        } else {
            // Nếu chưa có mật khẩu, chỉ cần validate mật khẩu mới
            $request->validate([
                'new_password' => 'required|min:8|confirmed',
            ]);
        }

        // Cập nhật mật khẩu
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Mật khẩu đã được cập nhật thành công');
    }
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)->first();
            $token = Str::random(64);

            $user->update([
                'reset_password_token' => $token,
                'reset_token_expires' => now()->addHours(1)
            ]);
            //   Mail::to($user->email)->queue(new OrderPlacedMail($order));
            //Mail::to($user->email)->send(new \App\Mail\ResetPasswordEmail($token, $user->email));
            Mail::to($user->email)->queue(new ResetPasswordEmail($token, $user->email));
            DB::commit();
            return back()->with('success', 'Email đặt lại mật khẩu đã được gửi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi gửi email đặt lại mật khẩu');
        }
    }
    public function showResetForm($token)
    {
        $email = request('email'); // Lấy email từ query string
        return view('auth.reset-form', compact('token', 'email'));
    }
    
    
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)->first();

            if ($user->reset_password_token !== $request->token || now()->greaterThan($user->reset_token_expires)) {
                return back()->with('error', 'Token không hợp lệ hoặc đã hết hạn.');
            }

            $user->update([
                'password' => Hash::make($request->password),
                'reset_password_token' => null,
                'reset_token_expires' => null
            ]);

            DB::commit();
            // Đăng nhập người dùng
            Auth::login($user);
            return redirect()->route('home')
                ->with('success', 'Mật khẩu đã được đặt lại thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi đặt lại mật khẩu');
        }
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'address_detail' => 'required|string|max:255',
    //         'id_province' => 'required|exists:provinces,code',
    //         'id_district' => 'required|exists:districts,code',
    //         'id_ward' => 'required|exists:wards,code',
    //         'is_default' => 'boolean',
    //     ]);

    //     // If the new address is set as default, unset the default from other addresses
    //     if ($request->boolean('is_default')) {
    //         auth()->user()->addresses()->update(['is_default' => false]);
    //     }

    //     $address = auth()->user()->addresses()->create([
    //         'name' => $request->name,
    //         'address_detail' => $request->address_detail,
    //         'id_province' => $request->id_province,
    //         'id_district' => $request->id_district,
    //         'id_ward' => $request->id_ward,
    //         'is_default' => $request->boolean('is_default'),
    //     ]);

    //     return redirect()->route('profile.addresses')->with('success', 'Địa chỉ đã được thêm mới thành công.');
    // }
    public function getDistricts($provinceCode)
    {
        $districts = District::where('province_id', Province::where('code', $provinceCode)->first()->id)->get(['code', 'name']);
        return response()->json($districts);
    }

    public function getWards($districtCode)
    {
        $wards = Ward::where('district_id', District::where('code', $districtCode)->first()->id)->get(['code', 'name']);
        return response()->json($wards);
    }

    // public function update(Request $request, $id)
    // {
    //     $address = UserAddress::findOrFail($id);

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'address_detail' => 'required|string|max:255',
    //         'id_province' => 'required|exists:provinces,code',
    //         'id_district' => 'required|exists:districts,code',
    //         'id_ward' => 'required|exists:wards,code',
    //     ]);

    //     // Nếu địa chỉ được cập nhật là mặc định, bỏ mặc định của các địa chỉ khác
    //     if ($request->boolean('is_default')) {
    //         auth()->user()->addresses()->update(['is_default' => false]);
    //     }

    //     $address->update([
    //         'name' => $request->name,
    //         'address_detail' => $request->address_detail,
    //         'id_province' => $request->id_province,
    //         'id_district' => $request->id_district,
    //         'id_ward' => $request->id_ward,
    //         'is_default' => $request->boolean('is_default'),
    //     ]);

    //     return redirect()->route('profile.addresses')->with('success', 'Địa chỉ đã được cập nhật thành công.');
    // }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|regex:/^[0-9]{10,15}$/',
            'address_detail' => 'required|string|max:255',
            'id_province' => 'required|exists:provinces,code',
            'id_district' => 'required|exists:districts,code',
            'id_ward' => 'required|exists:wards,code',
            'is_default' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Nếu địa chỉ mới là mặc định, bỏ mặc định các địa chỉ cũ
            if ($request->boolean('is_default')) {
                auth()->user()->addresses()->update(['is_default' => false]);
            }

            auth()->user()->addresses()->create([
                'user_id' => auth()->user()->user_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'address_detail' => $request->address_detail,
                'id_province' => $request->id_province,
                'id_district' => $request->id_district,
                'id_ward' => $request->id_ward,
                'is_default' => $request->boolean('is_default'),
            ]);

            DB::commit();
            return back()->with('success', 'Địa chỉ đã được thêm mới thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi thêm địa chỉ.');
        }
    }

    public function update(Request $request, $address_id)
    {
        $address = UserAddress::where('address_id', $address_id)
            ->where('user_id', auth()->user()->user_id)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|regex:/^[0-9]{10,15}$/',
            'address_detail' => 'required|string|max:255',
            'id_province' => 'required|exists:provinces,code',
            'id_district' => 'required|exists:districts,code',
            'id_ward' => 'required|exists:wards,code',
        ]);

        DB::beginTransaction();
        try {
            if ($request->boolean('is_default')) {
                auth()->user()->addresses()
                    ->where('address_id', '!=', $address_id)
                    ->update(['is_default' => false]);
            }

            $address->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address_detail' => $request->address_detail,
                'id_province' => $request->id_province,
                'id_district' => $request->id_district,
                'id_ward' => $request->id_ward,
                'is_default' => $request->boolean('is_default'),
            ]);

            DB::commit();
            return redirect()->route('profile.addresses')
                ->with('success', 'Địa chỉ đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật địa chỉ.');
        }
    }

    public function destroy(Request $request, $id)
    {
        $address = UserAddress::findOrFail($id);
        $address->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Địa chỉ đã được xóa thành công.'
            ]);
        }

        return redirect()->route('profile.addresses')->with('success', 'Địa chỉ đã được xóa.');
    }
}
