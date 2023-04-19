<?php

namespace App\Providers;

use App\Models\Notification;
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
    }

    /**
     * Get the Notifications
     */
    private function globalNotifications()
    {
        view()->composer(array('*.*'), function ($view) {
            $view->with('G_notifications', Notification::where('user_id', Auth::user()->id)->whereNull('readed')->get());
        });
    }
}
