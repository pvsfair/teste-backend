<?php

namespace App\Application\Providers;

use Illuminate\Support\ServiceProvider;

class RefundServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Domain\Repositories\IRefundRepository',
            'App\Infrastructure\Repositories\RefundRepository'
        );
    }

    public function provides()
    {
        return[
            'App\Domain\Repositories\IRefundRepository',
            ];
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
