<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function collection(): Enumerable
    {
        return Order::with(['user', 'items'])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Mã đơn hàng',
            'Ngày đặt',
            'Khách hàng',
            'Số điện thoại',
            'Địa chỉ nhận hàng',
            'Tổng tiền hàng',
            'Giảm giá voucher',
            'Phí vận chuyển',
            'Tổng thanh toán',
            'Phương thức',
            'Trạng thái đơn',
        ];
    }

    /**
     * @param  Order  $order
     */
    public function map(mixed $order): array
    {
        return [
            $order->order_code,
            $order->created_at?->format('d/m/Y H:i'),
            $order->receiver_name,
            $order->receiver_phone,
            $order->shipping_address,
            $order->subtotal,
            $order->discount_amount,
            $order->shipping_fee,
            $order->total_amount,
            strtoupper($order->payment_method),
            $order->status_label,
        ];
    }
}
