<?php

namespace App\Providers;

use App\Services\Game\CardService;
use App\Services\Game\HeroService;
use App\Services\Media\ImageService;
use Illuminate\Pagination\Paginator;
use App\Services\Game\FactionService;
use App\Services\Game\CardTypeService;
use App\Services\Game\HeroRaceService;
use App\Services\Game\HeroClassService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Services\Game\AttackRangeService;
use App\Services\Game\HeroAbilityService;
use App\Services\Game\AttackSubtypeService;
use App\Services\Game\EquipmentTypeService;
use App\Services\Media\WysiwygImageService;
use App\Services\Game\CostTranslatorService;
use App\Services\Game\HeroSuperclassService;
use App\Services\Content\PageService;
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
        HeroClassService::class,
        HeroSuperclassService::class,
        HeroAttributesConfigurationService::class,
        CostTranslatorService::class,
        AttackSubtypeService::class,
        AttackRangeService::class,
        HeroAbilityService::class,
        WysiwygImageService::class,
        HeroRaceService::class,
        EquipmentTypeService::class,
        CardTypeService::class,
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
      // Game services
      $this->app->singleton(FactionService::class, function ($app) {
        return new FactionService($app->make(ImageService::class));
      });
      
      $this->app->singleton(HeroService::class, function ($app) {
        return new HeroService(
          $app->make(ImageService::class),
          $app->make(HeroAttributesConfigurationService::class)
        );
      });
      
      $this->app->singleton(CardService::class, function ($app) {
        return new CardService(
          $app->make(ImageService::class),
          $app->make(CostTranslatorService::class)
        );
      });

      // Content services
      $this->app->singleton(PageService::class, function ($app) {
        return new PageService(
          $app->make(ImageService::class)
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

      Model::preventLazyLoading(!app()->isProduction());
      Model::preventSilentlyDiscardingAttributes(!app()->isProduction());
    }
}