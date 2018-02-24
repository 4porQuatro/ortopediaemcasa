<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Gloudemans\Shoppingcart\Facades\Cart;

use App\Models\Pages\Page;
use App\Models\Geo\Country;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Redirect path
     */
    public function redirectTo(){
        if(Cart::instance('items')->count())
        {
            $redirect = urli18n('checkout');
        }
        else
        {
            $redirect = urli18n('user-welcome');
        }

        return $redirect;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $page = Page::where('id', 8)->with('articles')->first();

        $countries = Country::pluck('name', 'id');

        return view(
            'front.auth.login',
            compact(
                'page',
                'countries'
            )
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $response = $request->only($this->username(), 'password');
        $response['active'] = 1;

        return $response;
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(urli18n());
    }
}
