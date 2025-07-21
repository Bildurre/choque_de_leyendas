@props([
  'entity',
  'entityType' => '',
  'class' => '',
  'type' => null
])

@php
  $pdf = $entity->getAvailablePdf();
@endphp

@if($pdf)
  <button 
    type="button"
    class="pdf-action-button {{ $type ? 'pdf-action-button--'.$type : '' }} {{ $class }}"
    data-download-pdf
    data-pdf-id="{{ $pdf->id }}"
    data-entity-name="{{ $entity->name }}"
    data-entity-type="{{ $entityType }}"
    title="{{ __('pdf.download.button_title') }}"
  >
    {{ $slot }}
    <x-icon name="pdf-download" />
  </button>
@else
  <button 
    type="button"
    class="pdf-action-button pdf-action-button--disabled {{ $type ? 'pdf-action-button--'.$type : '' }} {{ $class }}"
    title="{{ __('pdf.download.not_available') }}"
    disabled
  >
    {{ $slot }}
    <x-icon name="pdf-download" />
  </button>
@endif