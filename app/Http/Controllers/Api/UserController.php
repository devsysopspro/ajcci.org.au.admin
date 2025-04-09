<?php

namespace App\Http\Controllers\Api;

use App\Mail\ApproveNotification;
use App\Mail\DeclineNotification;
use App\Member;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {

        return Member::orderBy('status', 'asc')->get();
    }

    public function delete(Request $request, $id)
    {
        $user = Member::find($id);
        if (empty($user)) {
            abort(404);
        }
        $user->forceDelete();
    }

    public function approve(Request $request, $id)
    {
        $user = Member::find($id);
        if (empty($user)) {
            abort(404);
        }
        if ($user->status !== Member::STATUS_PENDING) {
            abort(500);
        }

        $approveResult = $request->get('approve');
        if ($approveResult === true) {
            $user->status = Member::STATUS_ACTIVE;
        } else {
            $user->status = Member::STATUS_DECLINED;

            // cancel subscription
            try {
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
                $stripe->subscriptions->cancel($user->subscription, []);
            } catch (\Stripe\Exception\InvalidArgumentException $e) {
                $user->save();
                return $user;
            }

            // refund payment
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $subscription = \Stripe\Subscription::retrieve($user->subscription);
            $latestInvoice = \Stripe\Invoice::retrieve($subscription->latest_invoice);

            $latestChargeID = $latestInvoice->charge;
            $refund = \Stripe\Refund::create([
                'charge' => $latestChargeID,
                'amount' => $latestInvoice->amount_paid,
            ]);

            if ($refund->status !== "succeeded") {
                // refund failed
                abort(500);
            }

        }
        $user->save();


        if ($user->status === Member::STATUS_ACTIVE) {
            Mail::to($user->email)->send(new ApproveNotification($user));
        } else {
            Mail::to($user->email)->send(new DeclineNotification($user));
        }

        return $user;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return mixed
     */
    public function show($id)
    {
        return Member::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
