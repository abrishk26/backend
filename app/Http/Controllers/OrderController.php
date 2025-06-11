<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // List all orders (admin only)
    public function index()
    {
        return response()->json(Order::with(['user', 'orderItems.book'])->get());
    }

    // Show a single order
    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.book'])->find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        return response()->json($order);
    }

    // Create a new order
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_price' => 'required|numeric',
            'status' => 'required|string',
            'order_items' => 'required|array',
            'order_items.*.book_id' => 'required|string|exists:books,id',
            'order_items.*.quantity' => 'required|integer|min:1',
            'order_items.*.price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $order = Order::create([
            'user_id' => request()->user()->id,
            'total_price' => $request->total_price,
            'status' => $request->status,
        ]);


        foreach ($request->order_items as $item) {
            $order->orderItems()->create([
                'id' => Str::uuid(),
                'book_id' => $item['book_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return response()->json($order->load('orderItems.book'), 201);
    }

    // Update an existing order (admin only)
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'total_price' => 'sometimes|numeric',
            'status' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $order->update($request->only(['total_price', 'status']));
        return response()->json($order->load('orderItems.book'));
    }

    // Delete an order (admin only)
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->orderItems()->delete();
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
