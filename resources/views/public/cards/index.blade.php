<x-public-layout>
  {{-- Header Block --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = __('public.cards.title', [], $locale);
      
      $description = __('public.cards.description', [], $locale);
      $subtitleTranslations[$locale] = $description !== 'public.cards.description' ? $description : '';
    }
    
    $headerBlock = new \App\Models\Block([
      'type' => 'header',
      'title' => $titleTranslations,
      'subtitle' => $subtitleTranslations,
      'background_color' => 'none',
      'settings' => [
        'text_alignment' => 'left'
      ]
    ]);
  @endphp
  {!! $headerBlock->render() !!}

  {{-- Cards List Block --}}
  <section class="block">
    <div class="block__inner">
      <x-entity.list 
        :items="$cards"
        :showHeader="false"
        emptyMessage="{{ __('public.cards.no_cards') }}"
      >
        <x-slot:filters>
          <x-filters.card
            :model="$cardModel"
            :request="$request"
            :totalCount="$totalCount"
            :filteredCount="$filteredCount"
            context="public"
          />
        </x-slot:filters>

        @foreach($cards as $card)
          <div class="cards-list__item">
            <a href="{{ route('public.cards.show', $card) }}" class="cards-list__link">
              <x-previews.preview-image :entity="$card" type="card" />
            </a>
          </div>
        @endforeach
        
        <x-slot:pagination>
          {{ $cards->links() }}
        </x-slot:pagination>
      </x-entity.list>
    </div>
  </section>

  {{-- Related Heroes Block --}}
  @php
    $heroesBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Héroes', 'en' => 'Heroes'],
      'subtitle' => ['es' => 'Conoce a los héroes del juego', 'en' => 'Meet the game heroes'],
      'background_color' => 'none',
      'settings' => [
        'model_type' => 'hero',
        'display_type' => 'random',
        'button_text' => __('public.view_all_heroes'),
        'text_alignment' => 'left'
      ]
    ]);
  @endphp
  {!! $heroesBlock->render() !!}

  {{-- Related Factions Block --}}
  @php
    $factionsBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Facciones', 'en' => 'Factions'],
      'subtitle' => ['es' => 'Explora las facciones disponibles', 'en' => 'Explore the available factions'],
      'background_color' => 'none',
      'settings' => [
        'model_type' => 'faction',
        'display_type' => 'random',
        'button_text' => __('public.view_all_factions'),
        'text_alignment' => 'left'
      ]
    ]);
  @endphp
  {!! $factionsBlock->render() !!}
</x-public-layout>