@extends('admin.layouts.master')
@section('content')
<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-4">Inventory Overview</h2>
                    
                    {{-- Statistics Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <h3 class="font-semibold">Total Products</h3>
                            <p class="text-2xl">{{ $totalProducts }}</p>
                        </div>
                        <div class="bg-red-100 p-4 rounded-lg">
                            <h3 class="font-semibold">Out of Stock</h3>
                            <p class="text-2xl">{{ $outOfStock }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg">
                            <h3 class="font-semibold">Low Stock</h3>
                            <p class="text-2xl">{{ $lowStock }}</p>
                        </div>
                    </div>

                    {{-- Products Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Product</th>
                                    <th class="px-4 py-2">Category</th>
                                    <th class="px-4 py-2">Current Stock</th>
                                    <th class="px-4 py-2">Stock Status</th>
                                    <th class="px-4 py-2">Changes History</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td class="border px-4 py-2">{{ $product->product_name }}</td>
                                    <td class="border px-4 py-2">{{ $product->category->category_name }}</td>
                                    <td class="border px-4 py-2">{{ $product->stock }}</td>
                                    <td class="border px-4 py-2">
                                        @if($product->stock <= 0)
                                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Out of Stock</span>
                                        @elseif($product->stock <= 10)
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Low Stock</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded">In Stock</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ $product->changes_count }} changes</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('inventory.show', $product->product_id) }}" 
                                           class="bg-blue-500 px-3 py-1 rounded hover:bg-blue-600">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection