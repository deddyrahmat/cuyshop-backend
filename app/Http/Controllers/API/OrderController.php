<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        try {

            $order = Order::create([
                'updated_by' => auth()->id(),
                'address_id' => $request->address,
                'total_price' => $request->total_price
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $order->id . '-' . uniqid(),
                    'gross_amount' => $request->total_price,
                ],
                'items_details' => json_decode($request->order_items, true),
                'customer_details' => [
                    'first_name' => $request->fullname,
                    'email' => $request->email,
                ],
            ];

            $midtransSnapUrl = $this->getMidtransSnapUrl($params);

            $order->snap_url = $midtransSnapUrl;

            $order->order_items = $request->order_items;

            $order->save();

            return response()->json([
                'message' => 'Get all data product',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getMidtransSnapUrl($params)
    {

        $snapUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
        return response()->json(['snap_url' => $snapUrl]);
    }
}
