{{-- PDF Generation Progress (Embedded in page) --}}
<div class="pdf-progress" id="pdf-progress" style="display: none;">
  <div class="pdf-progress__content">
    <div class="pdf-progress__icon">
      <x-icon name="pdf" size="lg" />
    </div>
    
    <h3 class="pdf-progress__title">{{ __('pdf.collection.generating_pdf') }}</h3>
    
    <div class="pdf-progress__bar">
      <div class="pdf-progress__bar-fill"></div>
    </div>
    
    <p class="pdf-progress__message">{{ __('pdf.collection.generation_warning') }}</p>
    
    {{-- Success Result --}}
    <div class="pdf-progress__result" style="display: none;">
      <p class="pdf-progress__success-message">
        <x-icon name="check-circle" size="sm" />
        {{ __('pdf.collection.generation_complete') }}
      </p>
      <p class="pdf-progress__success-details">{{ __('pdf.collection.pdf_added_to_list') }}</p>
    </div>
    
    {{-- Error Result --}}
    <div class="pdf-progress__error" style="display: none;">
      <p class="pdf-progress__error-message"></p>
    </div>
  </div>
</div>