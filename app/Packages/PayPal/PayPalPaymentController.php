<?php

namespace App\Packages\PayPal;

use App\Packages\Store\Events\PaymentReceived;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Paypalpayment;
use PayPal\Api\Presentation;
use PayPal\Api\WebProfile;

class PayPalPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function expressCheckout(Request $request)
    {
        $currency = "EUR";

        // Order record
        $order = auth()->user()->orders()->where('id', $request->get('order_id'))->first();

        // Shipping Address
        $shippingAddress= Paypalpayment::shippingAddress();
        $shippingAddress->setLine1(auth()->user()->shipping_address)
            ->setCity(auth()->user()->billing_city)
            ->setPostalCode(auth()->user()->billing_zip_code)
            ->setCountryCode(auth()->user()->billingCountry->iso)
            ->setRecipientName(auth()->user()->shipping_name);


        // Payer
        $payer = Paypalpayment::payer();
        $payer->setPaymentMethod("paypal");


        // Items
        $paypal_items = [];
        foreach($order->items as $item)
        {
            $paypal_item = Paypalpayment::item();
            $paypal_item->setName($item->name)
                ->setCurrency($currency)
                ->setQuantity($item->quantity)
                ->setTax(0)
                ->setPrice($item->taxed_price);

            $paypal_items[] = $paypal_item;
        }


        // Voucher discount
        if($order->voucher_discount > 0)
        {
            $paypal_item = Paypalpayment::item();
            $paypal_item->setName(trans('app.voucher-discount'))
                ->setCurrency($currency)
                ->setQuantity(1)
                ->setTax(0)
                ->setPrice("-" . $order->voucher_discount);

            $order->items_total -= $order->voucher_discount;

            $paypal_items[] = $paypal_item;
        }


        // Points discount
        if($order->points_discount > 0)
        {
            $paypal_item = Paypalpayment::item();
            $paypal_item->setName(trans('app.points-discount'))
                ->setCurrency($currency)
                ->setQuantity(1)
                ->setTax(0)
                ->setPrice("-" . $order->points_discount);

            $order->items_total -= $order->points_discount;

            $paypal_items[] = $paypal_item;
        }

        /* add items to list */
        $item_list = Paypalpayment::itemList();
        $item_list->setItems($paypal_items)
            ->setShippingAddress($shippingAddress);


        // Details
        $details = Paypalpayment::details();
        $sub_total = str_replace(',', '', number_format($order->items_total, 2));
        $details->setSubtotal($sub_total)
            ->setTax(0)
            ->setShipping($order->shipping_cost);


        // Payment Amount
        $amount = Paypalpayment::amount();
        $amount->setCurrency($currency)
            ->setTotal($order->total)
            ->setDetails($details);


        // Transaction
        $transaction = Paypalpayment::transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription(config('app.name') . " - " . trans('app.order') . " #" . $order->id)
            ->setInvoiceNumber(uniqid());


        // Redirect URLs
        $redirectUrls = Paypalpayment::redirectUrls();
        $redirectUrls->setReturnUrl(action('\\' . get_class($this) . "@success"))
            ->setCancelUrl(url()->previous());

        // Profile
        $profile = $this->getCreateProfileResponse();
        // Payment
        $payment = Paypalpayment::payment();
        $payment->setExperienceProfileId($profile->id)
            ->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);


        try
        {
            $payment->create(Paypalpayment::apiContext());
        }
        catch (\PayPal\Exception\PayPalConnectionException $e)
        {
            return response()->json(["error" => $e->getMessage()], 400);
        }

        // store request in the database
        PaypalPaymentRequest::create(
            [
                'payment_id' => $payment->id,
                'order_id' => $order->id
            ]
        );
        return redirect($payment->getApprovalLink());
    }

    /**
     * Create a Web Profile
     *
     * @return null|\PayPal\Api\CreateProfileResponse
     */
    private function getCreateProfileResponse()
    {
        $locale = strtoupper(auth()->user()->billingCountry->iso);

        // Create Presentation
        $presentation = new Presentation();
        $presentation->setLogoImage('http://ortopediaemcasa.projetos-4por4.com/front/images/logo/logo.png')
            ->setBrandName(config('app.name'))
            ->setLocaleCode($locale);

        // Create the WebProfile
        $web_profile = new WebProfile();
        $web_profile->setName(config('app.name') . " - " . uniqid())
            ->setPresentation($presentation)
            ->setTemporary(true);

        try
        {
            $profile_response = $web_profile->create(Paypalpayment::apiContext());
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex)
        {
            $profile_response = null;
        }

        return $profile_response;
    }

    /**
     * Updates the order status and redirects the user to thanks page.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        $payment_request = PaypalPaymentRequest::with('order')->where('payment_id', $request->get('paymentId'))->whereNull('token')->first();

        if($payment_request)
        {
            // store token
            $payment_request->token = $request->get('token');
            $payment_request->save();

            // trigger events
            event(new PaymentReceived($payment_request->order));
        }

        return redirect()->action('Store\StoreController@paymentReceived');
    }


    /**
     * Sets the return URL, if the payment fails or is aborted by the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fail()
    {
        return redirect()->back();
    }
}