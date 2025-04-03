<?php

namespace App\Providers;

use App\Services\ImageService;
use App\Services\FactionService;
use App\Services\HeroClassService;
use App\Services\SuperclassService;
use Illuminate\Support\ServiceProvider;
use App\Services\HeroAttributeConfigurationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      $this->app->singleton(ImageService::class, function ($app) {
        return new ImageService();
      });
  
      $this->app->singleton(FactionService::class, function ($app) {
        return new FactionService($app->make(ImageService::class));
      });
  
      $this->app->singleton(HeroClassService::class, function ($app) {
        return new HeroClassService();
      });
  
      $this->app->singleton(SuperclassService::class, function ($app) {
        return new SuperclassService();
      });

      $this->app->singleton(HeroAttributeConfigurationService::class, function ($app) {
        return new HeroAttributeConfigurationService();
      });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
