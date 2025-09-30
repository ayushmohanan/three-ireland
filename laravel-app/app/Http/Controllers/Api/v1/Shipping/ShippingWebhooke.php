<?php

namespace App\Http\Controllers\Api\v1\Shipping;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ShippingWebhooke extends Controller
{
    public function handle(Request $request)
    {
        $secret = env('ORDER_WEBHOOK_SECRET');

        // Get raw payload
        $payload = $request->getContent();

        // Validate HMAC signature
        $signature = $request->header('X-Signature');
        $computed = hash_hmac('sha256', $payload, $secret);

        if (! hash_equals($computed, $signature)) {
            Log::warning('Invalid webhook signature', ['payload' => $payload]);

            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = $request->json()->all();

        // Required fields
        $orderId = $data['order_id'] ?? null;
        $carrier = $data['carrier'] ?? null;
        $trackingNumber = $data['tracking_number'] ?? null;
        $shippedAt = $data['shipped_at'] ?? null;

        if (! $orderId || ! $carrier || ! $trackingNumber || ! $shippedAt) {
            return response()->json(['error' => 'Missing required fields'], 400);
        }

        // Idempotency key: can use tracking_number + order_id
        $idempotencyKey = $orderId.'_'.$trackingNumber;

        $alreadyProcessed = DB::table('shipping_webhook_logs')
            ->where('idempotency_key', $idempotencyKey)
            ->exists();

        if ($alreadyProcessed) {
            return response()->json(['message' => 'Already processed'], 200);
        }

        DB::transaction(function () use ($orderId, $carrier, $trackingNumber, $shippedAt, $idempotencyKey) {
            $order = Order::findOrFail($orderId);

            // Store the webhook log for idempotency
            DB::table('shipping_webhook_logs')->insert([
                'idempotency_key' => $idempotencyKey,
                'order_id' => $orderId,
                'payload' => json_encode(compact('carrier', 'trackingNumber', 'shippedAt')),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update order
            if ($order->status === 'paid') {
                $order->update([
                    'status' => 'fulfilled',
                    'carrier' => $carrier,
                    'tracking_number' => $trackingNumber,
                    'shipped_at' => $shippedAt,
                ]);
            } else {
                // Only store tracking info
                $order->update([
                    'carrier' => $carrier,
                    'tracking_number' => $trackingNumber,
                    'shipped_at' => $shippedAt,
                ]);
            }
        });

        return response()->json(['message' => 'Webhook processed'], 200);
    }
}
