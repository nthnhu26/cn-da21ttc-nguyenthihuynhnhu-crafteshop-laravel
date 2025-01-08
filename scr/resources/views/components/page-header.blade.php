{{-- resources/views/components/page-header.blade.php --}}
@props(['title', 'breadcrumbs'])

<div class="page-header position-relative mb-5">
    {{-- Nội dung tiêu đề và breadcrumb --}}
    <div class="container position-relative" style="margin-top: -150px;">
        <div class="row">
            <div class="col-12">
                <div class="py-4 text-center position-relative">
                    {{-- Tiêu đề trang --}}
                    <h1 class="display-5 fw-bold mb-3">{{ $title }}</h1>

                    {{-- Breadcrumb --}}
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <i class="fas fa-home"></i> Trang chủ
                                </a>
                            </li>
                            @foreach($breadcrumbs as $breadcrumb)
                            @if(!$loop->last)
                            <li class="breadcrumb-item">
                                <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                                    {{ $breadcrumb['name'] }}
                                </a>
                            </li>
                            @else
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $breadcrumb['name'] }}
                            </li>
                            @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
            <hr class='custom-hr' size="10px" />
        </div>
    </div>
</div>

{{-- Custom CSS --}}
<style>
    .custom-hr {
        border-radius: 5px;
        color: #e67e22;


    }

    .page-header .breadcrumb-item+.breadcrumb-item::before {
        content: "›";
        font-size: 1.2rem;
        line-height: 1;
        color: #6c757d;
    }

    .page-header .breadcrumb-item a {
        color: #e67e22;
        transition: color 0.3s ease;
    }

    .page-header .breadcrumb-item a:hover {
        color: #d35400;
    }

    .page-header .breadcrumb-item.active {
        color: #2c3e50;
    }

    .page-banner .bg-image {
        transition: transform 0.3s ease;
    }

    .page-banner:hover .bg-image {
        transform: scale(1.05);
    }
</style>