<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log; // Add this at the top

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentLink;
use Stripe\Price;
use Stripe\Product;


class PaymentController extends Controller
{

    public function processDonation(Request $request)
    {
        Log::info('Incoming Donation Request', $request->all());
    
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'email' => 'required|email',
            'night_bright' => 'nullable|string|max:255',
            'anonymous' => 'nullable|in:0,1',
            'contact' => 'nullable|in:0,1',
            'donation_type' => 'required|in:one-time,monthly',
            'payment_method' => 'required|in:visa,amex,us_bank,cash_app',
            'selected_amount' => 'required|numeric|min:1',
            'tip_percent' => 'nullable|numeric|min:0|max:100',
        ]);
    
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
    
            $donorName = $validated['donor_name'];
            $totalAmount = $validated['selected_amount'];
            $anonymous = $validated['anonymous'] == '1';
            $contact = $validated['contact'] == '1';
            $email = $validated['email'];
            $nightBright = $validated['night_bright'] ?? 'Night Bright';
            $donationType = $validated['donation_type'];
            $paymentMethod = $validated['payment_method'];
            $tipPercent = $validated['tip_percent'] ?? 0;
    
            $unitAmount = intval(round($totalAmount * 100));
    
            Log::info("Creating product for $donorName with amount $unitAmount cents");
    
            $product = Product::create([
                'name' => 'Missionary Donation - ' . ($anonymous ? 'Anonymous' : $donorName),
                'metadata' => [
                    'email' => $email,
                    'night_bright' => $nightBright,
                    'contact' => $contact ? 'yes' : 'no',
                    'tip_percent' => $tipPercent,
                ],
            ]);
    
            $price = Price::create([
                'product' => $product->id,
                'unit_amount' => $unitAmount,
                'currency' => 'usd',
                'recurring' => $donationType === 'monthly' ? ['interval' => 'month'] : null,
            ]);
    
            $paymentLink = PaymentLink::create([
                'line_items' => [[
                    'price' => $price->id,
                    'quantity' => 1
                ]],
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => ['url' => url('/donation/thank-you')],
                ],
                'metadata' => [
                    'email' => $email,
                    'payment_method' => $paymentMethod,
                    'donation_type' => $donationType,
                    'anonymous' => $anonymous ? 'yes' : 'no',
                    'tip_percent' => $tipPercent,
                ],
            ]);
    
            Log::info("Stripe Payment URL: " . $paymentLink->url);
    
            return response()->json(['payment_url' => $paymentLink->url]);
        } catch (\Exception $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed. Please try again.'], 500);
        }
    }

    public function thankYou()
    {
        return view('donation.thank-you');
    }
}



