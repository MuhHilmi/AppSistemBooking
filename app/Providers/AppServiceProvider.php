<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\Booking;
use App\Models\SiteSetting;
use App\Policies\BookingPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
        Gate::policy(Booking::class, BookingPolicy::class);

        View::composer(
            ['landing.partials.navbar', 'landing.partials.hero', 'landing.partials.footer', 'layouts.dashboard'],
            function ($view) {
                $view->with('siteSettings', SiteSetting::current());
            }
        );
    }
}
