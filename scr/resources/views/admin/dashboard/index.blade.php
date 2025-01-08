@extends('admin.layouts.master')
@section('header')
Dashboard
@endsection
@section('content')
<div class="container-fluid">
    <!-- Time Frame Selection -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body bg-white rounded">
            <form id="timeFrameForm" method="GET" action="{{ route('admin.dashboard.index') }}" class="form-inline justify-content-center">
                <div class="btn-group" role="group">
                    @foreach(['day' => 'Hôm nay', 'week' => 'Tuần này', 'month' => 'Tháng này', 'year' => 'Năm nay'] as $value => $label)
                    <button type="button" onclick="submitTimeFrame('{{ $value }}')"
                        class="btn {{ $timeFrame == $value ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-info elevation-3">
                <div class="inner">
                    <h3>{{ number_format($revenueStats->sum('total_revenue')) }}đ</h3>
                    <p class="mb-0">Doanh Thu</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-danger elevation-3">
                <div class="inner">
                    <h3>{{ $orderStats->sum('total_orders') }}</h3>
                    <p class="mb-0">Đơn Hàng</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success elevation-3">
                <div class="inner">
                    <h3>{{ $inventoryStats->sum('total_products') }}</h3>
                    <p class="mb-0">Sản Phẩm</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning elevation-3">
                <div class="inner">
                    <h3>{{ $userStats->sum('new_users') }}</h3>
                    <p class="mb-0">Người Dùng Mới</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-1"></i>
                        Biểu đồ doanh thu
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Trạng thái đơn hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Products Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-boxes mr-1"></i>
                        Thống kê sản phẩm theo danh mục
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="categoryProductsChart"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="category-legend mt-3" id="categoryLegend"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .small-box {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .small-box:hover {
        transform: translateY(-5px);
    }

    .small-box .inner {
        padding: 20px;
    }

    .small-box h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        white-space: nowrap;
        color: #fff;
    }

    .small-box p {
        font-size: 1rem;
        color: #fff;
    }

    .small-box .icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 50px;
        color: rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .small-box:hover .icon {
        font-size: 55px;
    }

    .small-box .small-box-footer {
        background: rgba(0, 0, 0, 0.1);
        color: #fff;
        padding: 8px 0;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .small-box .small-box-footer:hover {
        background: rgba(0, 0, 0, 0.2);
    }

    .chart-container {
        min-height: 300px;
    }

    .category-legend {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .category-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        padding: 8px;
        border-radius: 4px;
        background: #f8f9fa;
        transition: all 0.2s ease;
    }

    .category-item:hover {
        background: #e9ecef;
    }

    .category-color {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        margin-right: 10px;
    }

    .btn-group .btn {
        padding: 8px 16px;
        font-weight: 600;
    }

    .elevation-3 {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formatVND = (value) => {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        };

        // Revenue Chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($revenueStats -> pluck('date')),
                datasets: [{
                    label: 'Doanh thu',
                    data: @json($revenueStats -> pluck('total_revenue')),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: true,
                    backgroundColor: 'rgba(75, 192, 192, 0.1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return formatVND(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return formatVND(value);
                            }
                        }
                    }
                }
            }
        });

        const orderStatusLabels = {
            'pending': 'Chờ xử lý',
            'processing': 'Đang xử lý',
            'shipping': 'Đang vận chuyển',
            'delivered': 'Đã giao hàng',
            'cancelled': 'Đã hủy'
        };

        const orderStatusColors = {
            'pending': '#ffc107',
            'processing': '#17a2b8',
            'shipping': '#6f42c1',
            'delivered': '#28a745',
            'cancelled': '#dc3545'
        };
        const orderStatusData = @json($orderStatusStats);

        new Chart(document.getElementById('orderStatusChart'), {
            type: 'bar',
            data: {
                labels: orderStatusData.map(item => orderStatusLabels[item.order_status] || item.order_status),
                datasets: [{
                    label: 'Trạng thái đơn hàng',
                    data: orderStatusData.map(item => item.count),
                    backgroundColor: orderStatusData.map(item => orderStatusColors[item.order_status] || '#6c757d'),
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: true
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Updated Category Products Chart
        const categoryData = @json($inventoryStats);
        const chartColors = [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
            '#858796', '#5a5c69', '#2e59d9', '#17a673', '#2c9faf'
        ];

        new Chart(document.getElementById('categoryProductsChart'), {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.category_name),
                datasets: [{
                    data: categoryData.map(item => item.total_products),
                    backgroundColor: chartColors,
                    hoverBackgroundColor: chartColors.map(color => color + 'dd'),
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Generate interactive category legend
        const legendContainer = document.getElementById('categoryLegend');
        categoryData.forEach((category, index) => {
            const item = document.createElement('div');
            item.className = 'category-item';
            item.innerHTML = `
            <div class="category-color" style="background-color: ${chartColors[index]}"></div>
            <div class="flex-grow-1">
                <div class="font-weight-bold">${category.category_name}</div>
                <div class="text-muted small">
                    ${category.total_products} sản phẩm / ${category.total_stock} trong kho
                </div>
            </div>
        `;
            legendContainer.appendChild(item);
        });
    });

    // Improved time frame filter submission
    function submitTimeFrame(value) {
        const form = document.getElementById('timeFrameForm');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'time_frame';
        input.value = value;
        form.appendChild(input);
        form.submit();
    }
</script>
@endsection