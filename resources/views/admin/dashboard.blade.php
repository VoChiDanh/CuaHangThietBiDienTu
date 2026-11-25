@extends('layouts.admin')

@section('page_title', 'Bảng điều khiển Quản trị & Báo cáo')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 border border-slate-200">
        <div>
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Số liệu kinh doanh tổng hợp</h2>
            <p class="text-xs text-slate-500 mt-0.5">Theo dõi doanh thu, trạng thái đơn hàng và sản phẩm bán chạy</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard.export') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-blue-600 text-white text-xs font-semibold transition-colors flex items-center gap-2 border border-slate-900">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Xuất file Excel (.xlsx)</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white p-4 border border-slate-200">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Tổng doanh thu</span>
            <div class="text-xl font-bold font-mono text-slate-900 mt-1">
                {{ number_format($revenueTotal, 0, ',', '.') }}đ
            </div>
            <div class="mt-2 text-[11px] text-emerald-600 font-medium">
                Tháng này: <span class="font-mono">{{ number_format($revenueMonth, 0, ',', '.') }}đ</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-4 border border-slate-200">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Đơn chờ xử lý</span>
            <div class="text-xl font-bold font-mono text-amber-600 mt-1">
                {{ $newOrdersCount }}
            </div>
            <div class="mt-2 text-[11px] text-slate-500">
                Cần xác nhận giao hàng
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-4 border border-slate-200">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Khách hàng thành viên</span>
            <div class="text-xl font-bold font-mono text-slate-900 mt-1">
                {{ $totalCustomers }}
            </div>
            <div class="mt-2 text-[11px] text-blue-600 font-medium">
                Tài khoản hoạt động
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-4 border border-slate-200">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Sản phẩm & Đánh giá</span>
            <div class="text-xl font-bold font-mono text-slate-900 mt-1">
                {{ $totalProducts }}
            </div>
            <div class="mt-2 text-[11px] text-slate-500">
                <span class="font-mono">{{ $totalReviews }}</span> đánh giá đã xác thực
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Trend Line Chart -->
        <div class="lg:col-span-2 bg-white p-5 border border-slate-200">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Biểu đồ doanh thu 7 ngày gần nhất</h3>
                    <p class="text-[11px] text-slate-500">Đơn vị: VNĐ</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Order Status Doughnut Chart -->
        <div class="bg-white p-5 border border-slate-200">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-1">Cơ cấu trạng thái đơn hàng</h3>
            <p class="text-[11px] text-slate-500 mb-4">Tỷ trọng các khâu xử lý đơn</p>
            <div class="h-64 flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Products Table -->
        <div class="bg-white border border-slate-200">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Top 5 Sản phẩm bán chạy nhất</h3>
                <span class="text-[11px] text-slate-400">Theo số lượng bán</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-4 w-12 text-center">#</th>
                            <th class="py-2.5 px-4">Tên sản phẩm</th>
                            <th class="py-2.5 px-4 text-right">Số lượng</th>
                            <th class="py-2.5 px-4 text-right">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($topProducts as $idx => $prod)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-2.5 px-4 text-center font-mono text-slate-400 font-bold">{{ $idx + 1 }}</td>
                                <td class="py-2.5 px-4 font-medium text-slate-900 max-w-xs truncate">{{ $prod->product_name }}</td>
                                <td class="py-2.5 px-4 text-right font-mono font-bold text-blue-600">{{ $prod->total_quantity }}</td>
                                <td class="py-2.5 px-4 text-right font-mono text-slate-600">{{ number_format($prod->total_sales, 0, ',', '.') }}đ</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Chưa có số liệu bán hàng.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="bg-white border border-slate-200">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Đơn hàng mới nhận</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-[11px] text-blue-600 hover:underline font-semibold">Tất cả đơn →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-4">Mã đơn</th>
                            <th class="py-2.5 px-4">Khách hàng</th>
                            <th class="py-2.5 px-4 text-right">Tổng tiền</th>
                            <th class="py-2.5 px-4 text-center">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-2.5 px-4 font-mono font-bold text-blue-600">{{ $order->order_code }}</td>
                                <td class="py-2.5 px-4">
                                    <div class="font-medium text-slate-900">{{ $order->receiver_name }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $order->created_at?->format('d/m H:i') }}</div>
                                </td>
                                <td class="py-2.5 px-4 text-right font-mono font-semibold text-slate-900">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                <td class="py-2.5 px-4 text-center">
                                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold border {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($order->status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-50 text-slate-700 border-slate-200') }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Chưa có đơn hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.Chart === 'undefined') return;

        // Line Chart: Revenue
        const revCtx = document.getElementById('revenueChart')?.getContext('2d');
        if (revCtx) {
            new window.Chart(revCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartDates) !!},
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: {!! json_encode($chartRevenues) !!},
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.05)',
                        fill: true,
                        tension: 0.2,
                        borderWidth: 2,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return (value / 1000000) + ' Tr';
                                },
                                font: { size: 10, family: 'monospace' }
                            },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            ticks: { font: { size: 10, family: 'monospace' } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // Doughnut Chart: Orders
        const statusCtx = document.getElementById('statusChart')?.getContext('2d');
        if (statusCtx) {
            new window.Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($orderStatuses)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($orderStatuses)) !!},
                        backgroundColor: ['#d97706', '#2563eb', '#059669', '#dc2626'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 10, font: { size: 10 } }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });
</script>
@endpush
