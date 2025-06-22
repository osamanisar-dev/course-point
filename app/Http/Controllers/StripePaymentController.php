<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripePaymentController extends Controller
{
    public function showCheckout()
    {
        return view('stripe');
    }

    public function createPaymentIntent()
    {
        Stripe::setApiKey(config('services.stripe.stripe_secret'));

        try {
            $intent = PaymentIntent::create([
                'amount' => 1 * 100,
                'currency' => 'usd',
                'description' => 'Stripe PaymentIntent Example',
                'payment_method_types' => ['card'],
            ]);

            return response()->json(['clientSecret' => $intent->client_secret]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
