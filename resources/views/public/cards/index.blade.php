<x-public-layout
  :title="__('entities.cards.index_page_title')"
  :metaDescription="__('entities.cards.index_page_description')"
>

  <x-page-background :image="asset('storage/images/pages/cards-bg.jpeg')" />

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
          <x-entity.public-card 
            :entity="$card"
            type="card"
            :view-route="route('public.cards.show', $card)"
          />
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
      'content' => [
        'en' => [
          'button_text' => __('View all Heroes'),
        ],
        'es' => [
          'button_text' => __('Ver todos los Héroes'),
        ]
      ],
      'settings' => [
        'model_type' => 'hero',
        'display_type' => 'random',
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
      'content' => [
        'en' => [
          'button_text' => __('View all Factions'),
        ],
        'es' => [
          'button_text' => __('Ver todas las Facciones'),
        ]
      ],
      'settings' => [
        'model_type' => 'faction',
        'display_type' => 'random',
        'text_alignment' => 'left'
      ]
    ]);
  @endphp
  {!! $factionsBlock->render() !!}
</x-public-layout>