@props([
  'entityType' => '',
  'entityId' => '',
  'class' => ''
])

<button 
  type="button"
  class="add-button {{ $class }}"
  data-collection-add
  data-entity-type="{{ $entityType }}"
  data-entity-id="{{ $entityId }}"
  title="{{ __('components.add_button.title') }}"
>
  <x-icon name="pdf-add" />
</button>