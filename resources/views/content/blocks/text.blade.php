@php
  $imagePosition = $block->settings['image_position'] ?? 'top';
@endphp

<x-blocks.block :block="$block">
  @if($block->image)
    <x-blocks.content-wrapper :block="$block">
      @if(in_array($imagePosition, ['top', 'left']))
        <x-blocks.image :block="$block" />
      @endif
      
      <div class="block__content">
        <x-blocks.titles :block="$block" />
        
        @if($block->content)
          <div class="block__text">{!! $block->content !!}</div>
        @endif
      </div>
      
      @if(in_array($imagePosition, ['bottom', 'right']))
        <x-blocks.image :block="$block" />
      @endif
    </x-blocks.content-wrapper>
  @else
    <div class="block__content">
      <x-blocks.titles :block="$block" />
      
      @if($block->content)
        <div class="block__text">{!! $block->content !!}</div>
      @endif
    </div>
  @endif
</x-blocks.block>