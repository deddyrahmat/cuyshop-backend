<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotifPaymentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {

            $data = $request->all();

            Log::info('incoming-midtrans', ['data' => $data]);
            $signatureKey = $data['signature_key'];

            $orderId = $data['order_id'];
            $statusCode = $data['status_code'];
            $grossAmount = $data['gross_amount'];
            $serverKey = config('services.midtrans.serverKey');

            $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            $transactionStatus = $data['transaction_status'];
            $type = $data['payment_type'];
            $fraudStatus = $data['fraud_status'];

            if ($signatureKey !== $mySignatureKey) {
                return response()->json([
                    'message' => 'invalid signature'
                ], 400);
            }

            $realOrderId = explode('-', $orderId);
            $order = Order::find($realOrderId[0]);
            echo $realOrderId[0];
            if (!$order) {
                return response()->json([
                    'message' => 'order id not found'
                ], 404);
            }

            if ($order->status === 'success') {
                return response()->json([
                    'message' => 'operation not permitted'
                ], 405);
            }

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $order->status = 'challenge';
                } else if ($fraudStatus == 'accept') {
                    $order->status = 'success';
                }
            } else if ($transactionStatus == 'settlement') {
                $order->status = 'success';
            } else if (
                $transactionStatus == 'cancel' ||
                $transactionStatus == 'deny' ||
                $transactionStatus == 'expire'
            ) {
                $order->status = 'failure';
            } else if ($transactionStatus == 'pending') {
                $order->status = 'pending';
            }

            $logData = [
                'status' => $transactionStatus,
                'raw_response' => json_encode($data),
                'order_id' => $realOrderId[0],
                'payment_type' => $type
            ];

            PaymentLogs::create($logData);
            $order->save();


            return response()->json([
                'message' => 'Payment Success',
            ]);
        } catch (\Throwable $th) {
            // Menangani kesalahan
            // Log::error('Error saat menyimpan data: ' . $th->getMessage());


            return response()->json([
                'message' => 'Payment Failed',
            ]);
        }
    }
}
