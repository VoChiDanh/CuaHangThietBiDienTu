<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RevenueExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        // 1. KPI Doanh thu (tính từ đơn hàng đã hoàn thành hoặc đã giao)
        $revenueTotal = Order::whereIn('status', ['completed', 'delivered'])->sum('total_amount');
        $revenueMonth = Order::whereIn('status', ['completed', 'delivered'])->where('created_at', '>=', $startOfMonth)->sum('total_amount');
        $revenueToday = Order::whereIn('status', ['completed', 'delivered'])->where('created_at', '>=', $today)->sum('total_amount');

        // 2. Thống kê số lượng
        $newOrdersCount = Order::where('status', 'pending')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $totalReviews = Review::count();

        // 3. Top 5 sản phẩm bán chạy nhất
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total_price) as total_sales'))
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // 4. Biểu đồ Doanh thu 7 ngày gần nhất (Chart.js)
        $chartDates = [];
        $chartRevenues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->format('d/m');
            $dailyRev = Order::whereIn('status', ['completed', 'delivered'])
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            $chartRevenues[] = (float) $dailyRev;
        }

        // 5. Cơ cấu trạng thái đơn hàng (Doughnut Chart)
        $orderStatuses = [
            'Chờ xác nhận' => Order::where('status', 'pending')->count(),
            'Đang giao' => Order::where('status', 'shipping')->count(),
            'Hoàn thành' => Order::whereIn('status', ['completed', 'delivered'])->count(),
            'Đã hủy' => Order::where('status', 'cancelled')->count(),
        ];

        // 6. Đơn hàng mới nhất
        $recentOrders = Order::with('user')->latest()->limit(8)->get();

        return view('admin.dashboard', compact(
            'revenueTotal',
            'revenueMonth',
            'revenueToday',
            'newOrdersCount',
            'totalCustomers',
            'totalProducts',
            'totalReviews',
            'topProducts',
            'chartDates',
            'chartRevenues',
            'orderStatuses',
            'recentOrders'
        ));
    }

    public function exportExcel(): BinaryFileResponse
    {
        $fileName = 'Bao_Cao_Doanh_Thu_SmartTech_'.date('Y_m_d_His').'.xlsx';

        return Excel::download(new RevenueExport, $fileName);
    }
}
