<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        $addresses = Auth::user()->addresses;

        if ($provinces->isEmpty()) {
            return redirect()->route('profile.show')->withErrors('Không tìm thấy tỉnh/thành phố nào.');
        }

        return view('home.profile.address', compact('provinces', 'addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address_detail' => 'required|string|max:255',
            'id_province' => 'required|exists:provinces,code',
            'id_district' => 'required|exists:districts,code',
            'id_ward' => 'required|exists:wards,code',
        ]);

        $user = Auth::user();
        $existingAddressCount = UserAddress::where('user_id', $user->user_id)->count();
        $isDefault = $existingAddressCount === 0 ? true : $request->boolean('is_default');

        if ($isDefault) {
            UserAddress::where('user_id', $user->user_id)->update(['is_default' => false]);
        }

        $province = Province::where('code', $request->id_province)->first();
        $district = District::where('code', $request->id_district)->first();
        $ward = Ward::where('code', $request->id_ward)->first();

        $fullAddress = "{$request->address_detail}, {$ward->full_name}, {$district->full_name}, {$province->full_name}";

        UserAddress::create([
            'user_id' => $user->user_id,
            'name' => $request->name,
            'address_detail' => $request->address_detail,
            'id_province' => $request->id_province,
            'id_district' => $request->id_district,
            'id_ward' => $request->id_ward,
            'full_address' => $fullAddress,
            'is_default' => $isDefault,
        ]);

        return redirect()->route('profile.addresses')->with('success', 'Địa chỉ giao hàng đã được thêm thành công.');
    }

    public function getAddress($id)
    {
        $address = UserAddress::findOrFail($id);
        return response()->json($address);
    }

    public function update(Request $request, $id)
    {
        $address = UserAddress::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address_detail' => 'required|string|max:255',
            'id_province' => 'required|exists:provinces,code',
            'id_district' => 'required|exists:districts,code',
            'id_ward' => 'required|exists:wards,code',
        ]);

        if ($request->boolean('is_default')) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $province = Province::where('code', $request->id_province)->first();
        $district = District::where('code', $request->id_district)->first();
        $ward = Ward::where('code', $request->id_ward)->first();

        $fullAddress = "{$request->address_detail}, {$ward->full_name}, {$district->full_name}, {$province->full_name}";

        $address->update([
            'name' => $request->name,
            'address_detail' => $request->address_detail,
            'id_province' => $request->id_province,
            'id_district' => $request->id_district,
            'id_ward' => $request->id_ward,
            'full_address' => $fullAddress,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->route('profile.addresses')->with('success', 'Địa chỉ giao hàng đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $address = UserAddress::findOrFail($id);

        if ($address->is_default) {
            $otherAddress = UserAddress::where('user_id', $address->user_id)
                ->where('address_id', '!=', $id)
                ->first();

            if ($otherAddress) {
                $otherAddress->update(['is_default' => true]);
            }
        }

        $address->delete();
        return redirect()->route('profile.addresses')->with('success', 'Địa chỉ giao hàng đã được xóa thành công.');
    }

    public function getDistricts($provinceCode)
    {
        $districts = District::where('province_id', Province::where('code', $provinceCode)->first()->id)->get();
        return response()->json($districts);
    }

    public function getWards($districtCode)
    {
        $wards = Ward::where('district_id', District::where('code', $districtCode)->first()->id)->get();
        return response()->json($wards);
    }
}

