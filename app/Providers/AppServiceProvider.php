<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

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
        $this->app->register(\Yajra\DataTables\DataTablesServiceProvider::class);

        // Share categories with all views
        View::composer([
            'layouts.app', 
            'welcome',
            'products.*',
            'seller.products.*'
        ], 'App\Http\ViewComposers\CategoryComposer');

        Paginator::useBootstrap();

        // Share profile completion data with all views
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $incompleteFields = [];
                
                if(empty($user->name)) $incompleteFields[] = 'Full Name';
                if(empty($user->mobile_number)) $incompleteFields[] = 'Contact Number';
                if(empty($user->gender)) $incompleteFields[] = 'Gender';
                if(empty($user->dob)) $incompleteFields[] = 'Date of Birth';
                
                $profileComplete = empty($incompleteFields);
                
                $view->with('profileComplete', $profileComplete);
                $view->with('incompleteFields', $incompleteFields);
            }
        });
    }
}
