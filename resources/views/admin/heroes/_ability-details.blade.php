@props([
  'ability',
  'isSelected' => false
])

<div class="ability-card" data-ability-card>
  <div class="ability-card__header">
    <span class="ability-card__name">{{ $ability->name }}</span>
    @if($ability->attackRange)
      <span class="ability-card__badge">{{ $ability->attackRange->name }}</span>
    @endif
  </div>

  @if($ability->cost)
    <div class="ability-card__cost">
      <x-cost-display :cost="$ability->cost" />
    </div>
  @endif

  @if($ability->description)
    <div class="ability-card__description">
      {!! \Illuminate\Support\Str::limit(strip_tags($ability->description), 100) !!}
    </div>
  @endif

  <div class="ability-card__meta">
    @if($ability->attack_type)
      <span class="ability-card__meta-item">
        {{ __('entities.hero_abilities.attack_types.' . $ability->attack_type) }}
      </span>
    @endif
    @if($ability->attackSubtype)
      <span class="ability-card__meta-item">
        {{ $ability->attackSubtype->name }}
      </span>
    @endif
    @if($ability->area)
      <span class="ability-card__meta-item">
        {{ __('entities.hero_abilities.area_effect') }}
      </span>
    @endif
  </div>

  @if($isSelected)
    <button 
      type="button" 
      class="ability-card__remove" 
      data-remove-ability
      aria-label="{{ __('entities.hero_abilities.remove_ability') }}"
    >
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
    
    <div class="ability-card__drag-handle" data-drag-handle>
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M6 3H6.01M10 3H10.01M6 8H6.01M10 8H10.01M6 13H6.01M10 13H10.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </div>
  @endif
</div>