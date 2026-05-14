<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\profile\ProfileCard;
use App\View\Components\profile\PersonalInfoCard;
use App\View\Components\enutrition\EnutritionMetrics;
use App\View\Components\header\UserDropdown;
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
         Blade::component('enutrition.enutrition-metrics', EnutritionMetrics::class);
 	 Blade::component('header.user-dropdown', UserDropdown::class);
Blade::component('profile.profile-card', ProfileCard::class);
Blade::component('profile.personal-info-card', PersonalInfoCard::class);
    }
}
