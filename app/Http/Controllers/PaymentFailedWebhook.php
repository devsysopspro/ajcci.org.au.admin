<?php

namespace App\Http\Controllers;

use App\Mail\PaymentFailedNotification;
use App\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


/**
 * AMOcrm Webhook для автоматического распределения задач фотографам (фоткать объекты)
 *
 * Class AutoTaskForPhotographer
 * @package App\Http\Controllers
 */
class PaymentFailedWebhook extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        try {
            $data = $request->all();
            if ($data['type'] === 'charge.failed') {
                $invoiceID = $data['data']['object']['invoice'];
                $failureCode = $data['data']['object']['failure_code'];
                $failureMessage = $data['data']['object']['failure_message'];

                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
                $invoice = $stripe->invoices->retrieve($invoiceID, []);

                $user = Member::where('subscription', $invoice->subscription)->first();
                if (!empty($user)) {
                    $result = [
                        'user' => $user,
                        'failureCode' => $failureCode,
                        'failureMessage' => $failureMessage,
                    ];

                    Mail::to(env('EMAIL_TO'))->send(new PaymentFailedNotification($result));
                }
            }

        } catch (\Exception $e) {

        }
        return response()->json([], 200);
    }
}
