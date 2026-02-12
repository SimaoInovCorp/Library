<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Orders\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->is_admin) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });

        $this->orderService = $orderService;
    }

    /**
     * Display all orders
     */
    public function index(Request $request)
    {
        $status = $request->get('status');

        $query = Order::with(['user', 'items'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20);

        // Statistics (keys must match Blade view)
        $stats = [
            'total_orders'   => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'paid_orders'    => Order::where('status', Order::STATUS_PAID)->count(),
            'total_revenue'  => Order::where('status', Order::STATUS_PAID)->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'stats', 'status'));
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.book.authors', 'items.book.publisher'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cancel an order
     */
    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        try {
            $this->orderService->cancelOrder($order);

            return redirect()->back()->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
