@props([
  'id',
  'title',
  'collapsed' => true,
  'inAccordion' => false,
  'forceCollapse' => false
])

<div class="collapsible-section{{ ($collapsed || $forceCollapse) ? ' is-collapsed' : '' }}" 
  id="{{ $id }}" 
  data-in-accordion="{{ $inAccordion ? 'true' : 'false' }}"
  data-force-collapse="{{ $forceCollapse ? 'true' : 'false' }}">
  <div class="collapsible-section__header">
    <h2 class="collapsible-section__title">{{ $title }}</h2>
    
    <div class="collapsible-section__icon-container">
      <x-icon name="chevron-down" class="collapsible-section__icon" />
    </div>
  </div>
  
  <div class="collapsible-section__content-wrapper">
    <div class="collapsible-section__content">
      {{ $slot }}
    </div>
  </div>
</div>