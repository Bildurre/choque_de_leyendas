<x-public-layout>
  <section class="block">
    <div class="block__inner">
      <div class="collection-page">
        <div class="collection-page__header">
          <h1 class="collection-page__title">{{ __('public.print_collection_title') }}</h1>
          
          <div class="collection-page__actions">
            @if(count($collection['heroes']) > 0 || count($collection['cards']) > 0)
              <x-button
                type="button"
                variant="secondary"
                icon="trash"
                class="collection-clear-all"
              >
                {{ __('public.clear_all') }}
              </x-button>
              
              <x-button-link
                :href="route('public.print-collection.generate-pdf')"
                variant="primary"
                icon="file-text"
              >
                {{ __('public.generate_pdf') }}
              </x-button-link>
            @endif
          </div>
        </div>
        
        @if(count($collection['heroes']) > 0 || count($collection['cards']) > 0)
          <div class="collection-content">
            <div class="collection-content__grid">
              {{-- Heroes --}}
              @foreach($collection['heroes'] as $key => $heroData)
                @if(isset($heroes[$heroData['id']]))
                  @php $hero = $heroes[$heroData['id']]; @endphp
                  <div class="collection-item">
                    <div class="collection-item__preview">
                      <x-previews.hero :hero="$hero" />
                    </div>
                    <div class="collection-item__controls">
                      <div class="collection-item__quantity-group">
                        <label>{{ __('public.copies') }}:</label>
                        <input 
                          type="number" 
                          class="collection-item__quantity" 
                          value="{{ $heroData['copies'] }}" 
                          min="1" 
                          max="99"
                        >
                        <button 
                          type="button"
                          class="collection-item__update"
                          data-entity-type="hero"
                          data-entity-id="{{ $hero->id }}"
                          title="{{ __('public.update_quantity') }}"
                        >
                          <x-icon name="check" size="sm" />
                        </button>
                      </div>
                      <button 
                        type="button"
                        class="collection-item__remove"
                        data-entity-type="hero"
                        data-entity-id="{{ $hero->id }}"
                        title="{{ __('public.remove_from_collection') }}"
                      >
                        <x-icon name="trash" size="sm" />
                      </button>
                    </div>
                  </div>
                @endif
              @endforeach
              
              {{-- Cards --}}
              @foreach($collection['cards'] as $key => $cardData)
                @if(isset($cards[$cardData['id']]))
                  @php $card = $cards[$cardData['id']]; @endphp
                  <div class="collection-item">
                    <div class="collection-item__preview">
                      <x-previews.card :card="$card" />
                    </div>
                    <div class="collection-item__controls">
                      <div class="collection-item__quantity-group">
                        <label>{{ __('public.copies') }}:</label>
                        <input 
                          type="number" 
                          class="collection-item__quantity" 
                          value="{{ $cardData['copies'] }}" 
                          min="1" 
                          max="99"
                        >
                        <button 
                          type="button"
                          class="collection-item__update"
                          data-entity-type="card"
                          data-entity-id="{{ $card->id }}"
                          title="{{ __('public.update_quantity') }}"
                        >
                          <x-icon name="check" size="sm" />
                        </button>
                      </div>
                      <button 
                        type="button"
                        class="collection-item__remove"
                        data-entity-type="card"
                        data-entity-id="{{ $card->id }}"
                        title="{{ __('public.remove_from_collection') }}"
                      >
                        <x-icon name="trash" size="sm" />
                      </button>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
          </div>
        @else
          <div class="collection-empty">
            <div class="collection-empty__icon">
              <x-icon name="file-text" />
            </div>
            <p class="collection-empty__message">{{ __('public.collection_empty_message') }}</p>
            <x-button-link
              :href="route('public.heroes.index')"
              variant="primary"
            >
              {{ __('public.browse_content') }}
            </x-button-link>
          </div>
        @endif
      </div>
    </div>
  </section>
</x-public-layout>