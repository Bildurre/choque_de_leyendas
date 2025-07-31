@php
  $cardStats = $stats['cards'];
@endphp

<x-dashboard.dashboard-module 
  :title="__('admin.dashboard.modules.cards.title')" 
  icon="cards"
  class="dashboard-module--cards"
>
  {{-- Summary Stats --}}
  <div class="dashboard-stats-grid">
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.cards.total')"
      :value="$cardStats['summary']['total_cards']"
      icon="cards"
      size="large"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.cards.published')"
      :value="$cardStats['summary']['published_cards']"
      icon="check-circle"
      color="var(--color-success)"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.cards.with_effects')"
      :value="$cardStats['summary']['cards_with_effects']"
      icon="sliders"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.cards.area_attacks')"
      :value="$cardStats['summary']['area_attacks']"
      icon="globe"
    />
  </div>

  {{-- Main Distribution Charts --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.cards.distributions') }}</h4>
    
    <div class="dashboard-distribution-grid">
      {{-- Type Distribution --}}
      <div class="dashboard-chart-container">
        <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.cards.by_type') }}</h5>
        <x-dashboard.dashboard-chart
          id="cards-type-chart"
          type="bar"
          height="200px"
          :data="[
            'labels' => collect($cardStats['by_type'])->pluck('name')->toArray(),
            'datasets' => [[
              'label' => __('entities.cards.plural'),
              'data' => collect($cardStats['by_type'])->pluck('count')->toArray(),
              'backgroundColor' => '#7a64c8',
              'borderColor' => '#7a64c8',
              'borderWidth' => 1,
            ]]
          ]"
        />
      </div>

      {{-- Cost Distribution --}}
      <div class="dashboard-chart-container">
        <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.cards.cost_distribution') }}</h5>
        <x-dashboard.dashboard-chart
          id="cards-cost-chart"
          type="bar"
          height="200px"
          :data="[
            'labels' => collect($cardStats['by_cost']['distribution'])->pluck('cost')->toArray(),
            'datasets' => [[
              'label' => __('entities.cards.plural'),
              'data' => collect($cardStats['by_cost']['distribution'])->pluck('count')->toArray(),
              'backgroundColor' => '#f1753a',
              'borderColor' => '#f1753a',
              'borderWidth' => 1,
            ]]
          ]"
        />
        <div class="dashboard-chart-container__footer">
          {{ __('admin.dashboard.modules.cards.avg_cost') }}: {{ $cardStats['by_cost']['avg_cost'] }}
        </div>
      </div>

      {{-- Color Distribution --}}
      <div class="dashboard-chart-container">
        <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.cards.color_distribution') }}</h5>
        <x-dashboard.dashboard-chart
          id="cards-color-chart"
          type="bar"
          height="200px"
          :data="[
            'labels' => [
              __('admin.dashboard.modules.cards.red'),
              __('admin.dashboard.modules.cards.green'),
              __('admin.dashboard.modules.cards.blue')
            ],
            'datasets' => [[
              'label' => __('entities.cards.plural'),
              'data' => [
                $cardStats['by_cost']['colors']['R'],
                $cardStats['by_cost']['colors']['G'],
                $cardStats['by_cost']['colors']['B']
              ],
              'backgroundColor' => [
                '#f15959',
                '#29ab5f',
                '#408cfd'
              ],
              'borderColor' => [
                '#f15959',
                '#29ab5f',
                '#408cfd'
              ],
              'borderWidth' => 1,
            ]]
          ]"
        />
      </div>
    </div>
  </div>

  {{-- Equipment Distribution --}}
  @if($cardStats['equipment_distribution']['total'] > 0)
    <div class="dashboard-section">
      <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.cards.equipment_distribution') }}</h4>
      
      <div class="dashboard-distribution-grid">
        {{-- General Equipment Distribution --}}
        <div class="dashboard-chart-container">
          <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.cards.equipment_distribution') }}</h5>
          <x-dashboard.dashboard-chart
            id="equipment-general-chart"
            type="bar"
            height="200px"
            :data="[
              'labels' => [
                __('entities.equipment_types.categories.weapon'),
                __('entities.equipment_types.categories.armor')
              ],
              'datasets' => [[
                'data' => [
                  $cardStats['equipment_distribution']['general_distribution']['weapon'],
                  $cardStats['equipment_distribution']['general_distribution']['armor']
                ],
                'backgroundColor' => [
                  '#f15959',
                  '#408cfd'
                ],
                'borderColor' => [
                  '#f15959',
                  '#408cfd'
                ],
                'borderWidth' => 1
              ]]
            ]"
            :options="['indexAxis' => 'y']"
          />
        </div>

        {{-- Weapon Types Distribution --}}
        @if(count($cardStats['equipment_distribution']['weapon_types']) > 0)
          <div class="dashboard-chart-container">
            <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.cards.weapon_distribution') }}</h5>
            <x-dashboard.dashboard-chart
              id="weapon-types-chart"
              type="bar"
              height="200px"
              :data="[
                'labels' => array_keys($cardStats['equipment_distribution']['weapon_types']),
                'datasets' => [[
                  'data' => array_values($cardStats['equipment_distribution']['weapon_types']),
                  'backgroundColor' => '#f15959',
                  'borderColor' => '#f15959',
                  'borderWidth' => 1,
                ]]
              ]"
              :options="['indexAxis' => 'y']"
            />
          </div>
        @endif

        {{-- Armor Types Distribution --}}
        @if(count($cardStats['equipment_distribution']['armor_types']) > 0)
          <div class="dashboard-chart-container">
            <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.cards.armor_distribution') }}</h5>
            <x-dashboard.dashboard-chart
              id="armor-types-chart"
              type="bar"
              height="200px"
              :data="[
                'labels' => array_keys($cardStats['equipment_distribution']['armor_types']),
                'datasets' => [[
                  'data' => array_values($cardStats['equipment_distribution']['armor_types']),
                  'backgroundColor' => '#408cfd',
                  'borderColor' => '#408cfd',
                  'borderWidth' => 1,
                ]]
              ]"
              :options="['indexAxis' => 'y']"
            />
          </div>
        @endif
      </div>
    </div>
  @endif

  {{-- Cards by Faction --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.cards.by_faction') }}</h4>
    <div class="dashboard-faction-cards-grid">
      @foreach($cardStats['by_faction'] as $faction)
        <x-dashboard.dashboard-info-line
          :label="$faction['name']"
          :value="$faction['count'] . ' (' . __('admin.dashboard.modules.cards.avg_cost_short') . ': ' . $faction['avg_cost'] . ')'"
          :showBar="true"
          :percentage="$cardStats['summary']['total_cards'] > 0 ? ($faction['count'] / $cardStats['summary']['total_cards']) * 100 : 0"
          :color="$faction['color']"
        />
      @endforeach
    </div>
  </div>
</x-dashboard.dashboard-module>