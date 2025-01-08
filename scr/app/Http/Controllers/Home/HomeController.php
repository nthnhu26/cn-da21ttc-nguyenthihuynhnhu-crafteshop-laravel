<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use DateTime;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy banners
        $banners = Banner::active()->orderBy('start_datetime', 'desc')->get();

        // Nếu không có banner nào đang hoạt động, sử dụng banner mặc định
        if ($banners->isEmpty()) {
            $defaultBanner = new Banner([
                'title' => 'Chào mừng đến với website của chúng tôi',
                'description' => 'Khám phá các sản phẩm và dịch vụ tuyệt vời của chúng tôi',
                'image_url' => 'images/default-banner.jpg',
                'link' => '/',
                'start_datetime' => now(),
                'end_datetime' => now()->addYear(),
            ]);
            $banners->push($defaultBanner);
        }

        // Lấy sản phẩm nổi bật
        $featuredProducts = Product::withCount('orderitems')
            ->with(['images', 'promotion'])
            ->orderBy('orderitems_count', 'desc')
            ->take(10)
            ->get();

        return view('home.index', compact('banners', 'featuredProducts'));
    }

    function about()
    {
        return view('home.about');
    }
}