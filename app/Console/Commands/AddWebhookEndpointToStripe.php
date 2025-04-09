<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddWebhookEndpointToStripe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:add-endpoint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding webhook endpoint to Stripe';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET_KEY')
        );
        $stripe->webhookEndpoints->create([
            'url' => env('APP_URL') . '/api/stripe-payment-failed',
            'enabled_events' => [
                'charge.failed',
            ],
        ]);

        echo "Done\n";
    }
}
