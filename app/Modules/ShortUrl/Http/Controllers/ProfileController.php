<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Modules\ShortUrl\Http\Requests\PasswordRequest;
use App\Modules\ShortUrl\Http\Requests\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class ProfileController extends ShortUrlController
{
    /**
     * Change the password.
     *
     * @param PasswordRequest $request
     * @return RedirectResponse
     */
    public function password(PasswordRequest $request): RedirectResponse
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('account.password.success'));
    }

    public function verified()
    {
        return view('shorturl::profile.verified');
    }
}
