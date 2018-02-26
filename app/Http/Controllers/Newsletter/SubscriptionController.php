<?php

namespace App\Http\Controllers\Newsletter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Newsletter\SubscriptionRequest;
use App\Mail\Newsletter\SubscriptionMail;

use App\Models\Newsletter\Subscriber;

class SubscriptionController extends Controller
{
    public function store(SubscriptionRequest $request)
    {
        // Subscriber::create($request->all());

        // send notification
        if(env('APP_ENV') != 'local')
        {
            \Mail::queue(new SubscriptionMail($request));
        }

        $success_msg = trans('app.subscription-success');

        if($request->ajax())
        {
            return response()->json(['success_msg' => $success_msg]);
        }

        $request->session()->flash('success_msg', $success_msg);

        return back();
    }
}
