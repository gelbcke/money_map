<?php

namespace App\Http\Controllers;

use App\Models\Currencies;
use App\Models\Expense;
use App\Models\Timezone;
use App\Models\UserGroup;
use Gate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $currency = Currencies::get();

        $language = ['pt_BR', 'en'];

        $theme = [
            'Default' => "sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed sidebar-collapse",
            'Dark' => "dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed sidebar-collapse",
            'Default_Mini' => "sidebar-mini layout-fixed control-sidebar-slide-open sidebar-mini-md sidebar-mini-xs layout-navbar-fixed text-sm sidebar-collapse",
            'Dark_Mini' => "dark-mode sidebar-mini layout-fixed control-sidebar-slide-open sidebar-mini-md sidebar-mini-xs layout-navbar-fixed text-sm sidebar-collapse"
        ];

        $timezones = Timezone::Orderby('offset')->get();

        $user_settings = User::where('id', Auth::user()->id)->get();

        return view('profile.edit', compact('currency', 'language', 'theme', 'timezones', 'user_settings'));
    }

    /**
     * Update the profile
     *
     * @param \App\Http\Requests\ProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        auth()->user()->update($request->all());

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param \App\Http\Requests\PasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(Request $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }

    public function settings(Request $request)
    {
        User::where('id', Auth::user()->id)->update(
            [
                'currency_id' => $request->input('currency_id'),
                'language' => $request->input('language'),
                'theme' => $request->input('theme'),
                'timezone_id' => $request->input('timezone_id'),
            ]
        );

        return back()->withSettings(__('Settings Updated'));
    }
}
