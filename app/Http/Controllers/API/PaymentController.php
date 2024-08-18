<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createPay(Request $request)
    {
        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $request->total,
            ],
            'items_details' => json_decode($request->items, true),
            'customer_details' => [
                'first_name' => $request->fullname,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        try {
            // $snapToken = \Midtrans\Snap::getSnapToken($params);
            // return response()->json(['snap_token' => $snapToken]);
            $snapUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
            return response()->json(['snap_url' => $snapUrl]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
