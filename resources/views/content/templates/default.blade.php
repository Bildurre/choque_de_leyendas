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
      @if($page->blocks->isNotEmpty())
        @foreach($page->blocks as $block)
          {!! $block->render() !!}
        @endforeach
      @else
        <div class="page-placeholder">
          {{ __('pages.no_blocks') }}
        </div>
      @endif
    </div>
  </div>
</x-public-layout>