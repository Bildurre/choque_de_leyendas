@php
  $textCardContent = $block->getTranslation('content', app()->getLocale());
  $textCardText = $textCardContent['text'] ?? '';
  $textCardLabel = $textCardContent['label'] ?? '';
@endphp

<x-blocks.block :block="$block">
  <div class="text-card-block__card">
    <x-blocks.titles :block="$block" />
    @if($textCardLabel)
      <div class="block__label">{!! $textCardLabel !!}</div>
    @endif
    <div class="block__content">
      @if($textCardText)
        <div class="block__text">{!! $textCardText !!}</div>
      @endif
    </div>
  </div>
</x-blocks.block>