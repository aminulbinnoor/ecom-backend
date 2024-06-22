<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Illuminate\Support\Facades\Route;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Route::get('/',function(){
            return response()->json([
                'website' => "https://p2p.com.bd",
                'version' => '1.0.0',
                'api' => 'rest api',
                'developed by' => 'p2p tech team',
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Schema::defaultStringLength(191);
      include __DIR__.'/../Helper/basic.php';
    }
}
