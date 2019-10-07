<?php

namespace App\Application\Providers;

use Illuminate\Support\ServiceProvider;

class PersonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Domain\Interfaces\Repositories\IPersonRepository',
            'App\Infrastructure\Repositories\PersonRepository'
        );
    }

    public function provides()
    {
        return[
            'App\Domain\Interfaces\Repositories\IPersonRepository',
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
