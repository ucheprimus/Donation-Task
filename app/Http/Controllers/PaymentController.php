<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentLink;
use Stripe\Price;
use Stripe\Product;

class PaymentController extends Controller
{
    public function processDonation(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'email' => 'required|email',
            'amount' => 'nullable|numeric|min:1',
            'night_bright' => 'nullable|string|max:255',
            'anonymous' => 'boolean',
            'contact' => 'boolean',
            'donation_type' => 'required|in:one-time,monthly',
            'payment_method' => 'required|in:amex,visa,us_bank,cash_app',
            'selected_amount' => 'required|numeric|min:1',
        ]);

        $amount = $validated['selected_amount'];
        $email = $validated['email'];
        $donorName = $validated['donor_name'];
        $nightBright = $validated['night_bright'];
        $anonymous = $validated['anonymous'] ?? false;
        $contact = $validated['contact'] ?? false;
        $paymentMethod = $validated['payment_method'];
        $donationType = $validated['donation_type'];

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $product = Product::create([
                'name' => 'Missionary Donation - ' . ($anonymous ? 'Anonymous' : $donorName),
                'metadata' => [
                    'email' => $email,
                    'night_bright' => $nightBright,
                    'contact' => $contact ? 'yes' : 'no',
                ],
            ]);

            $price = Price::create([
                'product' => $product->id,
                'unit_amount' => $amount * 100,
                'currency' => 'usd',
            ]);

            $paymentLink = PaymentLink::create([
                'line_items' => [['price' => $price->id, 'quantity' => 1]],
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => ['url' => url('/donation/thank-you')],
                ],
                'metadata' => [
                    'email' => $email,
                    'payment_method' => $paymentMethod,
                    'donation_type' => $donationType,
                ],
            ]);

            return response()->json(['payment_url' => $paymentLink->url], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['Payment processing failed: ' . $e->getMessage()]], 400);
        }
    }

    public function thankYou()
    {
        return view('donation.thank-you');
    }
}



