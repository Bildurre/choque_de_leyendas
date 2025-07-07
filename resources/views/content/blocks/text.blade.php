@php
  $imagePosition = $block->settings['image_position'] ?? 'top';
  $isClearfix = str_contains($imagePosition, 'clearfix');
@endphp

<x-blocks.block :block="$block">
  {{-- Titles always go first, outside the wrapper --}}
  <x-blocks.titles :block="$block" />
  
  @if($block->image)
    @if($isClearfix)
      {{-- Clearfix layout: image floats, text wraps --}}
      <x-blocks.content-wrapper :block="$block">
        <x-blocks.image :block="$block" />
        @if($block->content)
          <div class="block__text">{!! $block->content !!}</div>
        @endif
      </x-blocks.content-wrapper>
    @else
      {{-- Grid layout: structured columns --}}
      <x-blocks.content-wrapper :block="$block">
        @if(in_array($imagePosition, ['top', 'left']))
          <x-blocks.image :block="$block" />
        @endif
        
        <div class="block__content">
          @if($block->content)
            <div class="block__text">{!! $block->content !!}</div>
          @endif
        </div>
        
        @if(in_array($imagePosition, ['bottom', 'right']))
          <x-blocks.image :block="$block" />
        @endif
      </x-blocks.content-wrapper>
    @endif
  @else
    <div class="block__content">
      @if($block->content)
        <div class="block__text">{!! $block->content !!}</div>
      @endif
    </div>
  @endif
</x-blocks.block>