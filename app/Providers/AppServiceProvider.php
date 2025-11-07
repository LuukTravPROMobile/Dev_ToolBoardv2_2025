<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * 
     * Hier registreer je bindings in de service container.
     * Deze method wordt uitgevoerd VOOR boot().
     */
    public function register(): void
    {
        // Voorbeeld: Singleton binding
        $this->app->singleton(SomeService::class, function ($app) {
            return new SomeService();
        });

        // Voorbeeld: Interface binding
        $this->app->bind(
            \App\Contracts\PaymentInterface::class,
            \App\Services\StripePaymentService::class
        );

        // Conditionele registratie (alleen in lokale omgeving)
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     * 
     * Hier voer je acties uit die alle services nodig hebben.
     * Deze method wordt uitgevoerd NA register().
     */
    public function boot(): void
    {
        // Fixes voor oudere MySQL/MariaDB versies
        Schema::defaultStringLength(191);

        // Pagination met Bootstrap in plaats van Tailwind
        Paginator::useBootstrapFive();

        // View composers - data delen met specifieke views
        View::composer('layouts.app', function ($view) {
            $view->with('appName', config('app.name'));
        });

        // View share - data delen met alle views
        View::share('currentYear', date('Y'));

        // Custom validation rules
        \Validator::extend('dutch_postal_code', function ($attribute, $value) {
            return preg_match('/^[1-9][0-9]{3}\s?[A-Z]{2}$/i', $value);
        });

        // Model observers registreren
        // \App\Models\User::observe(\App\Observers\UserObserver::class);

        // Force HTTPS in productie
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }
}