<x-admin-layout>
  <x-admin.page-header :title="__('admin.pdf_export.other_exports')">
    <x-slot:actions>
      <x-button-link 
        :href="route('admin.pdf-export.index')" 
        icon="arrow-left" 
        variant="secondary"
      >
        {{ __('admin.back') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <x-collapsible-section 
      id="pdf-custom-section" 
      title="{{ __('admin.pdf_export.custom_exports') }}"
      :open="true"
    >
      <div class="custom-exports">
        <div class="custom-exports__grid">
          @foreach($customExports as $export)
            <div class="custom-export-card">
              <div class="custom-export-card__icon">
                <x-icon name="{{ $export['icon'] ?? 'file-text' }}" />
              </div>
              <div class="custom-export-card__content">
                <h3 class="custom-export-card__title">{{ $export['name'] }}</h3>
                <p class="custom-export-card__description">{{ $export['description'] }}</p>
              </div>
              <div class="custom-export-card__actions">
                <x-button
                  type="button"
                  variant="primary"
                  icon="file-text"
                  class="generate-custom-pdf"
                  data-template="{{ $export['template'] }}"
                  data-url="{{ route('admin.pdf-export.generate-custom') }}"
                >
                  {{ __('admin.pdf_export.generate') }}
                </x-button>
              </div>
            </div>
          @endforeach
        </div>
        
        <div class="custom-exports__info">
          <x-icon name="info" />
          <p>{{ __('admin.pdf_export.custom_exports_info') }}</p>
        </div>
      </div>
    </x-collapsible-section>
  </div>
  
  <script src="{{ asset('js/admin-pdf-export.js') }}" type="module"></script>
</x-admin-layout>