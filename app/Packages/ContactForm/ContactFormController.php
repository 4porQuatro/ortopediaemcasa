<?php

namespace App\Packages\ContactForm;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    /**
     * @param ContactFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function request(ContactFormRequest $request)
    {
        if (config('app.env') != 'local') {
            Mail::send(new ContactFormMail($request));
        }

        $success_msg = trans('app.subscription-success');

        if($request->ajax())
        {
            return response()->json(['success_msg' => $success_msg]);
        }

        $request->session()->flash('newsletter_msg', $success_msg);

        return back();
    }
}
