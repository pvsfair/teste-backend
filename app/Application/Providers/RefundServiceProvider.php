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
            'App\Domain\Interfaces\Repositories\IRefundRepository',
            'App\Infrastructure\Repositories\RefundRepository'
        );
        $this->app->bind(
            'App\Domain\Interfaces\Services\IRefundService',
            'App\Domain\Services\RefundService'
        );
    }

    public function provides()
    {
        return[
            'App\Domain\Interfaces\Repositories\IRefundRepository',
            'App\Domain\Interfaces\Services\IRefundService',
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
