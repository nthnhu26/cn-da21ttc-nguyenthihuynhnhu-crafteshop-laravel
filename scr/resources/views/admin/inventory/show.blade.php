@extends('admin.layouts.master')

@section('content')
<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">{{ $product->product_name }}</h2>
                        <a href="{{ route('inventory.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Back</a>
                    </div>

                    {{-- Product Details --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="font-semibold mb-2">Product Details</h3>
                            <p><strong>Category:</strong> {{ $product->category->category_name }}</p>
                            <p><strong>Current Stock:</strong> {{ $product->stock }}</p>
                            <p><strong>Status:</strong> 
                                @if($product->stock <= 0)
                                    <span class="text-red-600">Out of Stock</span>
                                @elseif($product->stock <= 10)
                                    <span class="text-yellow-600">Low Stock</span>
                                @else
                                    <span class="text-green-600">In Stock</span>
                                @endif
                            </p>
                        </div>                     
                    </div>

                    {{-- Monthly Changes Summary --}}
                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Monthly Changes Summary</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Period</th>
                                        <th class="px-4 py-2">Total Change</th>
                                        <th class="px-4 py-2">Number of Changes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyChanges as $change)
                                    <tr>
                                        <td class="border px-4 py-2">
                                            {{ date('F Y', mktime(0, 0, 0, $change->month, 1, $change->year)) }}
                                        </td>
                                        <td class="border px-4 py-2">
                                            {{ $change->total_change }}
                                        </td>
                                        <td class="border px-4 py-2">
                                            {{ $change->changes_count }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Detailed Changes History --}}
                    <div>
                        <h3 class="font-semibold mb-2">Changes History</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Date</th>
                                        <th class="px-4 py-2">Quantity Change</th>
                                        <th class="px-4 py-2">Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->inventoryChanges as $change)
                                    <tr>
                                        <td class="border px-4 py-2">
                                            {{ $change->created_at->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="border px-4 py-2">
                                            <span class="{{ $change->quantity_change > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $change->quantity_change }}
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2">{{ $change->reason }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection