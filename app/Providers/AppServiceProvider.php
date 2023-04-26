<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->globalNotifications();
        Paginator::useBootstrap();
    }

    /**
     * Get the Notifications
     */
    private function globalNotifications()
    {
        view()->composer('layouts.navbars.navs.auth', function ($view) {

            $view->with(
                'G_notifications',
                Notification::where('user_id', Auth::user()->id)
                    ->whereNull('readed')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );
        });
    }
}
