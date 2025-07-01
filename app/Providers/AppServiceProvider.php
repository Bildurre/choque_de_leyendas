<?php

namespace App\Providers;

use App\Services\Game\CardService;
use Illuminate\Pagination\Paginator;
use App\Services\Content\PageService;
use App\Services\Game\FactionService;
use App\Services\Content\BlockService;
use App\Services\Game\CardTypeService;
use App\Services\Game\HeroRaceService;
use App\Services\Game\HeroClassService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Services\Game\AttackRangeService;
use App\Services\Game\HeroAbilityService;
use App\Services\Game\AttackSubtypeService;
use App\Services\Game\EquipmentTypeService;
use App\Services\Game\HeroSuperclassService;
use App\Services\Game\HeroAttributesConfigurationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar servicios sin dependencias
        $services = [
            HeroClassService::class,
            HeroRaceService::class,
            HeroAttributesConfigurationService::class,
            CardTypeService::class,
            EquipmentTypeService::class,
            AttackSubtypeService::class,
            HeroAbilityService::class,
            PageService::class,
            BlockService::class,
            HeroSuperclassService::class,
            AttackRangeService::class,
            FactionService::class,
            CardService::class,
        ];
        
        foreach ($services as $service) {
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