<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripePaymentController extends Controller
{
    public function stripeCheckout()
    {
        return view('stripe');
    }
    public function createPaymentIntent()
    {
        Stripe::setApiKey(config('services.stripe.stripe_secret'));
        try {
            $intent = PaymentIntent::create([
                'amount' => 11.99 * 100,
                'currency' => 'usd',
                'description' => 'The ICT Bible',
                'payment_method_types' => ['card'],
            ]);
            return response()->json(['clientSecret' => $intent->client_secret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function paymentSuccess()
    {
        $user = auth()->user();
        $user->update([
            'buy_bible' => true
        ]);
        session()->flash('success', 'Payment was successful!');
        return redirect()->route('dashboard');
    }
}
