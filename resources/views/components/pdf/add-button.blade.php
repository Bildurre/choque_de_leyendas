@props([
  'entityType' => '',
  'entityId' => '',
  'class' => '',
  'type' => null
])

<button 
  type="button"
  class="pdf-action-button {{ $type ? 'pdf-action-button--'.$type : '' }} {{ $class }}"
  data-collection-add
  data-entity-type="{{ $entityType }}"
  data-entity-id="{{ $entityId }}"
  title="{{ __('pdf.collection.add_button_title') }}"
>
  <x-icon name="pdf-add" />
  {{ $slot }}
</button>