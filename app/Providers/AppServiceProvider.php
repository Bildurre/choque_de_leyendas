<?php

namespace App\Providers;

use App\Services\ImageService;
use App\Services\FactionService;
use App\Services\HeroRaceService;
use App\Services\HeroClassService;
use App\Services\AttackTypeService;
use App\Services\AttackRangeService;
use App\Services\HeroAbilityService;
use Illuminate\Pagination\Paginator;
use App\Services\WysiwygImageService;
use App\Services\AttackSubtypeService;
use App\Services\CostTranslatorService;
use App\Services\HeroSuperclassService;
use Illuminate\Support\ServiceProvider;
use App\Services\HeroAttributesConfigurationService;
use App\Services\HeroService;

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
        HeroAttributesConfigurationService::class,
        CostTranslatorService::class,
        AttackTypeService::class,
        AttackSubtypeService::class,
        AttackRangeService::class,
        HeroAbilityService::class,
        WysiwygImageService::class,
        HeroRaceService::class,
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
      $this->app->singleton(HeroService::class, function ($app) {
        return new HeroService(
          $app->make(ImageService::class),
          $app->make(HeroAttributesConfigurationService::class)
        );
      });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      Paginator::defaultView('vendor.pagination.custom');
      Paginator::defaultSimpleView('vendor.pagination.custom');
    }
}
