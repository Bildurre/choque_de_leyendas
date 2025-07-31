@php
  $deckStats = $stats['decks'];
@endphp

<x-dashboard.dashboard-module 
  :title="__('admin.dashboard.modules.decks.title')" 
  icon="decks"
  class="dashboard-module--decks"
>
  {{-- Summary Stats --}}
  <div class="dashboard-stats-grid">
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.decks.total')"
      :value="$deckStats['summary']['total_decks']"
      icon="decks"
      size="large"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.decks.published')"
      :value="$deckStats['summary']['published_decks']"
      icon="check-circle"
      color="var(--color-success)"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.decks.avg_cards')"
      :value="$deckStats['summary']['avg_cards_per_deck']"
      icon="cards"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.decks.game_modes')"
      :value="$deckStats['summary']['game_modes_count']"
      icon="sliders"
    />
  </div>

  {{-- Main Distributions --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.decks.distributions') }}</h4>
    
    <div class="dashboard-distribution-grid">
      {{-- By Game Mode --}}
      <div class="dashboard-chart-container">
        <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.decks.by_game_mode') }}</h5>
        <x-dashboard.dashboard-chart
          id="decks-game-mode-chart"
          type="bar"
          height="200px"
          :data="[
            'labels' => collect($deckStats['by_game_mode'])->pluck('name')->toArray(),
            'datasets' => [[
              'label' => __('entities.faction_decks.plural'),
              'data' => collect($deckStats['by_game_mode'])->pluck('count')->toArray(),
              'backgroundColor' => '#88b033',
              'borderColor' => '#88b033',
              'borderWidth' => 1,
            ]]
          ]"
          :options="['indexAxis' => 'y']"
        />
      </div>

      {{-- Size Distribution --}}
      <div class="dashboard-chart-container">
        <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.decks.size_distribution') }}</h5>
        <x-dashboard.dashboard-chart
          id="decks-size-chart"
          type="bar"
          height="200px"
          :data="[
            'labels' => collect($deckStats['size_distribution'])->pluck('range')->toArray(),
            'datasets' => [[
              'label' => __('entities.faction_decks.plural'),
              'data' => collect($deckStats['size_distribution'])->pluck('count')->toArray(),
              'backgroundColor' => '#f1753a',
              'borderColor' => '#f1753a',
              'borderWidth' => 1,
            ]]
          ]"
        />
      </div>

      {{-- Top Card Types in Decks --}}
      <div class="dashboard-chart-container">
        <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.decks.popular_card_types') }}</h5>
        @if(count($deckStats['composition']['top_card_types']) > 0)
          <x-dashboard.dashboard-chart
            id="decks-card-types-chart"
            type="bar"
            height="200px"
            :data="[
              'labels' => array_map(function($type) {
                return str_replace('equipment_', '', $type);
              }, array_keys($deckStats['composition']['top_card_types'])),
              'datasets' => [[
                'label' => __('entities.cards.plural'),
                'data' => array_values($deckStats['composition']['top_card_types']),
                'backgroundColor' => '#3999cd',
                'borderColor' => '#3999cd',
                'borderWidth' => 1,
              ]]
            ]"
            :options="['indexAxis' => 'y']"
          />
        @else
          <p class="dashboard-no-data">{{ __('admin.no_records') }}</p>
        @endif
      </div>
    </div>
  </div>

  {{-- Decks by Faction --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.decks.by_faction') }}</h4>
    <div class="dashboard-faction-cards-grid">
      @foreach($deckStats['by_faction'] as $faction)
        <x-dashboard.dashboard-info-line
          :label="$faction['name']"
          :value="$faction['count'] . ' (' . $faction['published'] . ' ' . __('admin.published') . ')'"
          :showBar="true"
          :percentage="$deckStats['summary']['total_decks'] > 0 ? ($faction['count'] / $deckStats['summary']['total_decks']) * 100 : 0"
          :color="$faction['color']"
        />
      @endforeach
    </div>
  </div>

  {{-- Deck Composition --}}
  @if(count($deckStats['composition']['top_hero_classes']) > 0)
    <div class="dashboard-section">
      <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.decks.composition') }}</h4>
      
      <div class="dashboard-chart-container">
        <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.decks.popular_hero_classes') }}</h5>
        <x-dashboard.dashboard-chart
          id="decks-hero-classes-chart"
          type="bar"
          height="200px"
          :data="[
            'labels' => array_keys($deckStats['composition']['top_hero_classes']),
            'datasets' => [[
              'label' => __('entities.heroes.plural'),
              'data' => array_values($deckStats['composition']['top_hero_classes']),
              'backgroundColor' => '#a75da5',
              'borderColor' => '#a75da5',
              'borderWidth' => 1,
            ]]
          ]"
          :options="['indexAxis' => 'y']"
        />
      </div>
    </div>
  @endif

  {{-- Game Mode Details --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.decks.mode_details') }}</h4>
    <div class="dashboard-mode-list">
      @foreach($deckStats['by_game_mode'] as $mode)
        <div class="dashboard-mode-item">
          <div class="dashboard-mode-item__info">
            <span class="dashboard-mode-item__name">{{ $mode['name'] }}</span>
            <span class="dashboard-mode-item__stats">
              {{ $mode['count'] }} {{ __('entities.faction_decks.plural') }} 
              ({{ $mode['published'] }} {{ __('admin.published') }})
            </span>
          </div>
          <div class="dashboard-mode-item__percentage">{{ $mode['percentage'] }}%</div>
        </div>
      @endforeach
    </div>
  </div>
</x-dashboard.dashboard-module>