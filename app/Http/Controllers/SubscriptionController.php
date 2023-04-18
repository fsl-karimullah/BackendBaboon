<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionOption;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        return ['data' => SubscriptionOption::all(['id', 'period'])];
    }

    public function pay(SubscriptionOption $option)
    {
        $name = "Langganan Polije Press $option->period Bulan";
        $price = $option->price;
        $user = auth()->user();
        $orderId = 'SUB'.rand(100000000, 999999999);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $price,
            ],
            'item_details' => [
                [
                    'id' => $option->id,
                    'price' => $price,
                    'quantity' => 1,
                    'name' => $name,
                ],
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];

        $snapUrl = Snap::getSnapUrl($params);
        $subscription = Subscription::create([
            'name' => $name,
            'user_id' => $user->id,
            'order_id' => $orderId,
            'redirect_url' => $snapUrl,
            'price' => $price,
            'period' => $option->period,
        ]);

        return $subscription;
    }

    public function handling()
    {
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $subscription = Subscription::where('order_id', '=', $order_id)->firstOrFail();
        $fraud = $notif->fraud_status;

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $subscription->status = 'CHALLENGE';
                } else {
                    $subscription->status = 'SUCCESS';
                }
            }
        } elseif ($transaction == 'settlement') {
            $subscription->status = 'SUCCESS';
        } elseif ($transaction == 'pending') {
            $subscription->status = 'PENDING';
        } elseif ($transaction == 'deny') {
            $subscription->status = 'DENIED';
        } elseif ($transaction == 'expire') {
            $subscription->status = 'EXPIRED';
        } elseif ($transaction == 'cancel') {
            $subscription->status = 'DENIED';
        }

        $subscription->save();
    }
}
