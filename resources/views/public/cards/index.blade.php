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
      'background_color' => 'theme-border',
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

  {{-- CTA Block --}}
  @php
    $ctaBlock = new \App\Models\Block([
      'type' => 'cta',
      'title' => ['es' => 'Conoce los componentes', 'en' => 'Know the Components'],
      'image' => null,
      'subtitle' => ['es' => 'Descubre cada elemento que da vida al juego: héroes, cartas de acción, contadores de beneficio y perjuicio y el sistema de dados de acción', 'en' => 'Discover every element that brings the game to life: heroes, action cards, boon and bane counters and the action dice system'],
      'background_color' => 'theme-border',
      'content' => [
        'en' => [
          'button_text' => 'Explore Components',
          'button_link' => '/en/game-components'
        ],
        'es' => [
          'button_text' => 'Explorar Componentes',
          'button_link' => '/es/componentes-del-juego'
        ]
      ],
      'settings' => [
        'button_variant' => 'primary',
        'button_size' => 'lg',
        'width' => 'md',
        'text_alignment' => 'center'
      ]
    ]);
  @endphp
  {!! $ctaBlock->render() !!}

  {{-- Related Heroes Block --}}
  @php
    $heroesBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => null,
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
        'text_alignment' => 'left',
        'button_size' => 'md',
        'button_variant' => 'secondary',
      ]
    ]);
  @endphp
  {!! $heroesBlock->render() !!}

  {{-- Related Factions Block --}}
  @php
    $factionsBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => null,
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
        'text_alignment' => 'left',
        'button_size' => 'md',
        'button_variant' => 'secondary',
      ]
    ]);
  @endphp
  {!! $factionsBlock->render() !!}
</x-public-layout>