<?php

namespace App\Http\Controllers\PrivateArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrivateArea\UserRequest;
use App\Models\Pages\Page;
use App\Models\Geo\Country;
use App\Models\User;

class UserProfileController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Shows the edit page.
     *
     * @return View
     */
    public function edit()
    {
        $page = Page::find(14);

        $user = \Auth::user();

        $countries = Country::pluck('name', 'id');

        return view(
            'front.pages.private-area.edit-profile',
            compact(
                'page',
                'user',
                'countries'
            )
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  UserRequest  $request
     * @param  User $user
     *
     * @return View
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->all());

        $request->session()->flash('status', trans('app.data-saved-success'));

        return back();
    }
}
