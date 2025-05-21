<x-public-layout>
  @if($page->background_image)
    <x-page-background :image="asset('storage/' . $page->background_image)" />
  @endif

      @if($page->blocks->isNotEmpty())
        @foreach($page->blocks as $block)
          {!! $block->render() !!}
        @endforeach
      @else
        <div class="page-placeholder">
          {{ __('pages.no_blocks') }}
        </div>
      @endif
</x-public-layout>