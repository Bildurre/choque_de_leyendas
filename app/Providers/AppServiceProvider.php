<?php

namespace App\Providers;

use App\Services\ImageService;
use App\Services\FactionService;
use App\Services\HeroClassService;
use App\Services\AttackTypeService;
use App\Services\AttackRangeService;
use App\Services\HeroAbilityService;
use App\Services\AttackSubtypeService;
use App\Services\CostTranslatorService;
use App\Services\HeroSuperclassService;
use Illuminate\Support\ServiceProvider;
use App\Services\HeroAttributeConfigurationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      // Register basic services first
      $this->registerBasicServices();
      
      // Register dependent services
      $this->registerDependentServices();
    }

    /**
     * Register services without dependencies
     */
    private function registerBasicServices(): void
    {
      $services = [
        ImageService::class,
        HeroClassService::class,
        HeroSuperclassService::class,
        HeroAttributeConfigurationService::class,
        CostTranslatorService::class,
        AttackTypeService::class,
        AttackSubtypeService::class,
        AttackRangeService::class,
        HeroAbilityService::class,
        WysiwygImageService::class
      ];
      
      foreach ($services as $service) {
        $this->app->singleton($service);
      }
    }

    /**
     * Register services with dependencies
     */
    private function registerDependentServices(): void
    {
      $this->app->singleton(FactionService::class, function ($app) {
        return new FactionService($app->make(ImageService::class));
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
