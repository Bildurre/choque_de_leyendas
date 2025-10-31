@props([
  'hero',
  'isSelected' => false
])

<div class="hero-item" data-hero-item-inner>
  <div class="hero-item__header">
    <span class="hero-item__name">{{ $hero->name }}</span>
    @if($hero->faction)
      <span class="hero-item__badge hero-item__badge--faction">
        {{ $hero->faction->name }}
      </span>
    @endif
  </div>

  <div class="hero-item__meta">
    @if($hero->heroClass)
      <span class="hero-item__meta-item">
        {{ $hero->heroClass->name }}
      </span>
    @endif
  </div>

  @if($isSelected)
    {{-- Remove button --}}
    <button 
      type="button" 
      class="hero-item__remove" 
      data-remove-hero
      aria-label="{{ __('entities.heroes.remove_hero') }}"
    >
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
  @endif
</div>