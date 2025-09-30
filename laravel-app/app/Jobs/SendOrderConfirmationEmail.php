<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {

        $superAdmin = User::where('role', 'super_admin')->first();

        if (! $superAdmin) {
            return;
        }

        Mail::raw("Order #{$this->order->id} placed successfully.", function ($message) use ($superAdmin) {
            $message->to($superAdmin->email)
                ->subject('Order Confirmation');
        });
    }
}
