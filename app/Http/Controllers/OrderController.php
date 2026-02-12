<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's orders
     */
    public function index()
    {
        $user = Auth::user();

        $orders = $user->orders()
            ->with(['items.book'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        $user = Auth::user();

        $order = $user->orders()
            ->with(['items.book.authors', 'items.book.publisher'])
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }
}
