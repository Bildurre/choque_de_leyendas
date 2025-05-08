@props([
  'id',
  'title',
  'collapsed' => false,
  'inAccordion' => false
])

<div class="collapsible-section {{ $collapsed ? 'is-collapsed' : '' }}" 
     id="{{ $id }}" 
     data-in-accordion="{{ $inAccordion ? 'true' : 'false' }}">
  <div class="collapsible-section__header">
    <h2 class="collapsible-section__title">{{ $title }}</h2>
    
    <button type="button" class="collapsible-section__toggle" data-target="{{ $id }}">
      <x-icon name="{{ $collapsed ? 'chevron-down' : 'chevron-up' }}" 
              class="collapsible-section__icon" />
    </button>
  </div>
  
  <div class="collapsible-section__content">
    {{ $slot }}
  </div>
</div>