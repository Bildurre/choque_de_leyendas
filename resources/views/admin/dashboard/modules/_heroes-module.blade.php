@php
  $heroStats = $stats['heroes'];
@endphp

<x-dashboard.dashboard-module 
  :title="__('admin.dashboard.modules.heroes.title')" 
  icon="heroes"
  class="dashboard-module--heroes"
>
  {{-- Summary Stats --}}
  <div class="dashboard-stats-grid">
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.heroes.total')"
      :value="$heroStats['summary']['total_heroes']"
      icon="heroes"
      size="large"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.heroes.published')"
      :value="$heroStats['summary']['published_heroes']"
      icon="check-circle"
      color="var(--color-success)"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.heroes.with_passives')"
      :value="$heroStats['summary']['heroes_with_passives']"
      icon="sliders"
    />
    <x-dashboard.dashboard-stat-item
      :label="__('admin.dashboard.modules.heroes.unique_classes')"
      :value="$heroStats['summary']['unique_classes']"
      icon="layers"
    />
  </div>

  {{-- Distribution by Superclass and Gender --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.heroes.distributions') }}</h4>
    
    <div class="dashboard-distribution-grid">
      {{-- Superclass Distribution --}}
      <div class="dashboard-chart-container">
        <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.heroes.by_superclass') }}</h5>
        <x-dashboard.dashboard-chart
          id="heroes-superclass-chart"
          type="bar"
          height="200px"
          :data="[
            'labels' => collect($heroStats['by_class']['by_superclass'])->pluck('name')->toArray(),
            'datasets' => [[
              'label' => __('entities.heroes.plural'),
              'data' => collect($heroStats['by_class']['by_superclass'])->pluck('count')->toArray(),
              'backgroundColor' => '#7a64c8',
              'borderColor' => '#7a64c8',
              'borderWidth' => 1,
            ]]
          ]"
        />
      </div>

      {{-- Race Distribution --}}
      <div class="dashboard-chart-container">
        <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.heroes.by_race') }}</h5>
        @if(count($heroStats['by_race']) > 0)
          <x-dashboard.dashboard-chart
            id="heroes-race-chart"
            type="bar"
            height="200px"
            :data="[
              'labels' => collect($heroStats['by_race'])->take(8)->pluck('name')->toArray(),
              'datasets' => [[
                'label' => __('entities.heroes.plural'),
                'data' => collect($heroStats['by_race'])->take(8)->pluck('count')->toArray(),
                'backgroundColor' => '#31a28e',
                'borderColor' => '#31a28e',
                'borderWidth' => 1,
              ]]
            ]"
            :options="['indexAxis' => 'y']"
          />
        @else
          <p class="dashboard-no-data">{{ __('admin.no_records') }}</p>
        @endif
      </div>

      {{-- Gender Distribution --}}
      <div class="dashboard-chart-container">
        <h5 class="dashboard-chart-container__title">{{ __('admin.dashboard.modules.heroes.gender_distribution') }}</h5>
        <x-dashboard.dashboard-chart
          id="heroes-gender-chart"
          type="bar"
          height="200px"
          :data="[
            'labels' => [
              __('entities.heroes.genders.male'),
              __('entities.heroes.genders.female')
            ],
            'datasets' => [[
              'label' => __('entities.heroes.plural'),
              'data' => [
                $heroStats['gender_distribution']['male'],
                $heroStats['gender_distribution']['female']
              ],
              'backgroundColor' => [
                '#408cfd',
                '#f15959'
              ],
              'borderColor' => [
                '#408cfd',
                '#f15959'
              ],
              'borderWidth' => 1,
            ]]
          ]"
        />
      </div>
    </div>
  </div>

  {{-- Attribute Statistics --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.heroes.attribute_stats') }}</h4>
    
    <div class="dashboard-distribution-grid">
      @foreach(['agility', 'mental', 'will', 'strength', 'armor'] as $attribute)
        <div class="dashboard-chart-container">
          <h5 class="dashboard-chart-container__title">{{ __('entities.heroes.attributes.' . $attribute) }}</h5>
          <x-dashboard.dashboard-chart
            :id="'attribute-' . $attribute . '-chart'"
            type="bar"
            height="150px"
            :data="[
              'labels' => ['1', '2', '3', '4', '5', '6', '7', '8'],
              'datasets' => [[
                'label' => __('entities.heroes.plural'),
                'data' => array_values($heroStats['attributes'][$attribute]['distribution']),
                'backgroundColor' => '#7a64c8',
                'borderColor' => '#7a64c8',
                'borderWidth' => 1,
              ]]
            ]"
          />
          <div class="dashboard-chart-container__footer">
            {{ __('admin.dashboard.modules.heroes.avg') }}: {{ $heroStats['attributes'][$attribute]['avg'] }} | 
            {{ __('admin.dashboard.modules.heroes.range') }}: {{ $heroStats['attributes'][$attribute]['min'] }}-{{ $heroStats['attributes'][$attribute]['max'] }}
          </div>
        </div>
      @endforeach
      
      {{-- Health Stats --}}
      <div class="dashboard-chart-container">
        <h5 class="dashboard-chart-container__title">{{ __('entities.heroes.attributes.health') }}</h5>
        <div class="dashboard-health-stats">
          <div class="dashboard-health-stats__item">
            <span class="dashboard-health-stats__label">{{ __('admin.dashboard.modules.heroes.avg') }}:</span>
            <span class="dashboard-health-stats__value">{{ $heroStats['attributes']['health']['avg'] }}</span>
          </div>
          <div class="dashboard-health-stats__item">
            <span class="dashboard-health-stats__label">{{ __('admin.dashboard.modules.heroes.range') }}:</span>
            <span class="dashboard-health-stats__value">{{ $heroStats['attributes']['health']['min'] }}-{{ $heroStats['attributes']['health']['max'] }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Heroes by Faction --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.heroes.by_faction') }}</h4>
    <div class="dashboard-faction-cards-grid">
      @foreach($heroStats['by_faction'] as $faction)
        <x-dashboard.dashboard-info-line
          :label="$faction['name']"
          :value="$faction['count'] . ' (' . $faction['published'] . ' ' . __('admin.published') . ')'"
          :showBar="true"
          :percentage="$heroStats['summary']['total_heroes'] > 0 ? ($faction['count'] / $heroStats['summary']['total_heroes']) * 100 : 0"
          :color="$faction['color']"
        />
      @endforeach
    </div>
  </div>

  {{-- Top Classes --}}
  <div class="dashboard-section">
    <h4 class="dashboard-section__title">{{ __('admin.dashboard.modules.heroes.top_classes') }}</h4>
    <div class="dashboard-chart-container">
      <x-dashboard.dashboard-chart
        id="heroes-top-classes-chart"
        type="bar"
        height="250px"
        :data="[
          'labels' => collect($heroStats['by_class']['top_classes'])->pluck('name')->toArray(),
          'datasets' => [[
            'label' => __('entities.heroes.plural'),
            'data' => collect($heroStats['by_class']['top_classes'])->pluck('count')->toArray(),
            'backgroundColor' => '#a75da5',
            'borderColor' => '#a75da5',
            'borderWidth' => 1,
          ]]
        ]"
        :options="['indexAxis' => 'y']"
      />
    </div>
  </div>
</x-dashboard.dashboard-module>