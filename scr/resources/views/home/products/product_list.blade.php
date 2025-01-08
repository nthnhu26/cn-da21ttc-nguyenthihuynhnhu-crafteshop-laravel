@foreach($products as $product)
    <div class="col product-item">
        @include('home.products.product_card', ['product' => $product])
    </div>
@endforeach

