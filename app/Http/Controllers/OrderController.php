<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    
    public function listOrders()
    {
        return response()->json(Order::with('user', 'books')->get());
    }
}
