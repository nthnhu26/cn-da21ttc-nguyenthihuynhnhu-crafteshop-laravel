{{-- resources/views/admin/statistics/index.blade.php --}}
@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Thống kê doanh số</h1>

    {{-- Time Frame Selection --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.statistics') }}" class="form-inline">
                <select name="time_frame" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="day" {{ request('time_frame') == 'day' ? 'selected' : '' }}>Hôm nay</option>
                    <option value="week" {{ request('time_frame') == 'week' ? 'selected' : '' }}>Tuần này</option>
                    <option value="month" {{ request('time_frame') == 'month' ? 'selected' : '' }}>Tháng này</option>
                    <option value="year" {{ request('time_frame') == 'year' ? 'selected' : '' }}>Năm nay</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Biểu đồ doanh thu</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Status Chart --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Trạng thái đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="orderStatusChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Biểu đồ sản phẩm theo danh mục --}}
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thống kê sản phẩm theo danh mục</h6>
            </div>
            <div class="card-body d-flex justify-content-center">
                <div style="width: 600px; height: 600px;">
                    <canvas id="categoryProductsChart"></canvas>
                </div>
                <div class="mt-4" id="categoryLegend"></div>
            </div>
        </div>
    </div>
</div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hàm format tiền VND
        const formatVND = (value) => {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        };

        // Biểu đồ doanh thu
        const revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($revenueStats -> pluck('date')),
                datasets: [{
                    label: 'Doanh thu',
                    data: @json($revenueStats -> pluck('total_revenue')),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Doanh thu: ' + formatVND(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatVND(value);
                            }
                        }
                    }
                }
            }
        });

        // Biểu đồ trạng thái đơn hàng
        const orderStatusLabels = {
            'pending': 'Chờ xử lý',
            'processing': 'Đang xử lý',
            'shipping': 'Đang giao hàng',
            'delivered': 'Đã giao hàng',
            'cancelled': 'Đã hủy'
        };

        const statusData = @json($orderStatusStats);
        const translatedStatusData = statusData.map(item => ({
            status: orderStatusLabels[item.order_status] || item.order_status,
            count: item.count
        }));

        const orderStatusChart = new Chart(document.getElementById('orderStatusChart'), {
            type: 'bar',
            data: {
                labels: translatedStatusData.map(item => item.status),
                datasets: [{
                    label: 'Số lượng đơn hàng',
                    data: translatedStatusData.map(item => item.count),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(75, 192, 192, 0.7)',                      
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        const inventoryData = @json($inventoryStats);

    // Màu sắc biểu đồ
    const backgroundColors = [
        'rgba(255, 99, 132, 0.7)',
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(153, 102, 255, 0.7)',
        'rgba(255, 159, 64, 0.7)',
        'rgba(199, 199, 199, 0.7)',
        'rgba(83, 102, 255, 0.7)',
        'rgba(255, 99, 255, 0.7)',
        'rgba(99, 255, 132, 0.7)'
    ];

    // Tạo biểu đồ
    const categoryProductsChart = new Chart(document.getElementById('categoryProductsChart'), {
        type: 'pie',
        data: {
            labels: inventoryData.map(item => item.category_name),
            datasets: [{
                data: inventoryData.map(item => item.total_stock), // Sử dụng total_stock
                backgroundColor: backgroundColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const stock = context.raw || 0;
                            return `${label}: ${stock} sản phẩm tồn kho`;
                        }
                    }
                },
                legend: {
                    display: false // Tắt legend mặc định
                }
            }
        }
    });

    // Custom legend
    const legendContainer = document.getElementById('categoryLegend');
    let legendHTML = '<div class="list-group">';
    inventoryData.forEach((item, index) => {
        legendHTML += `
            <div class="list-group-item border-0 d-flex align-items-center py-2">
                <span class="mr-2" style="
                    width: 20px;
                    height: 20px;
                    background-color: ${backgroundColors[index]};
                    display: inline-block;
                    border-radius: 3px;
                "></span>
                <span style="font-size: 0.9rem;">
                    ${item.category_name}<br>
                    <small class="text-muted">
                        Tổng sản phẩm: ${item.total_products}<br>
                        Tồn kho: ${item.total_stock}
                    </small>
                </span>
            </div>
        `;
    });
    legendHTML += '</div>';
    legendContainer.innerHTML = legendHTML;
    });
</script>

@endsection