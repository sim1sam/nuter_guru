<?php

namespace App\Providers;

use App\Helpers\MailHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Throwable;

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
        // Use Bootstrap 5 pagination view
        Paginator::defaultView('pagination::bootstrap-5');
        Paginator::defaultSimpleView('pagination::simple-bootstrap-5');

        // Use Admin → Email Configuration for ALL outgoing mail
        // (orders, password reset, notifications, etc.) — not .env localhost:1025
        try {
            MailHelper::setMailConfig();
        } catch (Throwable $e) {
            // Ignore during migrate / missing DB
        }
    }
}
