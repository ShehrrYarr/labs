<?php

namespace App\Providers;

use App\Models\Mobile;
use App\Models\TransferRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);

        View::composer('user_navbar', function ($view) {
            $users = User::where('id', '!=', Auth::id())->get();

            $userData = $users->map(function ($user) {
                $mobiles = Mobile::where('user_id', $user->id)
                    ->where('availability', 'Available')
                    ->where('is_transfer', false)
                    ->get();

                return [
                    'user' => $user,
                    'mobiles' => $mobiles,
                ];
            });



            $view->with('userData', $userData);
        });
    }




}
