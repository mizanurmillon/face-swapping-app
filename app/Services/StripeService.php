<?php

namespace App\Services;

use App\Models\Credit;
use App\Models\PaymentHistory;
use App\Models\UserCredit;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession($user, Credit $credit)
    {
        $price = $credit->amount ?? 0;

        if ($price <= 0) {
            throw new \Exception('Invalid price amount');
        }

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => config('services.stripe.currency', 'EUR'),
                    'unit_amount' => $price * 100,
                    'product_data' => [
                        'name' => $credit->credit . ' Credits',
                    ],
                ],
                'quantity' => 1,
            ]],
            'customer_email' => $user->email,
            'mode' => 'payment',
            'success_url' => route('purchase.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('purchase.cancel'),
            'metadata' => [
                'user_id' => $user->id,
                'credit_id' => $credit->id,
            ],
        ]);
    }

    public function handleSuccess($sessionId)
    {
        $session = Session::retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            throw new \Exception('Payment not completed');
        }

        $userId   = $session->metadata->user_id;
        $creditId = $session->metadata->credit_id;

        $credit = Credit::find($creditId);

        if (!$credit) {
            throw new \Exception('Credit not found');
        }

        $userCredit = UserCredit::firstOrCreate(
            ['user_id' => $userId],
            ['credits' => 0]
        );

        $userCredit->credits += $credit->credit;
        $userCredit->save();

        PaymentHistory::create([
            'user_id'          => $userId,
            'credit_id'        => $creditId,
            'stripe_charge_id' => $session->payment_intent,
            'payment_method'   => 'stripe',
            'currency'         => $session->currency,
            'amount'           => $session->amount_total / 100,
            'status'           => $session->payment_status,
        ]);

        return true;
    }
}