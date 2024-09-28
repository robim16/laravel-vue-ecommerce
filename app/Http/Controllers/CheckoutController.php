<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\Cart;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();

        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        list($products, $cartItems) = Cart::getProductsAndCartItems();


        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;

        foreach ($products as $product) {
            $totalPrice += $product->price;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->title,
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => $cartItems[$product->id]['quantity'],
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        $orderData = [
            'total_price' => $totalPrice,
            'status' => OrderStatus::Unpaid,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];

        $order = Order::create($orderData);

        $paymentData = [
            'order_id' => $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];

        $payment = Payment::create($paymentData);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        try {
            
            $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));

            $session_id = $request->get('session_id');

            if ($session) {
                return view('checkout.failure');
            }
            
            $customer = \Stripe\Customer::retrieve($session->customer);
    
            return view('checkout.success', compact('customer'));

        } catch (\Throwable $th) {
            return view('checkout.failure');
        }
    }

    public function failure(Request $request)
    {

    }
}
