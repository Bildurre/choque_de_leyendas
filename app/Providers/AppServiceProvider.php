<?php

namespace App\Providers;

use App\Services\Media\ImageService;
use Illuminate\Pagination\Paginator;
use App\Services\Content\PageService;
use App\Services\Content\BlockService;
use App\Services\Game\CardTypeService;
use App\Services\Game\FactionService;
use App\Services\Game\HeroRaceService;
use App\Services\Game\HeroClassService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Services\Game\AttackRangeService;
use App\Services\LocalizedRoutingService;
use App\Services\Game\AttackSubtypeService;
use App\Services\Game\EquipmentTypeService;
use App\Services\Media\WysiwygImageService;
use App\Services\Game\CostTranslatorService;
use App\Services\Game\HeroSuperclassService;
use App\Services\Game\HeroAttributesConfigurationService;

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
        CostTranslatorService::class,
        WysiwygImageService::class,
        LocalizedRoutingService::class,
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
      // Services that depend on ImageService
      $imageServiceDependents = [
        PageService::class,
        BlockService::class,
        HeroSuperclassService::class,
        AttackRangeService::class,
        FactionService::class,
      ];
      
      foreach ($imageServiceDependents as $service) {
        $this->app->singleton($service, function ($app) use ($service) {
          return new $service($app->make(ImageService::class));
        });
      }
      
      // Register services without external dependencies but that should be registered after basic services
      $otherServices = [
        HeroClassService::class,
        HeroRaceService::class,
        HeroAttributesConfigurationService::class,
        CardTypeService::class,
        EquipmentTypeService::class,
        AttackSubtypeService::class,
      ];
      
      foreach ($otherServices as $service) {
        $this->app->singleton($service);
      }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      Paginator::defaultView('vendor.pagination.custom');
      Paginator::defaultSimpleView('vendor.pagination.custom');

      Model::preventLazyLoading(!app()->isProduction());
      Model::preventSilentlyDiscardingAttributes(!app()->isProduction());
    }
}