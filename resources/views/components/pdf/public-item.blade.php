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
    <h3 class="pdf-item__title">{{ $displayName }}</h3>
    
    @if($pdfExists)
      <div class="pdf-item__info">
        <span class="pdf-item__size">{{ $pdf->formatted_size }}</span>
        <span class="pdf-item__separator">•</span>
        <span class="pdf-item__date">{{ $pdf->created_at->format('d/m/Y H:i') }}</span>
      </div>
    @else
      <div class="pdf-item__info">
        <span class="pdf-item__processing">{{ __('pdf.processing') }}</span>
      </div>
    @endif
  </div>
  
  <div class="pdf-item__actions">
    @if($pdfExists)
      {{-- Download button --}}
      <x-action-button
        :href="route('public.pdf-collection.download', $pdf)"
        variant="download"
        icon="pdf-download"
        size="md"
        download
      />
      
      {{-- View button --}}
      <x-action-button
        :href="route('public.pdf-collection.view', $pdf)"
        target="_blank"
        variant="view"
        size="md"
        icon="eye"
      />
      
      {{-- Delete button (only for temporary PDFs) --}}
      @if($showDelete && !$pdf->is_permanent)
        <x-action-button
          :route="route('public.pdf-collection.destroy', $pdf)"
          method="DELETE"
          variant="delete"
          icon="trash"
          size="md"
          :confirmMessage="__('pdf.confirm_delete')"
        />
      @endif
    @endif
  </div>
</div>