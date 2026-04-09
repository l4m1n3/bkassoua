<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Auth;
use App\Models\Cart; // Adjust based on your Cart model
use App\Models\Vendor;
use Illuminate\Support\Facades\Broadcast;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user()->role === 'vendor') {
                $vendor = Vendor::where('user_id', Auth::id())->first();
                $view->with('vendor', $vendor);
            }
        });
        Schema::defaultStringLength(191);
        View::composer('layouts.slaves', function ($view) {
            $cartCount = 0;

            if (Auth::check()) {
                // For authenticated users, count items in the database
                $cartCount = Cart::where('user_id', Auth::id())->count();
            } else {
                // For guests, count items in the session
                $cartItems = session()->get('cart', []);
                $cartCount = count($cartItems);
            }

            $view->with('cartCount', $cartCount);
        });

        Broadcast::routes();
        require base_path('routes/channels.php');
    }
}
