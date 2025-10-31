@props([
  'heroes' => [],
  'selectedHeroes' => [],
  'name' => 'heroes',
  'requiredHeroes' => 0,
])

@php
  $selectedIds = collect($selectedHeroes)->pluck('id')->toArray();
  $initialTotalHeroes = count($selectedIds);
@endphp

<div class="deck-hero-selector" 
  data-component="deck-hero-selector"
  data-required-heroes="{{ $requiredHeroes }}"
  data-initial-total="{{ $initialTotalHeroes }}"
>
  {{-- Search Input --}}
  <div class="deck-hero-selector__search">
    <input 
      type="text" 
      class="deck-hero-selector__search-input"
      placeholder="{{ __('entities.heroes.search_placeholder') }}"
      data-search-input
    >
    <button 
      type="button" 
      class="deck-hero-selector__search-clear" 
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
  <div class="deck-hero-selector__columns">
    {{-- Available Heroes --}}
    <div class="deck-hero-selector__column">
      <h3 class="deck-hero-selector__title">
        {{ __('entities.heroes.available') }}
      </h3>
      <div class="deck-hero-selector__list" data-available-list>
        @forelse($heroes as $hero)
          <div 
            class="deck-hero-selector__item" 
            data-hero-item
            data-hero-id="{{ $hero->id }}"
            data-hero-name="{{ strtolower($hero->name) }}"
            data-hero-faction="{{ $hero->faction_id }}"
            style="{{ in_array($hero->id, $selectedIds) ? 'display: none;' : '' }}"
          >
            @include('admin.faction-decks._hero-item', ['hero' => $hero])
          </div>
        @empty
          <p class="deck-hero-selector__empty">
            {{ __('entities.heroes.no_heroes_available') }}
          </p>
        @endforelse
      </div>
    </div>

    {{-- Selected Heroes --}}
    <div class="deck-hero-selector__column">
      <h3 class="deck-hero-selector__title">
        {{ __('entities.heroes.selected') }}
        <span class="deck-hero-selector__count" data-selected-count>0</span>
        <span class="deck-hero-selector__total-heroes" data-total-heroes-display>
          <span data-total-heroes>{{ $initialTotalHeroes }}</span>/{{ $requiredHeroes }}
        </span>
      </h3>
      <div class="deck-hero-selector__list" data-selected-list>
        @forelse($selectedHeroes as $selected)
          @php
            $hero = $heroes->firstWhere('id', $selected['id']);
          @endphp
          @if($hero)
            <div 
              class="deck-hero-selector__item deck-hero-selector__item--selected" 
              data-selected-item
              data-hero-id="{{ $hero->id }}"
            >
              @include('admin.faction-decks._hero-item', ['hero' => $hero, 'isSelected' => true])
            </div>
          @endif
        @empty
          <p class="deck-hero-selector__empty" data-empty-message>
            {{ __('entities.heroes.no_heroes_selected') }}
          </p>
        @endforelse
      </div>
    </div>
  </div>

  {{-- Hidden inputs container --}}
  <div data-hidden-inputs style="display: none;"></div>
</div>