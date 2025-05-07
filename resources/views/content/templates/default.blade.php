<x-public-layout>
  <div class="page-container">
    <div class="page-header" @if($page->background_image) style="background-image: url('{{ asset('storage/' . $page->background_image) }}');" @endif>
      <h1 class="page-title">{{ $page->title }}</h1>
      
      @if($page->description)
        <div class="page-description">
          {!! $page->description !!}
        </div>
      @endif
    </div>
    
    <div class="page-content">
      <!-- Aquí irán los bloques en el futuro -->
      <div class="page-placeholder">
        {{ __('pages.no_blocks') }}
      </div>
    </div>
  </div>
</x-public-layout>