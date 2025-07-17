<x-blocks.block :block="$block">
  {{-- Titles always go first, outside the wrapper --}}
  <x-blocks.titles :block="$block" />
  <div class="block__content">
    @if($block->content)
      <div class="block__text">{!! $block->content !!}</div>
    @endif
    @if($counters->isNotEmpty())
      <ul class="counter-list">
        @foreach($counters as $counter)
          <li class="counter-list__item">
            @if($counter->hasImage())
              <figure class="counter-list__image-wrapper">
                <img src="{{ $counter->getImageUrl() }}" 
                  alt="{{ __('pages.blocks.counter_list.image_alt', ['name' => $counter->name]) }}"
                  class="counter-list__image"
                  loading="lazy"
                >
              </figure>
            @endif
            <div class="counter-list__content">
              <h3 class="counter-list__name">{{ $counter->name }}</h3>
              <p class="counter-list__effect">{{ $counter->effect }}</p>
            </div>
          </li>
        @endforeach
      </ul>
    @else
        <div class="block__empty">
            <p>{{ __('pages.blocks.counter_list.no_counters', ['type' => __('entities.counters.types.' . $counterType)]) }}</p>
        </div>
    @endif
  </div>
</x-blocks.block>