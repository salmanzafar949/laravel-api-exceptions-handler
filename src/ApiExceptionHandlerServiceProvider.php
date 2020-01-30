<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 01/06/2020
 * Time: 1:34 PM
 */

namespace Salman\ApiExceptionHandler;

use Illuminate\Support\ServiceProvider;
use Salman\ApiExceptionHandler\Commands\PublishTraits;

class ApiExceptionHandlerServiceProvider extends ServiceProvider
{

    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            PublishTraits::class,
        ]);
    }

}
