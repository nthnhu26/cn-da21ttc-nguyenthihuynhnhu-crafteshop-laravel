@if($selectedCategory != 'all')
    @php
        $categoryProducts = $products[$selectedCategory] ?? collect();
        $category = $categories->firstWhere('category_id', $selectedCategory);
    @endphp
    @if($category)
        <section id="category-{{ $selectedCategory }}" class="mb-5 category-container">
            <h3 class="mb-4">{{ $category->category_name }}</h3>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($categoryProducts as $product)
                    <div class="col product-item">
                        @include('home.products.product_card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </section>
    @else
        <div class="alert alert-info" role="alert">
            Không tìm thấy danh mục sản phẩm.
        </div>
    @endif
@else
    @foreach($categories as $category)
        @if(isset($products[$category->category_id]) && $products[$category->category_id]->isNotEmpty())
            <section id="category-{{ $category->category_id }}" class="mb-5 category-container">
                <h3 class="mb-4">{{ $category->category_name }}</h3>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach($products[$category->category_id] as $index => $product)
                        <div class="col product-item {{ $index >= 4 ? 'd-none' : '' }}">
                            @include('home.products.product_card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
                @if($products[$category->category_id]->count() > 4)
                    <div class="text-center mt-4">
                        <button class="btn btn-outline-primary view-more" data-category="{{ $category->category_id }}">Xem thêm</button>
                        <button class="btn btn-outline-secondary d-none collapse-products" data-category="{{ $category->category_id }}">Thu gọn</button>
                    </div>
                @endif
            </section>
        @endif
    @endforeach
@endif



