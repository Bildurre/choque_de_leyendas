@php
  $factionStats = $stats['factions'];
@endphp

<x-dashboard.dashboard-module 
  :title="__('admin.dashboard.modules.factions.title')" 
  icon="layers"
  class="dashboard-module--factions"
>
  {{-- Summary Stats --}}
  <div class="dashboard-stats-grid">
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.factions.total')"
      :value="$factionStats['summary']['total_factions']"
      icon="layers"
      size="large"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.factions.published')"
      :value="$factionStats['summary']['published_factions']"
      icon="check-circle"
      color="var(--color-success)"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.factions.avg_heroes')"
      :value="$factionStats['summary']['avg_heroes_per_faction']"
      icon="heroes"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.factions.avg_cards')"
      :value="$factionStats['summary']['avg_cards_per_faction']"
      icon="cards"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.factions.avg_decks')"
      :value="$factionStats['summary']['avg_decks_per_faction']"
      icon="decks"
    />
  </div>

  {{-- Faction Comparison --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.factions.comparison') }}</h4>
    <div class="dashboard-factions-grid">
      @foreach($factionStats['faction_comparison'] as $index => $faction)
        <div class="dashboard-faction-card">
          <div class="dashboard-faction-card__header">
            <span class="dashboard-faction-card__name">{{ $faction['name'] }}</span>
            <span class="dashboard-faction-card__color" style="background-color: {{ $faction['color'] }}"></span>
          </div>
          <div class="dashboard-faction-card__chart">
            <x-dashboard.dashboard-chart
              :id="'faction-chart-' . $index"
              type="bar"
              height="180px"
              :data="[
                'labels' => [
                  __('entities.heroes.plural'),
                  __('entities.cards.plural'),
                  __('entities.faction_decks.plural')
                ],
                'datasets' => [[
                  'data' => [
                    $faction['heroes_count'],
                    $faction['cards_count'],
                    $faction['decks_count']
                  ],
                  'backgroundColor' => $faction['color'],
                  'borderColor' => $faction['color'],
                  'borderWidth' => 1,
                ]]
              ]"
            />
          </div>
          @if(!$faction['is_published'])
            <div class="dashboard-faction-card__badge">{{ __('admin.draft') }}</div>
          @endif
        </div>
      @endforeach
    </div>
  </div>

  {{-- Top Factions --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.factions.rankings') }}</h4>
    
    <div class="dashboard-rankings-grid">
      {{-- Most Heroes --}}
      <div class="dashboard-ranking">
        <h5 class="dashboard-ranking__title">{{ __('admin.dashboard.modules.factions.most_heroes') }}</h5>
        @foreach($factionStats['top_factions']['most_heroes'] as $faction)
          <x-dashboard.dashboard-info-line
            :label="$faction['name']"
            :value="$faction['count']"
            :showBar="true"
            :percentage="$faction['count'] > 0 ? ($faction['count'] / $factionStats['top_factions']['most_heroes'][0]['count']) * 100 : 0"
            :color="$faction['color']"
          />
        @endforeach
      </div>

      {{-- Most Cards --}}
      <div class="dashboard-ranking">
        <h5 class="dashboard-ranking__title">{{ __('admin.dashboard.modules.factions.most_cards') }}</h5>
        @foreach($factionStats['top_factions']['most_cards'] as $faction)
          <x-dashboard.dashboard-info-line
            :label="$faction['name']"
            :value="$faction['count']"
            :showBar="true"
            :percentage="$faction['count'] > 0 ? ($faction['count'] / $factionStats['top_factions']['most_cards'][0]['count']) * 100 : 0"
            :color="$faction['color']"
          />
        @endforeach
      </div>

      {{-- Most Decks --}}
      <div class="dashboard-ranking">
        <h5 class="dashboard-ranking__title">{{ __('admin.dashboard.modules.factions.most_decks') }}</h5>
        @foreach($factionStats['top_factions']['most_decks'] as $faction)
          <x-dashboard.dashboard-info-line
            :label="$faction['name']"
            :value="$faction['count']"
            :showBar="true"
            :percentage="$faction['count'] > 0 ? ($faction['count'] / $factionStats['top_factions']['most_decks'][0]['count']) * 100 : 0"
            :color="$faction['color']"
          />
        @endforeach
      </div>
    </div>
  </div>

  {{-- Distribution Stats --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.factions.distribution') }}</h4>
    
    <div class="dashboard-distribution-grid">
      <x-dashboard.dashboard-chart
        id="factions-status-chart"
        type="bar"
        height="200px"
        :data="[
          'labels' => [
            __('admin.published'),
            __('admin.draft')
          ],
          'datasets' => [[
            'data' => [
              $factionStats['distribution']['by_status']['published'],
              $factionStats['distribution']['by_status']['draft']
            ],
            'backgroundColor' => [
              '#29ab5f',
              '#808080'
            ],
            'borderColor' => [
              '#29ab5f',
              '#808080'
            ],
            'borderWidth' => 1
          ]]
        ]"
        :options="['indexAxis' => 'y']"
      />
      
      <x-dashboard.dashboard-chart
        id="factions-content-chart"
        type="bar"
        height="200px"
        :data="[
          'labels' => [
            __('admin.dashboard.modules.factions.with_heroes'),
            __('admin.dashboard.modules.factions.without_heroes')
          ],
          'datasets' => [[
            'data' => [
              $factionStats['distribution']['by_content']['with_heroes'],
              $factionStats['distribution']['by_content']['without_heroes']
            ],
            'backgroundColor' => [
              '#408cfd',
              '#808080'
            ],
            'borderColor' => [
              '#408cfd',
              '#808080'
            ],
            'borderWidth' => 1
          ]]
        ]"
        :options="['indexAxis' => 'y']"
      />
    </div>
  </div>
</x-dashboard.dashboard-module>