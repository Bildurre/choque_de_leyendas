<div class="pdf-collection__section pdf-collection__section--temporary" id="temporary-collection">
  <div class="block-header">
    <h2 class="block__title">
      {{ __('pdf.collection.temporary_collection') }}
    </h2>

    <div class="temporary-collection__actions" data-collection-actions style="display: none;">
      <form action="{{ route('public.pdf-collection.clear') }}" method="POST" class="temporary-collection__form">
        @csrf
        @method('DELETE')
        <x-button 
          type="submit" 
          variant="secondary"
          size="md"
          :confirmMessage="__('pdf.collection.confirm_clear')"
        >
          {{ __('pdf.collection.clear') }}
        </x-button>
      </form>
      
      <form action="{{ route('public.pdf-collection.generate') }}" method="POST" class="temporary-collection__form" id="generate-pdf-form">
        @csrf
        <x-button 
          type="submit" 
          variant="primary"
          size="md"
        >
          {{ __('pdf.collection.generate_pdf') }}
        </x-button>
      </form>
    </div>
  </div>
  
  <div class="temporary-collection">
    {{-- Collection grid --}}
    <x-entity.list
      :items="[]"
      :showHeader="false"
      :emptyMessage="__('pdf.collection.empty_collection')"
      class="temporary-collection__list"
      data-collection-content
    >
      {{-- Items will be loaded here via JavaScript directly into entity-list__items --}}
    </x-entity.list>
  </div>
</div>