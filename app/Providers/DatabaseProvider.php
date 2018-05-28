<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class DatabaseProvider extends ServiceProvider
{
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}