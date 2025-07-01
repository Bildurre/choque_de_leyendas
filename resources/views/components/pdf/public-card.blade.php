@props([
  'pdf',
  'temporary' => false
])

<div class="pdf-public-card {{ $temporary ? 'pdf-public-card--temporary' : '' }}" data-pdf-id="{{ $pdf->id }}">
  <div class="pdf-public-card__info">
    <x-icon name="{{ $temporary ? 'clock' : 'file-text' }}" class="pdf-public-card__icon" />
    <div>
      <h4 class="pdf-public-card__name">{{ $pdf->filename }}</h4>
      <p class="pdf-public-card__meta">
        <span>{{ $pdf->formatted_size }}</span>
        <span class="pdf-public-card__separator">•</span>
        @if($temporary && $pdf->expires_at)
          <span>{{ __('public.expires_at', ['time' => $pdf->expires_at->diffForHumans()]) }}</span>
        @else
          <span>{{ $pdf->created_at->format('d/m/Y H:i') }}</span>
        @endif
      </p>
    </div>
  </div>
  
  <div class="pdf-public-card__actions">
    <x-button
      tag="a"
      :href="$pdf->url"
      target="_blank"
      variant="secondary"
      icon="eye"
      size="sm"
      title="{{ __('public.view_pdf') }}"
    >
      {{ __('public.view') }}
    </x-button>
    
    <x-button
      tag="a"
      :href="route('public.downloads.download', $pdf)"
      variant="primary"
      icon="download"
      size="sm"
      download
      title="{{ __('public.download_pdf') }}"
    >
      {{ __('public.download') }}
    </x-button>
    
    @if($temporary)
      <x-button
        type="button"
        variant="danger"
        icon="trash-2"
        size="sm"
        class="delete-pdf-btn"
        data-pdf-id="{{ $pdf->id }}"
        title="{{ __('public.delete_pdf') }}"
      >
        {{ __('public.delete') }}
      </x-button>
    @endif
  </div>
</div>