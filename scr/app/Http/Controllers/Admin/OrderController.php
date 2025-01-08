<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
        if ($request->filled('status')) {
            $orders = $orders->where('order_status', $request->status);
        }
        return view('admin.orders.index', compact('orders'));
    }

    // Cập nhật trạng thái đơn hàng
    // public function updateStatus(Request $request, $orderId)
    // {
    //     $validated = $request->validate([
    //         'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
    //     ]);

    //     $order = Order::findOrFail($orderId);

    //     // Kiểm tra nếu trạng thái hiện tại là 'cancelled' hoặc 'delivered'
    //     if (in_array($order->order_status, ['cancelled', 'delivered'])) {
    //         return back()->with('error', 'Không thể cập nhật trạng thái đơn hàng đã bị hủy hoặc đã giao.');
    //     }

    //     $previousStatus = $order->order_status;

    //     $order->order_status = $validated['status'];
    //     $order->save();


    //     return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    // }
    public function updateStatus(Request $request, $orderId)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipping,delivered',
        ]);
    
        $order = Order::findOrFail($orderId);
    
        // Kiểm tra nếu trạng thái hiện tại là 'cancelled' hoặc 'delivered'
        if (in_array($order->order_status, ['cancelled', 'delivered'])) {
            return back()->with('error', 'Không thể cập nhật trạng thái đơn hàng đã bị hủy hoặc đã giao.');
        }
    
        $previousStatus = $order->order_status;
    
        // Cập nhật trạng thái đơn hàng
        $order->order_status = $validated['status'];
        $order->save();
    
        // Kiểm tra nếu trạng thái mới là 'delivered' và phương thức thanh toán là COD
        if ($order->order_status === 'delivered') {
            $payment = $order->payment; // Lấy thông tin thanh toán liên quan đến đơn hàng
    
            if ($payment && $payment->payment_method === 'COD') {
                // Cập nhật số tiền trong bảng payments
                $payment->amount = $order->final_amount; // Hoặc giá trị bạn muốn cập nhật
                $payment->status = 'completed'; // Cập nhật trạng thái thanh toán nếu cần
                $payment->save();
            }
        }
    
        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

}
