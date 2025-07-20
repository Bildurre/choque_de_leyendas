@props([
  'entity',
  'entityType' => '',
  'class' => ''
])

@php
  $pdf = $entity->getAvailablePdf();
@endphp

@if($pdf)
  <button 
    type="button"
    class="pdf-action-button {{ $class }}"
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
    class="pdf-action-button pdf-action-button--disabled {{ $class }}"
    title="{{ __('pdf.download.not_available') }}"
    disabled
  >
    {{ $slot }}
    <x-icon name="pdf-download" />
  </button>
@endif