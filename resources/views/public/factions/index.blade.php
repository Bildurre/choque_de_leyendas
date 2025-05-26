<x-public-layout>
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
        'text_alignment' => 'left'
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
        emptyMessage="{{ __('public.factions.empty') }}"
      >
        <x-slot:filters>
          {{-- Space for future filters if needed --}}
        </x-slot:filters>

        @foreach($factions as $faction)
          <div class="factions-list__item">
            <a href="{{ route('public.factions.show', $faction) }}" class="factions-list__link">
              <x-previews.preview-image :entity="$faction" type="faction" />
            </a>
          </div>
        @endforeach
      </x-entity.list>
    </div>
  </section>

  {{-- Related Heroes Block --}}
  @php
    $heroesBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Héroes', 'en' => 'Heroes'],
      'subtitle' => ['es' => 'Conoce a los héroes de las facciones', 'en' => 'Meet the faction heroes'],
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

  {{-- Related Cards Block --}}
  @php
    $cardsBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Cartas', 'en' => 'Cards'],
      'subtitle' => ['es' => 'Descubre las cartas de las facciones', 'en' => 'Discover the faction cards'],
      'background_color' => 'none',
      'settings' => [
        'model_type' => 'card',
        'display_type' => 'random',
        'button_text' => __('public.view_all_cards'),
        'text_alignment' => 'left'
      ]
    ]);
  @endphp
  {!! $cardsBlock->render() !!}
</x-public-layout>