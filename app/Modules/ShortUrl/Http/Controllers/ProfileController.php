<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Modules\ShortUrl\Http\Requests\PasswordRequest;
use App\Modules\ShortUrl\Http\Requests\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class ProfileController extends ShortUrlController
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('shorturl::profile.edit');
    }

    /**
     * Update the profile.
     *
     * @param ProfileRequest $request
     * @return RedirectResponse
     */
    public function update(ProfileRequest $request): RedirectResponse
    {
        auth()->user()->update($request->all());

        return back()->withStatus(__('account.update_success'));
    }

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
