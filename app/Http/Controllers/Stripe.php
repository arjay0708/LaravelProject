<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Stripe extends Controller
{
    //
    public function stripePayment(Request $request)
    {
        $totalPayment = $request->input('total_payment');
        $typeOfRoom = $request->input('type_of_room');
        $totalNights = $request->input('total_nights');

        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
        $response = $stripe->checkout->sessions->create([

            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'type_of_room' => $typeOfRoom,
                            'total_nights' => $totalNights,
                        ],
                        'unit_amount' => $totalPayment * 100,


                    ],
                ],

            ],
            'mode' => 'payment',
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' =>  route('cancel'),

        ]);

        // dd($response);
        if (isset($response->id) && $response->id != '') {
            return redirect($response->url);
        } else {
            return redirect()->route('cancel');
        }
    }

    public function success(Request $request)
    {

        if (isset($request->session_id)) {

            $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
            $response = $stripe->checkout->sessions->retrieve($request->session_id);

            // dd($response);
            return "Payment is successful";
        } else {
            return redirect()->route('cancel');
        }
    }

    public function cancel()
    {

        return "Payment is canceled";
    }
}
