@props([
  'id',
  'title',
  'collapsed' => true,  // Cambiado a true por defecto
  'inAccordion' => false
])

<div class="collapsible-section is-collapsed collapsible-section--no-animation" 
     id="{{ $id }}" 
     data-in-accordion="{{ $inAccordion ? 'true' : 'false' }}">
  <div class="collapsible-section__header">
    <h2 class="collapsible-section__title">{{ $title }}</h2>
    
    <button type="button" class="collapsible-section__toggle" aria-label="{{ __('Toggle section') }}">
      <x-icon name="chevron-down" class="collapsible-section__icon" />
    </button>
  </div>
  
  <div class="collapsible-section__content">
    {{ $slot }}
  </div>
</div>