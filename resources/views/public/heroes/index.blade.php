<x-public-layout
  :title="__('entities.heroes.index_page_title')"
  :metaDescription="__('entities.heroes.index_page_description')"
>

  <x-page-background :image="asset('storage/images/pages/heroes-bg.jpeg')" />

  {{-- Header Block --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = __('public.heroes.title', [], $locale);
      
      $description = __('public.heroes.description', [], $locale);
      $subtitleTranslations[$locale] = $description !== 'public.heroes.description' ? $description : '';
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

  {{-- Heroes List Block --}}
  <section class="block">
    <div class="block__inner">
      <x-entity.list 
        :items="$heroes"
        :showHeader="false"
        emptyMessage="{{ __('public.heroes.no_heroes') }}"
      >
        <x-slot:filters>
          <x-filters.card
            :model="$heroModel"
            :request="$request"
            :totalCount="$totalCount"
            :filteredCount="$filteredCount"
            context="public"
          />
        </x-slot:filters>

        @foreach($heroes as $hero)
          <x-entity.public-card 
            :entity="$hero"
            type="hero"
            :view-route="route('public.heroes.show', $hero)"
          />
        @endforeach
        
        <x-slot:pagination>
          {{ $heroes->links() }}
        </x-slot:pagination>
      </x-entity.list>
    </div>
  </section>

  {{-- Related Cards Block --}}
  @php
    $cardsBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Cartas', 'en' => 'Cards'],
      'subtitle' => ['es' => 'Descubre las cartas del juego', 'en' => 'Discover the game cards'],
      'background_color' => 'none',
      'content' => [
        'en' => [
          'button_text' => __('View all Cards'),
        ],
        'es' => [
          'button_text' => __('Ver todas las Cartas'),
        ]
      ],
      'settings' => [
        'model_type' => 'card',
        'display_type' => 'random',
        'text_alignment' => 'left'
      ]
    ]);
  @endphp
  {!! $cardsBlock->render() !!}

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