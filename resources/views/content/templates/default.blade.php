<x-public-layout 
  :title="$page->meta_title ?: $page->title . ' - ' .  __('common.full_title')"
  :metaDescription="$page->meta_description ?: Str::limit(strip_tags($page->description), 160)"
>
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