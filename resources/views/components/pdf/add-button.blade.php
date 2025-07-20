@props([
  'entityType' => '',
  'entityId' => '',
  'class' => ''
])

<button 
  type="button"
  class="pdf-action-button {{ $class }}"
  data-collection-add
  data-entity-type="{{ $entityType }}"
  data-entity-id="{{ $entityId }}"
  title="{{ __('pdf.collection.add_button_title') }}"
>
  <x-icon name="pdf-add" />
</button>