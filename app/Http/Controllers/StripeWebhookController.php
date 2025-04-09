<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation; 
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        $eventType = $payload['type'] ?? null;

        Log::info('Stripe Webhook Received: ' . $eventType);

        if ($eventType === 'checkout.session.completed') {
            $session = $payload['data']['object'];

            Donation::create([
                'user_id' => $session['metadata']['user_id'],
                'amount' => $session['amount_total'] / 100, 
                'currency' => $session['currency'],
                'payment_status' => $session['payment_status'],
                'stripe_session_id' => $session['id'],
            ]);
        }

        return response()->json(['status' => 'success']);
    }
}

