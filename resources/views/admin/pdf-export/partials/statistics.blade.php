<div class="pdf-statistics">
  <div class="pdf-statistics__grid">
    {{-- Permanent PDFs --}}
    <div class="pdf-statistics__card">
      <div class="pdf-statistics__icon pdf-statistics__icon--permanent">
        <x-icon name="hard-drive" />
      </div>
      <div class="pdf-statistics__content">
        <h3 class="pdf-statistics__title">{{ __('admin.pdf_export.permanent_pdfs') }}</h3>
        <div class="pdf-statistics__value">{{ $statistics['permanent']['count'] }}</div>
        <div class="pdf-statistics__meta">{{ $statistics['permanent']['formatted_size'] }}</div>
      </div>
    </div>
    
    {{-- Temporary PDFs --}}
    <div class="pdf-statistics__card">
      <div class="pdf-statistics__icon pdf-statistics__icon--temporary">
        <x-icon name="clock" />
      </div>
      <div class="pdf-statistics__content">
        <h3 class="pdf-statistics__title">{{ __('admin.pdf_export.temporary_pdfs') }}</h3>
        <div class="pdf-statistics__value">{{ $statistics['temporary']['count'] }}</div>
        <div class="pdf-statistics__meta">{{ $statistics['temporary']['formatted_size'] }}</div>
      </div>
    </div>
    
    {{-- Total Storage --}}
    <div class="pdf-statistics__card">
      <div class="pdf-statistics__icon pdf-statistics__icon--total">
        <x-icon name="database" />
      </div>
      <div class="pdf-statistics__content">
        <h3 class="pdf-statistics__title">{{ __('admin.pdf_export.total_storage') }}</h3>
        <div class="pdf-statistics__value">{{ $statistics['total']['count'] }}</div>
        <div class="pdf-statistics__meta">{{ $statistics['total']['formatted_size'] }}</div>
      </div>
    </div>
  </div>
  
  {{-- Actions --}}
  <div class="pdf-statistics__actions">
    <x-button
      type="button"
      variant="secondary"
      icon="trash-2"
      class="cleanup-temp-pdfs"
      data-url="{{ route('admin.pdf-export.cleanup') }}"
    >
      {{ __('admin.pdf_export.cleanup_temporary') }}
    </x-button>
  </div>
</div>