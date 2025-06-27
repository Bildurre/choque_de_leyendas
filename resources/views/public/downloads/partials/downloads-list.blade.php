<div class="downloads-section">
  <h2 class="downloads-section__title">{{ __('public.available_downloads') }}</h2>
  
  {{-- PDF Generation Notification will be inserted here by JS --}}
  
  <div class="downloads-grid">
    {{-- Permanent PDFs --}}
    @forelse($permanentPdfs as $pdf)
      <x-download-card :pdf="$pdf" />
    @empty
      @if($sessionPdfs->isEmpty())
        <div class="downloads-empty">
          <x-icon name="file-x" class="downloads-empty__icon" />
          <p class="downloads-empty__text">{{ __('public.no_downloads_available') }}</p>
        </div>
      @endif
    @endforelse
    
    {{-- Session PDFs --}}
    @foreach($sessionPdfs as $pdf)
      <x-download-card :pdf="$pdf" :temporary="true" />
    @endforeach
  </div>
</div>