<div class="pdf-options">
  <div class="pdf-options__group">
    <label class="pdf-options__label">
      <input type="checkbox" 
             id="pdf-reduce-heroes" 
             class="pdf-options__checkbox"
             checked>
      <span class="pdf-options__text">{{ __('admin.pdf_export.reduce_heroes') }}</span>
      <span class="pdf-options__help">{{ __('admin.pdf_export.reduce_heroes_help') }}</span>
    </label>
  </div>
  
  <div class="pdf-options__group">
    <label class="pdf-options__label">
      <input type="checkbox" 
             id="pdf-with-gap" 
             class="pdf-options__checkbox"
             checked>
      <span class="pdf-options__text">{{ __('admin.pdf_export.with_gap') }}</span>
      <span class="pdf-options__help">{{ __('admin.pdf_export.with_gap_help') }}</span>
    </label>
  </div>
  
  <div class="pdf-options__info">
    <x-icon name="info" />
    <p>{{ __('admin.pdf_export.options_info') }}</p>
  </div>
</div>