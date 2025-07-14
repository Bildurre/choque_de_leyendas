<x-public-layout
  :title="__('entities.factions.index_page_title')"
  :metaDescription="__('entities.factions.index_page_description')"
>
  {{-- Header Block --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = __('public.factions.title', [], $locale);
      
      $description = __('public.factions.description', [], $locale);
      $subtitleTranslations[$locale] = $description !== 'public.factions.description' ? $description : '';
    }
    
    $headerBlock = new \App\Models\Block([
      'type' => 'header',
      'title' => $titleTranslations,
      'subtitle' => $subtitleTranslations,
      'background_color' => 'none',
      'settings' => [
        'text_alignment' => 'justify'
      ]
    ]);
  @endphp
  {!! $headerBlock->render() !!}

  {{-- Factions List Block --}}
  <section class="block">
    <div class="block__inner">
      <x-entity.list 
        :items="$factions"
        :showHeader="false"
        emptyMessage="{{ __('public.factions.no_factions') }}"
      >
        <x-slot:filters>
          <x-filters.card
            :model="$factionModel"
            :request="$request"
            :totalCount="$totalCount"
            :filteredCount="$filteredCount"
            context="public"
          />
        </x-slot:filters>

        @foreach($factions as $faction)
          <x-entity.public-card 
            :entity="$faction"
            type="faction"
            :view-route="route('public.factions.show', $faction)"
          />
        @endforeach
        
        <x-slot:pagination>
          {{ $factions->links() }}
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
      'background_color' => 'theme-card',
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
</x-public-layout>