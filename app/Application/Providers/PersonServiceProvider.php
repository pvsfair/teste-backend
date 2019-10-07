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
        $this->app->bind(
            'App\Domain\Interfaces\Services\IPersonService',
            'App\Domain\Services\PersonService'
        );
    }

    public function provides()
    {
        return[
            'App\Domain\Interfaces\Repositories\IPersonRepository',
            'App\Domain\Interfaces\Services\IPersonService',
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
