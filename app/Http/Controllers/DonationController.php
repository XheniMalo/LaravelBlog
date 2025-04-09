<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\DonationReceived;


class DonationController extends Controller
{
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $presetAmount = $request->input('amount');
        $customAmount = $request->input('custom_amount');
    
        $amount = null;
    
        if ($presetAmount) {
            $amount = (int) $presetAmount;
            if (!in_array($amount, config('donations.amounts'))) {
                abort(403, 'Invalid preset amount.');
            }
        } elseif ($customAmount) {
            $amount = (int) ((float) $customAmount * 100); 
            if ($amount < 100) {
                return back()->with('error', 'Minimum custom donation is $1.00');
            }
        } else {
            return back()->with('error', 'Please select or enter an amount to donate.');
        }
    
        Stripe::setApiKey(config('services.stripe.secret'));

        $amount = $request->input('amount');

        $checkoutSession = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'One-Time Donation',
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'metadata' => [
                'user_id' => Auth::id(), 
            ],
            'customer_email' => $user->email,
            'success_url' => route('donate.success') . '?session_id={CHECKOUT_SESSION_ID}',
        ]);
    
    
        return redirect($checkoutSession->url);
    }
    

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            abort(404);
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($sessionId);

        Mail::to(Auth::user())->send(new DonationReceived($session->amount_total));

        return view('donation.index');
    }
}
