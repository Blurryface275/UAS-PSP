<?php

namespace App\Http\Controllers;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Sale::where('user_id', Auth::id())->count();

        $activeOrders = Sale::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'processing', 'shipped'])
            ->count();

        $completedOrders = Sale::where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->count();

        return view('customer.dashboard', compact(
            'totalOrders',
            'activeOrders',
            'completedOrders'
        ));
    }
}