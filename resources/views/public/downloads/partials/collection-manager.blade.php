<div class="collection-section">
  <div class="collection-section__header">
    <h2 class="collection-section__title">{{ __('public.temporary_collection') }}</h2>
    <div class="collection-section__stats">
      <span class="collection-stat">
        <x-icon name="layers" />
        <span class="collection-stat__text">{{ __('public.unique_items', ['count' => $totalCount]) }}</span>
      </span>
      <span class="collection-stat">
        <x-icon name="copy" />
        <span class="collection-stat__text">{{ __('public.total_copies', ['count' => $totalCopies]) }}</span>
      </span>
    </div>
  </div>

  @if($totalCount > 0)
    {{-- Collection Actions --}}
    @include('public.downloads.partials.collection-actions')

    {{-- Collection Items Grid --}}
    <div class="collection-content">
      <div class="collection-content__grid">
        {{-- Heroes --}}
        @foreach($collection['heroes'] as $key => $heroData)
          @if(isset($heroes[$heroData['id']]))
            <x-collection-item 
              :entity="$heroes[$heroData['id']]"
              :type="'hero'"
              :copies="$heroData['copies']"
            />
          @endif
        @endforeach

        {{-- Cards --}}
        @foreach($collection['cards'] as $key => $cardData)
          @if(isset($cards[$cardData['id']]))
            <x-collection-item 
              :entity="$cards[$cardData['id']]"
              :type="'card'"
              :copies="$cardData['copies']"
            />
          @endif
        @endforeach
      </div>
    </div>
  @else
    {{-- Empty State --}}
    <div class="collection-empty">
      <x-icon name="inbox" class="collection-empty__icon" />
      <p class="collection-empty__message">{{ __('public.collection_empty') }}</p>
      <p class="collection-empty__hint">{{ __('public.collection_empty_hint') }}</p>
    </div>
  @endif
</div>