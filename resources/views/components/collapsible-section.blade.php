@props([
  'id',
  'title',
  'collapsed' => true,
  'inAccordion' => false
])

<div class="collapsible-section is-collapsed collapsible-section--no-animation" 
     id="{{ $id }}" 
     data-in-accordion="{{ $inAccordion ? 'true' : 'false' }}">
  <div class="collapsible-section__header">
    <h2 class="collapsible-section__title">{{ $title }}</h2>
    
    <div class="collapsible-section__icon-container">
      <x-icon name="chevron-down" class="collapsible-section__icon" />
    </div>
  </div>
  
  <div class="collapsible-section__content">
    {{ $slot }}
  </div>
</div>