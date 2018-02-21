<?php

namespace App\Packages\IfThen;

use App\Http\Controllers\Controller;

use App\Packages\IfThen\Requests\IfThenPaymentReferenceRequest;
use App\Packages\Store\Events\PaymentReceived;

use App\Models\Store\PaymentReference;


class IfThenController extends Controller
{
    public function paymentReceived(IfThenPaymentReferenceRequest $request)
    {
        $payment_reference = PaymentReference::with('order')
        ->where([
            'entity' => $request->get('entidade'),
            'reference' => rtrim(chunk_split($request->get('referencia'), 3, ' ')),
            'amount' => $request->get('valor')
        ])
        ->first();

        if($payment_reference)
        {
            // trigger events
            event(new PaymentReceived($payment_reference->order));

            return response(200);
        }
        else{
            abort(403);
        }
    }
}