<?php

namespace App\Providers;

use App\Http\Validators\Polenamevaridator;
use Illuminate\Support\ServiceProvider;

class PoleNameServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $validator = $this->app['validator'];
        $validator->resolver( function ( $translator, $data, $rules, $messages ) {
            return new Polenamevaridator( $translator, $data, $rules, $messages );
        });
    }
}
