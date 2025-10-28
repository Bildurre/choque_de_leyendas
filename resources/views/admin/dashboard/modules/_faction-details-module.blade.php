@php
  $factionDetailStats = $stats['faction_details'] ?? null;
  $factions = $stats['factions_list'] ?? [];
  $selectedFactionId = request()->get('faction_id');

  $factionOptions = collect($factions)->mapWithKeys(function ($f) {
    $id   = is_array($f) ? $f['id'] : $f->id;
    $name = is_array($f) ? $f['name'] : $f->name;
    $pub  = is_array($f) ? $f['is_published'] : $f->is_published;

    return [
      $id => $name . (!$pub ? ' (' . __('admin.draft') . ')' : '')
    ];
  })->toArray();
@endphp

<x-dashboard.dashboard-module 
  :title="__('admin.dashboard.modules.faction_details.title')" 
  icon="layers"
  class="dashboard-module--faction-details"
>
  {{-- Faction Selector --}}
  <div class="dashboard-section">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="dashboard-faction-selector">
      <x-form.select
        name="faction_id"
        id="faction-select"
        class="dashboard-faction-selector__select"
        :label="__('admin.dashboard.modules.faction_details.select_faction')"
        :options="$factionOptions"
        :selected="$selectedFactionId"
        :placeholder="__('admin.dashboard.modules.faction_details.select_placeholder')"
        onchange="this.form.submit()"
      />
    </form>
  </div>

  @if($factionDetailStats)
    @php
      $factionInfo = $factionDetailStats['faction_info'];
      $heroStats = $factionDetailStats['heroes'];
      $cardStats = $factionDetailStats['cards'];
      $abilityStats = $factionDetailStats['hero_abilities'];
    @endphp

    {{-- Faction Header --}}
    <div class="dashboard-section">
      <div class="dashboard-faction-header">
        <div class="dashboard-faction-header__info">
          <h4 class="dashboard-faction-header__name" style="color: {{ $factionInfo['color'] }}">
            {{ $factionInfo['name'] }}
          </h4>
          @if(!$factionInfo['is_published'])
            <span class="dashboard-faction-header__badge">{{ __('admin.draft') }}</span>
          @endif
        </div>
        <div class="dashboard-faction-header__stats">
          <span>{{ $factionInfo['total_heroes'] }} {{ __('entities.heroes.plural') }}</span>
          <span>{{ $factionInfo['total_cards'] }} {{ __('entities.cards.plural') }}</span>
        </div>
      </div>
    </div>

    {{-- HEROES SECTION --}}
    @if($heroStats['total'] > 0)
      <div class="dashboard-section">
        <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.faction_details.heroes_stats') }}</h4>
        
        {{-- Hero Distribution --}}
        <div class="dashboard-distribution-grid">
          {{-- By Superclass --}}
          @if(count($heroStats['by_superclass']) > 0)
            <div class="dashboard-chart-container">
              <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.faction_details.heroes_by_superclass') }}</h5>
              <x-dashboard.dashboard-chart
                id="faction-heroes-superclass-chart"
                type="bar"
                height="200px"
                :data="[
                  'labels' => collect($heroStats['by_superclass'])->pluck('name')->toArray(),
                  'datasets' => [[
                    'label' => __('entities.heroes.plural'),
                    'data' => collect($heroStats['by_superclass'])->pluck('count')->toArray(),
                    'backgroundColor' => $factionInfo['color'],
                    'borderColor' => $factionInfo['color'],
                    'borderWidth' => 1,
                  ]]
                ]"
              />
            </div>
          @endif

          {{-- By Class --}}
          @if(count($heroStats['by_class']) > 0)
            <div class="dashboard-chart-container">
              <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.faction_details.heroes_by_class') }}</h5>
              <x-dashboard.dashboard-chart
                id="faction-heroes-class-chart"
                type="bar"
                height="200px"
                :data="[
                  'labels' => collect($heroStats['by_class'])->pluck('name')->toArray(),
                  'datasets' => [[
                    'label' => __('entities.heroes.plural'),
                    'data' => collect($heroStats['by_class'])->pluck('count')->toArray(),
                    'backgroundColor' => $factionInfo['color'],
                    'borderColor' => $factionInfo['color'],
                    'borderWidth' => 1,
                  ]]
                ]"
                {{-- :options="['indexAxis' => 'y']" --}}
              />
            </div>
          @endif
        </div>

        {{-- Attributes --}}
        <div class="dashboard-section">
          <h5 class="dashboard-section__subtitle">{{ __('admin.dashboard.modules.faction_details.hero_attributes') }}</h5>
          <div class="dashboard-stats-grid">
            @foreach(['agility', 'mental', 'will', 'strength', 'armor'] as $attribute)
              <x-dashboard.dashboard-stat-item
                :label="__('entities.heroes.attributes.' . $attribute)"
                :value="$heroStats['attributes'][$attribute]['avg'] . ' (' . $heroStats['attributes'][$attribute]['min'] . '-' . $heroStats['attributes'][$attribute]['max'] . ')'"
                icon="sliders"
              />
            @endforeach
          </div>
        </div>
      </div>
    @endif

    {{-- HERO ABILITIES SECTION --}}
    @if($abilityStats['total'] > 0)
      <div class="dashboard-section">
        <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.faction_details.abilities_stats') }}</h4>
        
        <div class="dashboard-distribution-grid">
          {{-- By Attack Type --}}
          @if(count($abilityStats['by_attack_type']) > 0)
            <div class="dashboard-chart-container">
              <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.faction_details.abilities_by_attack_type') }}</h5>
              <x-dashboard.dashboard-chart
                id="faction-abilities-attack-type-chart"
                type="bar"
                height="200px"
                :data="[
                  'labels' => [
                    __('entities.hero_abilities.attack_types.physical'),
                    __('entities.hero_abilities.attack_types.magical')
                  ],
                  'datasets' => [[
                    'label' => __('entities.hero_abilities.plural'),
                    'data' => [
                      $abilityStats['by_attack_type']['physical'] ?? 0,
                      $abilityStats['by_attack_type']['magical'] ?? 0
                    ],
                    'backgroundColor' => [
                      '#f15959',
                      '#408cfd'
                    ],
                    'borderColor' => [
                      '#f15959',
                      '#408cfd'
                    ],
                    'borderWidth' => 1,
                  ]]
                ]"
                {{-- :options="['indexAxis' => 'y']" --}}
              />
            </div>
          @endif

          {{-- By Attack Subtype --}}
          @if(count($abilityStats['by_attack_subtype']) > 0)
            <div class="dashboard-chart-container">
              <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.faction_details.abilities_by_attack_subtype') }}</h5>
              <x-dashboard.dashboard-chart
                id="faction-abilities-attack-subtype-chart"
                type="bar"
                height="200px"
                :data="[
                  'labels' => collect($abilityStats['by_attack_subtype'])->pluck('name')->toArray(),
                  'datasets' => [[
                    'label' => __('entities.hero_abilities.plural'),
                    'data' => collect($abilityStats['by_attack_subtype'])->pluck('count')->toArray(),
                    'backgroundColor' => $factionInfo['color'],
                    'borderColor' => $factionInfo['color'],
                    'borderWidth' => 1,
                  ]]
                ]"
                {{-- :options="['indexAxis' => 'y']" --}}
              />
            </div>
          @endif

          {{-- By Attack Range --}}
          @if(count($abilityStats['by_attack_range']) > 0)
            <div class="dashboard-chart-container">
              <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.faction_details.abilities_by_attack_range') }}</h5>
              <x-dashboard.dashboard-chart
                id="faction-abilities-attack-range-chart"
                type="bar"
                height="200px"
                :data="[
                  'labels' => collect($abilityStats['by_attack_range'])->pluck('name')->toArray(),
                  'datasets' => [[
                    'label' => __('entities.hero_abilities.plural'),
                    'data' => collect($abilityStats['by_attack_range'])->pluck('count')->toArray(),
                    'backgroundColor' => $factionInfo['color'],
                    'borderColor' => $factionInfo['color'],
                    'borderWidth' => 1,
                  ]]
                ]"
                {{-- :options="['indexAxis' => 'y']" --}}
              />
            </div>
          @endif
        </div>
      </div>
    @endif

    {{-- CARDS SECTION --}}
    @if($cardStats['total'] > 0)
      <div class="dashboard-section">
        <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.faction_details.cards_stats') }}</h4>
        
        {{-- Cost Distribution --}}
        <div class="dashboard-distribution-grid">
          {{-- By Dice Cost --}}
          <div class="dashboard-chart-container">
            <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.faction_details.cards_by_dice_cost') }}</h5>
            <x-dashboard.dashboard-chart
              id="faction-cards-dice-cost-chart"
              type="bar"
              height="200px"
              :data="[
                'labels' => collect($cardStats['by_dice_cost'])->pluck('cost')->toArray(),
                'datasets' => [[
                  'label' => __('entities.cards.plural'),
                  'data' => collect($cardStats['by_dice_cost'])->pluck('count')->toArray(),
                  'backgroundColor' => $factionInfo['color'],
                  'borderColor' => $factionInfo['color'],
                  'borderWidth' => 1,
                ]]
              ]"
            />
          </div>

          {{-- By Specific Cost --}}
          @if(count($cardStats['by_specific_cost']) > 0)
            <div class="dashboard-chart-container">
              <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.faction_details.cards_by_specific_cost') }}</h5>
              <x-dashboard.dashboard-chart
                id="faction-cards-specific-cost-chart"
                type="bar"
                height="200px"
                :data="[
                  'labels' => collect($cardStats['by_specific_cost'])->pluck('cost')->toArray(),
                  'datasets' => [[
                    'label' => __('entities.cards.plural'),
                    'data' => collect($cardStats['by_specific_cost'])->pluck('count')->toArray(),
                    'backgroundColor' => $factionInfo['color'],
                    'borderColor' => $factionInfo['color'],
                    'borderWidth' => 1,
                  ]]
                ]"
                {{-- :options="['indexAxis' => 'y']" --}}
              />
            </div>
          @endif
        </div>

        {{-- Type and Subtype Distribution --}}
        <div class="dashboard-distribution-grid">
          {{-- By Type --}}
          @if(count($cardStats['by_type']) > 0)
            <div class="dashboard-chart-container">
              <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.faction_details.cards_by_type') }}</h5>
              <x-dashboard.dashboard-chart
                id="faction-cards-type-chart"
                type="bar"
                height="200px"
                :data="[
                  'labels' => collect($cardStats['by_type'])->pluck('name')->toArray(),
                  'datasets' => [[
                    'label' => __('entities.cards.plural'),
                    'data' => collect($cardStats['by_type'])->pluck('count')->toArray(),
                    'backgroundColor' => $factionInfo['color'],
                    'borderColor' => $factionInfo['color'],
                    'borderWidth' => 1,
                  ]]
                ]"
                {{-- :options="['indexAxis' => 'y']" --}}
              />
            </div>
          @endif

          {{-- By Subtype --}}
          @if(count($cardStats['by_subtype']) > 0)
            <div class="dashboard-chart-container">
              <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.faction_details.cards_by_subtype') }}</h5>
              <x-dashboard.dashboard-chart
                id="faction-cards-subtype-chart"
                type="bar"
                height="200px"
                :data="[
                  'labels' => collect($cardStats['by_subtype'])->pluck('name')->toArray(),
                  'datasets' => [[
                    'label' => __('entities.cards.plural'),
                    'data' => collect($cardStats['by_subtype'])->pluck('count')->toArray(),
                    'backgroundColor' => $factionInfo['color'],
                    'borderColor' => $factionInfo['color'],
                    'borderWidth' => 1,
                  ]]
                ]"
                {{-- :options="['indexAxis' => 'y']" --}}
              />
            </div>
          @endif
        </div>

        {{-- Attack Type and Subtype Distribution --}}
        <div class="dashboard-distribution-grid">
          {{-- By Attack Type --}}
          @if(count($cardStats['by_attack_type']) > 0)
            <div class="dashboard-chart-container">
              <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.faction_details.cards_by_attack_type') }}</h5>
              <x-dashboard.dashboard-chart
                id="faction-cards-attack-type-chart"
                type="bar"
                height="200px"
                :data="[
                  'labels' => [
                    __('entities.cards.attack_types.physical'),
                    __('entities.cards.attack_types.magical')
                  ],
                  'datasets' => [[
                    'label' => __('entities.cards.plural'),
                    'data' => [
                      $cardStats['by_attack_type']['physical'] ?? 0,
                      $cardStats['by_attack_type']['magical'] ?? 0
                    ],
                    'backgroundColor' => [
                      '#f15959',
                      '#408cfd'
                    ],
                    'borderColor' => [
                      '#f15959',
                      '#408cfd'
                    ],
                    'borderWidth' => 1,
                  ]]
                ]"
                {{-- :options="['indexAxis' => 'y']" --}}
              />
            </div>
          @endif

          {{-- By Attack Subtype --}}
          @if(count($cardStats['by_attack_subtype']) > 0)
            <div class="dashboard-chart-container">
              <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.faction_details.cards_by_attack_subtype') }}</h5>
              <x-dashboard.dashboard-chart
                id="faction-cards-attack-subtype-chart"
                type="bar"
                height="200px"
                :data="[
                  'labels' => collect($cardStats['by_attack_subtype'])->pluck('name')->toArray(),
                  'datasets' => [[
                    'label' => __('entities.cards.plural'),
                    'data' => collect($cardStats['by_attack_subtype'])->pluck('count')->toArray(),
                    'backgroundColor' => $factionInfo['color'],
                    'borderColor' => $factionInfo['color'],
                    'borderWidth' => 1,
                  ]]
                ]"
                {{-- :options="['indexAxis' => 'y']" --}}
              />
            </div>
          @endif
        </div>

        {{-- Equipment Distribution --}}
        @if(count($cardStats['equipment_by_type']['weapons']) > 0 || count($cardStats['equipment_by_type']['armors']) > 0)
          <div class="dashboard-section">
            <h5 class="dashboard-section__subtitle">{{ __('admin.dashboard.modules.faction_details.equipment_distribution') }}</h5>
            
            <div class="dashboard-distribution-grid">
              {{-- Weapons --}}
              @if(count($cardStats['equipment_by_type']['weapons']) > 0)
                <div class="dashboard-chart-container">
                  <h5 class="dashboard-chart-container__title">{{ __('entities.equipment_types.categories.weapon') }}</h5>
                  <x-dashboard.dashboard-chart
                    id="faction-weapons-chart"
                    type="bar"
                    height="200px"
                    :data="[
                      'labels' => collect($cardStats['equipment_by_type']['weapons'])->pluck('name')->toArray(),
                      'datasets' => [[
                        'label' => __('entities.cards.plural'),
                        'data' => collect($cardStats['equipment_by_type']['weapons'])->pluck('count')->toArray(),
                        'backgroundColor' => '#f15959',
                        'borderColor' => '#f15959',
                        'borderWidth' => 1,
                      ]]
                    ]"
                    {{-- :options="['indexAxis' => 'y']" --}}
                  />
                </div>
              @endif

              {{-- Armors --}}
              @if(count($cardStats['equipment_by_type']['armors']) > 0)
                <div class="dashboard-chart-container">
                  <h5 class="dashboard-chart-container__title">{{ __('entities.equipment_types.categories.armor') }}</h5>
                  <x-dashboard.dashboard-chart
                    id="faction-armors-chart"
                    type="bar"
                    height="200px"
                    :data="[
                      'labels' => collect($cardStats['equipment_by_type']['armors'])->pluck('name')->toArray(),
                      'datasets' => [[
                        'label' => __('entities.cards.plural'),
                        'data' => collect($cardStats['equipment_by_type']['armors'])->pluck('count')->toArray(),
                        'backgroundColor' => '#408cfd',
                        'borderColor' => '#408cfd',
                        'borderWidth' => 1,
                      ]]
                    ]"
                    {{-- :options="['indexAxis' => 'y']" --}}
                  />
                </div>
              @endif
            </div>
          </div>
        @endif
      </div>
    @endif

  @else
    <div class="dashboard-section">
      <p class="dashboard-no-data">{{ __('admin.dashboard.modules.faction_details.no_faction_selected') }}</p>
    </div>
  @endif
</x-dashboard.dashboard-module>