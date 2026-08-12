<?php

namespace App\Providers;

use App\Models\ClientConfig;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CustomUIProvider extends ServiceProvider
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
        $customConfig = ClientConfig::object();
        View::share('custom', $customConfig);
    }
}
