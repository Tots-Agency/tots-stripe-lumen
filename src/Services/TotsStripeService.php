<?php

namespace Tots\Stripe\Services;

class TotsStripeService 
{
    protected $secretKey = '';
    /**
     * 
     *
     * @var \Stripe\StripeClient
     */
    protected $stripe;

    public function __construct($config)
    {
        $this->secretKey = $config['secret_key'];
        $this->stripe = new \Stripe\StripeClient($this->secretKey);
        \Stripe\Stripe::setApiKey($this->secretKey);
    }
    /**
     * Crea la sesión para guardar la tarjeta para futuros pagos
     *
     * @param string $customerId
     * @param string $successUrl
     * @param string $cancelUrl
     * @return void
     */
    public function createModeSetupSessionCheckout($customerId, $successUrl, $cancelUrl)
    {
        return \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'setup',
            'customer' => $customerId,
            'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
        ]);
    }
    /**
     * Procesa un pago offline con los medios de pago guardados del cliente
     *
     * @param string $customerId
     * @param string $paymentMethodId
     * @param double $amount
     * @param string $currency
     * @return void
     */
    public function payOffline($customerId, $paymentMethodId, $amount, $currency = 'usd')
    {
        return \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'customer' => $customerId,
            'payment_method' => $paymentMethodId,
            'off_session' => true,
            'confirm' => true,
        ]);
    }
    /**
     * Obtiene todos los medios de pago del cliente guardados
     *
     * @param string $customerId
     * @return array
     */
    public function getPaymentMethodsSaved($customerId)
    {
        return $this->stripe->paymentMethods->all(['customer' => $customerId, 'type' => 'card']);
    }
    /**
     * Crea un webhook
     *
     * @param array $events
     * @param string $url
     * @return void
     */
    public function createWebhook($events, $url)
    {
        return $this->stripe->webhookEndpoints->create([
            'url' => $url,
            'enabled_events' => $events,
        ]);
    }
}
