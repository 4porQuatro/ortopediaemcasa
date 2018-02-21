<?php

namespace App\Http\Controllers\PrivateArea;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\PrivateArea\UserPasswordRequest;

use App\Models\Pages\Page;
use App\Models\User;

class UserPasswordController extends Controller
{
    /**
     * UserPasswordController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Shows the change password form.
     *
     * @return View
     */
    public function edit()
    {
        $page = Page::find(13);

        $user = \Auth::user();

        return view(
            'pages.user-pass.index',
            compact(
                'page',
                'user'
            )
        );
    }

    /**
     * Updates the password.
     *
     * @return View
     */
    public function update(UserPasswordRequest $request, User $user)
    {
        $user->update([
            'password' => bcrypt($request->password)
        ]);

        $request->session()->flash('status', trans('app.data-saved-success'));

        return back();
    }
}
