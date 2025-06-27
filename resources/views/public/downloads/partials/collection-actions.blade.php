<div class="collection-actions">
  <x-button
    type="button"
    variant="secondary"
    icon="trash-2"
    class="collection-clear-all"
  >
    {{ __('public.clear_collection') }}
  </x-button>
  
  <div class="collection-actions__generate">
    <div class="collection-options">
      <label class="collection-option">
        <input type="checkbox" id="reduce-heroes" class="collection-option__input">
        <span class="collection-option__label">{{ __('public.reduce_heroes') }}</span>
      </label>
      <label class="collection-option">
        <input type="checkbox" id="with-gap" class="collection-option__input" checked>
        <span class="collection-option__label">{{ __('public.with_gap') }}</span>
      </label>
    </div>
    
    <x-button
      type="button"
      variant="primary"
      icon="file-text"
      class="collection-generate-pdf"
      data-url="{{ route('public.collection.generate-pdf') }}"
    >
      {{ __('public.generate_pdf') }}
    </x-button>
  </div>
</div>