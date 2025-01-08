<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Requests\Admin\BannerRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(BannerRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/banners', 'public');
            $validatedData['image_url'] = $imagePath;
        }

        Banner::create($validatedData);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner đã được tạo thành công.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(BannerRequest $request, Banner $banner)
    {
        $validatedData = $request->validated();
    
        DB::transaction(function () use ($validatedData, $request, $banner) {
            if ($request->hasFile('image')) {
                // Xử lý ảnh mới
                if ($banner->image_url) {
                    Storage::disk('public')->delete($banner->image_url);
                }
                $imagePath = $request->file('image')->store('images/banners', 'public');
                $validatedData['image_url'] = $imagePath;
            }
    
            $banner->update($validatedData);
        });
    
        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner đã được cập nhật thành công.');
    }

    public function destroy(Banner $banner)
    {
        try {
            if ($banner->image_url) {
                Storage::disk('public')->delete($banner->image_url);
            }

            $banner->delete();

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner đã được xóa thành công.');
        } catch (\Exception $e) {
            // Xử lý lỗi và trả về thông báo
            return redirect()->route('admin.banners.index')
                ->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }
}
