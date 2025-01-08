@extends('home.layouts.app')
@section('title', 'Sản phẩm')
@section('content')

<div class="container-fluid py-4 px-5">
    <div class="row">
        <!-- Sidebar -->
       <!-- Sidebar -->
       <div class="col-lg-3 custom-sidebar mb-4">
            <div class="d-flex align-items-center mb-3">
                <button class="btn btn-primary d-lg-none me-3" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#sidebar">
                    <i class="fas fa-filter"></i> Lọc
                </button>
                <h4 class="m-0">Bộ lọc</h4>
            </div>
            <div class="offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar">
                <div class="offcanvas-header d-lg-none">
                    <h5 class="offcanvas-title">Lọc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body d-flex flex-column">
                    <!-- Categories -->
                    <div class="filter-section mb-4">
                        <h5 class="mb-3">Danh mục sản phẩm</h5>
                        <div class="form-check">
                            <input class="form-check-input category-filter" type="radio" name="category" id="category-all" value="all" {{ $selectedCategory == 'all' ? 'checked' : '' }}>
                            <label class="form-check-label" for="category-all">
                                Tất cả danh mục
                            </label>
                        </div>
                        @foreach($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input category-filter" type="radio" name="category" id="category-{{ $category->category_id }}" value="{{ $category->category_id }}" {{ $selectedCategory == $category->category_id ? 'checked' : '' }}>
                            <label class="form-check-label" for="category-{{ $category->category_id }}">
                                {{ $category->category_name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <!-- Rating Filter -->
                    <div class="filter-section mb-4">
                        <h5 class="mb-3">Đánh giá</h5>
                        @for($i = 5; $i >= 1; $i--)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}">
                            <label class="form-check-label" for="rating{{ $i }}">
                                <span class="star-rating">
                                    @for($j = 1; $j <= 5; $j++)
                                        @if($j <= $i)
                                        <i class="fas fa-star text-warning"></i>
                                        @else
                                        <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                {{ $i }}.0 & up
                            </label>
                        </div>
                        @endfor
                    </div>
                    <!-- Price Filter -->
                    <div class="filter-section mb-4">
                        <h5 class="mb-3">Khoảng giá</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="priceRange" id="price1" value="price1">
                            <label class="form-check-label" for="price1">
                                Dưới 100,000đ
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="priceRange" id="price2" value="price2">
                            <label class="form-check-label" for="price2">
                                100,000đ - 200,000đ
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="priceRange" id="price3" value="price3">
                            <label class="form-check-label" for="price3">
                                200,000đ - 500,000đ
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="priceRange" id="price4" value="price4">
                            <label class="form-check-label" for="price4">
                                500,000đ - 1,000,000đ
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="priceRange" id="price5" value="price5">
                            <label class="form-check-label" for="price5">
                                Trên 1,000,000đ
                            </label>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary me-2" id="applyFilter">Áp dụng</button>
                        <button class="btn btn-secondary" id="resetFilter">Hủy</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Sort Dropdown -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Sản phẩm</h2>
                <div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle sort-dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-sort-down me-2"></i>Sắp Xếp
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item sort-option" href="#" data-sort="price_asc">Giá: Thấp đến Cao</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="price_desc">Giá: Cao xuống Thấp</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="newest">Sản phẩm mới nhất</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Product Content -->
            <div id="product-content">
                @include('home.products.product_content', ['categories' => $categories, 'products' => $products, 'selectedCategory' => $selectedCategory])
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        function applyFilter() {
            $.ajax({
                url: '{{ route("products.filter") }}',
                type: 'POST',
                data: {
                    category: $('input[name="category"]:checked').val(),
                    rating: $('input[name="rating"]:checked').val(),
                    priceRange: $('input[name="priceRange"]:checked').val(),
                    sort: $('.sort-dropdown-toggle').data('sort'),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#product-content').html(response.html);
                        initializeViewMore();
                    }
                },
                error: function(xhr) {
                    console.error('Lỗi:', xhr.responseText);
                }
            });
        }

        function initializeViewMore() {
            if ($('input[name="category"]:checked').val() === 'all') {
                $('.category-container').each(function() {
                    const container = $(this);
                    const items = container.find('.product-item');
                    const viewMoreBtn = container.find('.view-more');
                    const collapseBtn = container.find('.collapse-products');

                    if (items.length > 4) {
                        items.slice(4).addClass('d-none');
                        viewMoreBtn.removeClass('d-none');
                        collapseBtn.addClass('d-none');
                    } else {
                        viewMoreBtn.addClass('d-none');
                        collapseBtn.addClass('d-none');
                    }
                });
            } else {
                $('.product-item').removeClass('d-none');
                $('.view-more, .collapse-products').addClass('d-none');
            }
        }

        $(document).on('click', '.view-more', function() {
            const btn = $(this);
            const categoryContainer = btn.closest('.category-container');
            const items = categoryContainer.find('.product-item');
            const hiddenItems = items.filter('.d-none');

            hiddenItems.slice(0, 4).removeClass('d-none');

            if (hiddenItems.length <= 4) {
                btn.addClass('d-none');
                categoryContainer.find('.collapse-products').removeClass('d-none');
            }
        });

        $(document).on('click', '.collapse-products', function() {
            const btn = $(this);
            const categoryContainer = btn.closest('.category-container');
            const items = categoryContainer.find('.product-item');

            items.slice(4).addClass('d-none');
            btn.addClass('d-none');
            categoryContainer.find('.view-more').removeClass('d-none');
        });

        $('.category-filter, input[name="rating"], input[name="priceRange"]').change(function() {
            applyFilter();
        });

        $('#applyFilter').click(function() {
            applyFilter();
        });

        $('#resetFilter').click(function() {
            $('input[name="category"], input[name="rating"], input[name="priceRange"]').prop('checked', false);
            $('#category-all').prop('checked', true);
            $('.sort-dropdown-toggle').text('Sắp Xếp').data('sort', '');
            applyFilter();
        });

        $('.sort-option').click(function(e) {
            e.preventDefault();
            const sortBy = $(this).data('sort');
            $('.sort-dropdown-toggle').text($(this).text()).data('sort', sortBy);
            applyFilter();
        });

        // Initialize view more functionality on page load
        initializeViewMore();

        // Re-initialize view more functionality after each filter application
        $('.category-filter').change(function() {
            applyFilter();
        });
    });
</script>

@endsection