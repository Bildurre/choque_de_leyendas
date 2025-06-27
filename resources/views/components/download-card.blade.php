@props([
  'pdf',
  'temporary' => false
])

<div class="download-card {{ $temporary ? 'download-card--temporary' : '' }}" data-pdf-id="{{ $pdf->id }}">
  <div class="download-card__header">
    <x-icon name="{{ $temporary ? 'clock' : 'file-text' }}" class="download-card__icon" />
    <div class="download-card__info">
      <h3 class="download-card__name">{{ $pdf->filename }}</h3>
      <p class="download-card__meta">
        {{ $pdf->formatted_size }} • 
        @if($temporary && $pdf->expires_at)
          {{ __('public.expires_at', ['time' => $pdf->expires_at->diffForHumans()]) }}
        @else
          {{ $pdf->created_at->format('d/m/Y H:i') }}
        @endif
      </p>
    </div>
  </div>
  <div class="download-card__actions">
    <a href="{{ route('public.downloads.download', $pdf) }}" 
       class="download-card__button"
       download>
      <x-icon name="download" />
      {{ __('public.download') }}
    </a>
    @if($temporary)
      <button type="button"
              class="download-card__delete"
              data-pdf-id="{{ $pdf->id }}"
              title="{{ __('public.delete_pdf') }}">
        <x-icon name="trash-2" />
      </button>
    @endif
  </div>
</div>