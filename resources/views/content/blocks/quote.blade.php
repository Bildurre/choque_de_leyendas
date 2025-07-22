@php
  $quoteContent = $block->getTranslation('content', app()->getLocale());
@endphp

<x-blocks.block :block="$block">
  <div class="quote-block__card">
    <x-blocks.titles :block="$block" />
    @if($quoteContent)
      <div class="block__content">
        <div class="block__text">{!! $quoteContent !!}</div>
      </div>
    @endif
  </div>
</x-blocks.block>