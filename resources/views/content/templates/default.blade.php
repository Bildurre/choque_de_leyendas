<x-public-layout>
  @if($page->background_image)
    <x-page-background :image="asset('storage/' . $page->background_image)" />
  @endif

  <div class="page-container">
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