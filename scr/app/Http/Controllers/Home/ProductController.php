<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{


    private function getFilteredProducts(Request $request)
    {
        $query = Product::query();

        // Lọc theo danh mục
        if ($request->filled('category') && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }

        // Lọc theo đánh giá
        if ($request->filled('rating')) {
            $query->having('average_rating', '>=', $request->rating);
        }

        // Lọc theo khoảng giá
        if ($request->filled('priceRange')) {
            switch ($request->priceRange) {
                case 'price1':
                    $query->where('price', '<', 100000);
                    break;
                case 'price2':
                    $query->whereBetween('price', [100000, 200000]);
                    break;
                case 'price3':
                    $query->whereBetween('price', [200000, 500000]);
                    break;
                case 'price4':
                    $query->whereBetween('price', [500000, 1000000]);
                    break;
                case 'price5':
                    $query->where('price', '>', 1000000);
                    break;
            }
        }

        // Sắp xếp
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->with(['images' => function ($query) {
            $query->where('is_primary', 1);
        }, 'reviews' => function ($query) {
            $query->where('status', 'visible');
        }])
            ->withAvg('reviews as average_rating', 'rating')
            ->get();

        if ($products->isEmpty()) {
            return ['no_results' => true];
        }

        return $products->groupBy('category_id');
    }

    public function filter(Request $request)
    {
        $products = $this->getFilteredProducts($request);
        $categories = Category::where('is_active', 1)->get();
        $selectedCategory = $request->filled('category') ? $request->category : 'all';

        if (isset($products['no_results']) && $products['no_results']) {
            $view = view('home.products.no_results')->render();
        } else {
            $view = view('home.products.product_content', compact('products', 'categories', 'selectedCategory'))->render();
        }

        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function index()
    {
        $categories = Category::where('is_active', 1)->get();
        $products = Product::with(['images' => function ($query) {
            $query->where('is_primary', 1);
        }, 'reviews' => function ($query) {
            $query->where('status', 'visible');
        }])
            ->withAvg('reviews as average_rating', 'rating')
            ->get()
            ->groupBy('category_id');
        $selectedCategory = 'all';

        return view('home.products.index', compact('categories', 'products', 'selectedCategory'));
    }

    public function sort(Request $request)
    {
        $products = $this->getFilteredProducts($request);
        $categories = Category::where('is_active', 1)->get();
        $view = view('home.products.product_content', compact('products', 'categories'))->render();

        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function loadMore(Request $request)
    {
        $categoryId = $request->category_id;
        $offset = $request->offset ?? 4;

        $products = Product::where('category_id', $categoryId)
            ->with(['images' => function ($query) {
                $query->where('is_primary', 1);
            }, 'reviews' => function ($query) {
                $query->where('status', 'visible');
            }])
            ->withAvg('reviews as average_rating', 'rating')
            ->skip($offset)
            ->take(4)
            ->get();

        $view = view('home.products.product_list', compact('products'))->render();

        return response()->json([
            'success' => true,
            'html' => $view,
            'remainingProducts' => Product::where('category_id', $categoryId)->count() - ($offset + 4)
        ]);
    }



    public function checkStock($productId)
    {
        try {
            $product = Product::findOrFail($productId);

            return response()->json([
                'stock' => $product->stock,
                'product_name' => $product->product_name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Không tìm thấy sản phẩm',
                'message' => $e->getMessage()
            ], 404);
        }
    }



    public function show($id)
    {
        // Xác nhận cột 'id' hoặc 'product_id' chính xác
        $product = Product::with(['category', 'reviews'])->where('product_id', $id)->firstOrFail();

        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('product_id', '!=', $product->product_id) // Đảm bảo cột này khớp
            ->take(8)
            ->get();

        return view('home.products.show', compact('product', 'similarProducts'));
    }
    public function categoryProducts($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::where('category_id', $id)->get();
        return view('home.products.category', compact('products', 'category'));
    }

    // Tìm kiếm sản phẩm
    public function search(Request $request)
    {

        $query = $request->input('value');

        if (!$query) {
            return response()->json([]);
        }

        // Tìm kiếm sản phẩm
        $results = Product::where('product_name', 'LIKE', "%{$query}%")
            ->select('product_id', 'product_name', 'price')
            ->limit(10)
            ->get();

        return response()->json($results);
    }
    public function searchResults(Request $request)
    {
        $query = $request->input('q');

        // Tìm kiếm sản phẩm chỉ theo tên
        $products = Product::where('product_name', 'like', "%{$query}%")
            ->get(); // Không phân trang

        return view('home.products.search', compact('products', 'query'));
    }
}
