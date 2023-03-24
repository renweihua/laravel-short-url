<?php

namespace App\Modules\ShortUrl\Http\Controllers\Admin;

use App\Models\User;
use App\Modules\ShortUrl\Http\Controllers\ShortUrlController;
use App\Modules\ShortUrl\Http\Requests\Admin\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends ShortUrlController
{
    /**
     * @var string
     */
    protected $redirectPath = '/';

    /**
     * Display a listing of the users.
     *
     * @param  User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        return view('shorturl::users.index', ['users' => $model->paginate(15)]);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('shorturl::users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  UserRequest  $request
     * @param  User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        $model->create($request->merge(['password' => Hash::make($request->get('password'))])->all());

        return redirect()->route('user.index')->withStatus(__('account.user_created'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('shorturl::users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User  $user)
    {
        $user->update(
            $request->merge(['password' => Hash::make($request->get('password'))])
                ->except([$request->get('password') ? '' : 'password']
                ));

        return redirect()->route('user.index')->withStatus(__('account.user_updated'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->withStatus(__('account.user_deleted'));
    }
}
