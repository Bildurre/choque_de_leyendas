@props([
  'title',
  'type' => 'faction', // faction, deck, other
  'entityId' => null,
  'existingPdf' => null,
  'generateRoute' => null,
  'deleteRoute' => null,
])

@php
  $hasExistingPdf = $existingPdf !== null;
  $pdfExists = $hasExistingPdf && $existingPdf->exists();
@endphp

<div class="pdf-item">
  <div class="pdf-item__header">
    <div class="pdf-item__status">
      @if($hasExistingPdf)
        <x-badge variant="success">
          <x-icon name="check-circle" size="xs" />
        </x-badge>
      @else
        <x-badge variant="danger">
          <x-icon name="x-circle" size="xs" />
        </x-badge>
      @endif
    </div>

    <h3 class="pdf-item__title">{{ $title }}</h3>
    
    @if($hasExistingPdf && $pdfExists)
      <div class="pdf-item__info">
        <span class="pdf-item__size">{{ $existingPdf->formatted_size }}</span>
        <span class="pdf-item__separator">•</span>
        <span class="pdf-item__date">{{ $existingPdf->created_at->format('d/m/Y H:i') }}</span>
      </div>
    @elseif($hasExistingPdf && !$pdfExists)
      <div class="pdf-item__info">
        <span class="pdf-item__processing">{{ __('admin.pdf_processing') }}</span>
      </div>
    @endif
  </div>
  
  <div class="pdf-item__actions">
    @if($generateRoute)
      <x-action-button
        :route="$generateRoute"
        method="POST"
        variant="publish"
        size="sm"
        icon="{{ $hasExistingPdf ? 'refresh' : 'pdf-add' }}"
        :confirmMessage="$hasExistingPdf ? __('admin.confirm_regenerate_pdf') : null"
      >
      </x-action-button>
    @endif
    
    @if($hasExistingPdf && $pdfExists)
      <x-action-button
        :href="route('admin.pdf-export.view', $existingPdf)"
        target="_blank"
        variant="view"
        size="sm"
        icon="eye"
      >
      </x-action-button>
    @endif
    
    @if($hasExistingPdf && $deleteRoute)
      <x-action-button
        :route="$deleteRoute"
        method="DELETE"
        variant="delete"
        size="sm"
        icon="trash"
        :confirmMessage="__('admin.confirm_delete_pdf')"
      >
      </x-action-button>
    @endif
  </div>
</div>