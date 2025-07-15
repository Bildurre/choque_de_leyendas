<x-blocks.block :block="$block">
  <div class="text-card-block__card">
    {{-- Titles always go first, outside the wrapper --}}
    <x-blocks.titles :block="$block" />
    <div class="block__content">
      @if($block->content)
        <div class="block__text">{!! $block->content !!}</div>
      @endif
    </div>
  </div>
</x-blocks.block>