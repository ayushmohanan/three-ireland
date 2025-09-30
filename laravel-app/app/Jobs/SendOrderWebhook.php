<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendOrderWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Order $order;

    public string $url;

    public string $secret;

    public function __construct(Order $order, string $url, string $secret)
    {
        $this->order = $order;
        $this->url = $url;
        $this->secret = $secret;
    }

    public function handle(): void
    {
        $payload = [
            'order_id' => $this->order->id,
            'total' => $this->order->lines->sum(fn ($l) => $l->quantity * $l->unit_price),
            'lines' => $this->order->lines->map(fn ($l) => [
                'product_id' => $l->product_id,
                'quantity' => $l->quantity,
                'unit_price' => $l->unit_price,
            ]),
            'timestamp' => now()->toIso8601String(),
        ];

        $signature = hash_hmac('sha256', json_encode($payload), $this->secret);

        Http::retry(3, 2000)
            ->withHeaders(['X-Signature' => $signature])
            ->post($this->url, $payload);
    }
}
