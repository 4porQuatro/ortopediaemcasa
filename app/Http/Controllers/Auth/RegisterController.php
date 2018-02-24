<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrivateArea\UserRequest;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\Mail\Auth\UserRegistrationMail;
use App\Mail\Auth\WelcomeUserMail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /*
     * RegisterController constructor.
     */
    public function __construct()
    {
        $this->redirectTo = urli18n('user-welcome');
        $this->middleware('guest');
    }


    /**
     * Handle a registration request for the application.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(UserRequest $request)
    {
        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'billing_name' => $data['billing_name'],
            'billing_phone' => $data['billing_phone'],
            'billing_address' => $data['billing_address'],
            'billing_city' => $data['billing_city'],
            'billing_zip_code' => $data['billing_zip_code'],
            'billing_country_id' => $data['billing_country_id'],
            'shipping_name' => $data['billing_name'],
            'shipping_phone' => $data['billing_phone'],
            'shipping_address' => $data['billing_address'],
            'shipping_city' => $data['billing_city'],
            'shipping_zip_code' => $data['billing_zip_code'],
            'shipping_country_id' => $data['billing_country_id'],
            'vat_number' => $data['vat_number'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return $user;
    }
}
