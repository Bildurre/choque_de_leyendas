@props([
  'pdf',
  'showDelete' => false,
])

@php
  $pdfExists = $pdf->exists();
  $displayName = $pdf->display_name ?? $pdf->filename;
@endphp

<div class="pdf-item">
  <div class="pdf-item__header">
    <div class="pdf-item__status">
      @if($pdfExists)
        <x-badge variant="success">
          <x-icon name="check-circle" size="xs" />
        </x-badge>
      @else
        <x-badge variant="warning">
          <x-icon name="clock" size="xs" />
        </x-badge>
      @endif
    </div>

    <h3 class="pdf-item__title">{{ $displayName }}</h3>
    
    @if($pdfExists)
      <div class="pdf-item__info">
        <span class="pdf-item__size">{{ $pdf->formatted_size }}</span>
        <span class="pdf-item__separator">•</span>
        <span class="pdf-item__date">{{ $pdf->created_at->format('d/m/Y H:i') }}</span>
      </div>
    @else
      <div class="pdf-item__info">
        <span class="pdf-item__processing">{{ __('public.pdf_processing') }}</span>
      </div>
    @endif
  </div>
  
  <div class="pdf-item__actions">
    @if($pdfExists)
      {{-- Download button --}}
      <x-action-button
        :href="route('public.pdf-collection.download', $pdf)"
        variant="primary"
        size="sm"
        icon="pdf-download"
        download
      >
      </x-action-button>
      
      {{-- View button --}}
      <x-action-button
        :href="route('public.pdf-collection.view', $pdf)"
        target="_blank"
        variant="view"
        size="sm"
        icon="eye"
      >
      </x-action-button>
      
      {{-- Delete button (only for temporary PDFs) --}}
      @if($showDelete && !$pdf->is_permanent)
        <x-action-button
          :route="route('public.pdf-collection.destroy', $pdf)"
          method="DELETE"
          variant="delete"
          size="sm"
          icon="trash"
          :confirmMessage="__('public.confirm_delete_pdf')"
        >
        </x-action-button>
      @endif
    @endif
  </div>
</div>