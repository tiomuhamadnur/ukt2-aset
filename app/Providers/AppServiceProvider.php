<?php

namespace App\Providers;

use App\Models\Seksi;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $seksiList = Seksi::select('id', 'uuid', 'name')->orderBy('name')->get();

        View::share('seksiList', $seksiList);
    }
}
