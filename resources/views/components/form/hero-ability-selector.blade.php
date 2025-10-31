@props([
  'abilities' => [],
  'selectedAbilities' => [],
  'name' => 'hero_abilities',
])

@php
  $selectedIds = collect($selectedAbilities)->pluck('id')->toArray();
  $selectedAbilitiesData = collect($selectedAbilities)->keyBy('id');
@endphp

<div class="hero-ability-selector" data-component="hero-ability-selector">
  {{-- Search Input --}}
  <div class="hero-ability-selector__search">
    <input 
      type="text" 
      class="hero-ability-selector__search-input"
      placeholder="{{ __('entities.hero_abilities.search_placeholder') }}"
      data-search-input
    >
    <button 
      type="button" 
      class="hero-ability-selector__search-clear" 
      data-search-clear
      style="display: none;"
      aria-label="{{ __('common.clear_search') }}"
    >
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
  </div>

  {{-- Two Column Layout --}}
  <div class="hero-ability-selector__columns">
    {{-- Available Abilities --}}
    <div class="hero-ability-selector__column">
      <h3 class="hero-ability-selector__title">
        {{ __('entities.hero_abilities.available') }}
      </h3>
      <div class="hero-ability-selector__list" data-available-list>
        @forelse($abilities as $ability)
          <div 
            class="hero-ability-selector__item" 
            data-ability-item
            data-ability-id="{{ $ability->id }}"
            data-ability-name="{{ strtolower($ability->name) }}"
            style="{{ in_array($ability->id, $selectedIds) ? 'display: none;' : '' }}"
          >
            @include('admin.heroes._ability-details', ['ability' => $ability])
          </div>
        @empty
          <p class="hero-ability-selector__empty">
            {{ __('entities.hero_abilities.no_abilities_available') }}
          </p>
        @endforelse
      </div>
    </div>

    {{-- Selected Abilities --}}
    <div class="hero-ability-selector__column">
      <h3 class="hero-ability-selector__title">
        {{ __('entities.hero_abilities.selected') }}
        <span class="hero-ability-selector__count" data-selected-count>0</span>
      </h3>
      <div 
        class="hero-ability-selector__list hero-ability-selector__list--sortable" 
        data-selected-list
      >
        @forelse($selectedAbilities as $selected)
          @php
            $ability = $abilities->firstWhere('id', $selected['id']);
          @endphp
          @if($ability)
            <div 
              class="hero-ability-selector__item hero-ability-selector__item--selected" 
              data-selected-item
              data-ability-id="{{ $ability->id }}"
            >
              @include('admin.heroes._ability-details', ['ability' => $ability, 'isSelected' => true])
            </div>
          @endif
        @empty
          <p class="hero-ability-selector__empty" data-empty-message>
            {{ __('entities.hero_abilities.no_abilities_selected') }}
          </p>
        @endforelse
      </div>
    </div>
  </div>

  {{-- Hidden inputs container --}}
  <div data-hidden-inputs style="display: none;"></div>
</div>