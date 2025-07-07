<x-public-layout>
  {{-- Page background image --}}
  @if($card->hasImage())
    <x-page-background :image="$card->getImageUrl()" />
  @endif

  {{-- Header Block --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = $card->getTranslation('name', $locale);
      $subtitleTranslations[$locale] = $card->getTranslation('lore_text', $locale);
    }
    
    $headerBlock = new \App\Models\Block([
      'type' => 'header',
      'title' => $titleTranslations,
      'subtitle' => $subtitleTranslations,
      'background_color' => 'none',
      'settings' => [
        'text_alignment' => 'center'
      ]
    ]);
  @endphp
  {!! $headerBlock->render() !!}

  {{-- Print Collection Button --}}
  <div class="info-block">
    <x-pdf.add-button
      data-entity-type="card"
      data-entity-id="{{ $card->id }}"
    </x-pdf.add-button>
  </div>

  {{-- Card Details Section --}}
  <section class="block card-detail-block">
    <div class="block__inner">
      <div class="card-detail-content">
        {{-- Preview Image --}}
        <div class="card-detail__preview">
          <x-previews.preview-image :entity="$card" type="card" />
        </div>
        
        {{-- Card Info --}}
        <div class="card-detail__info">
          {{-- Basic Info Block --}}
          <div class="info-block">
            <h2 class="info-block__title">{{ __('public.cards.basic_info') }}</h2>
            <dl class="info-list">
              <dt>{{ __('entities.cards.name') }}</dt>
              <dd>{{ $card->name }}</dd>
              
              <dt>{{ __('entities.factions.singular') }}</dt>
              <dd>
                <a href="{{ route('public.factions.show', $card->faction) }}" class="info-link">
                  {{ $card->faction->name }}
                </a>
              </dd>
              
              <dt>{{ __('entities.card_types.singular') }}</dt>
              <dd>{{ $card->cardType->name }}</dd>
              
              @if($card->cardType->id == 1 && $card->equipmentType) {{-- Equipment type --}}
                <dt>{{ __('entities.equipment_types.singular') }}</dt>
                <dd>{{ $card->equipmentType->name }}</dd>
                
                @if($card->equipmentType->category == 'weapon' && $card->hands)
                  <dt>{{ __('entities.cards.hands_required') }}</dt>
                  <dd>
                    {{ $card->hands }} 
                    {{ $card->hands > 1 ? __('entities.cards.hands') : __('entities.cards.hand') }}
                  </dd>
                @endif
              @endif
              
              @if($card->cost)
                <dt>{{ __('entities.cards.cost') }}</dt>
                <dd>
                  <x-cost-display :cost="$card->cost" />
                </dd>
              @endif
            </dl>
          </div>

          {{-- Attack Info Block --}}
          @if($card->cardType->heroSuperclass || $card->attackRange || $card->attackSubtype)
            <div class="info-block">
              <h2 class="info-block__title">{{ __('public.cards.attack_info') }}</h2>
              <dl class="info-list">
                @if($card->cardType->heroSuperclass)
                  <dt>{{ __('entities.hero_superclasses.singular') }}</dt>
                  <dd>{{ $card->cardType->heroSuperclass->name }}</dd>
                @endif
                
                @if($card->attackRange)
                  <dt>{{ __('entities.attack_ranges.singular') }}</dt>
                  <dd>{{ $card->attackRange->name }}</dd>
                @endif
                
                @if($card->attackSubtype)
                  <dt>{{ __('entities.attack_subtypes.attack_type') }}</dt>
                  <dd>{{ __('entities.attack_subtypes.types.' . $card->attackSubtype->type) }}</dd>
                  
                  <dt>{{ __('entities.attack_subtypes.singular') }}</dt>
                  <dd>{{ $card->attackSubtype->name }}</dd>
                @endif
                
                @if($card->area)
                  <dt>{{ __('entities.cards.area') }}</dt>
                  <dd>{{ __('common.yes') }}</dd>
                @endif
              </dl>
            </div>
          @endif

          {{-- Effects Block --}}
          @if($card->restriction || $card->effect || $card->heroAbility)
            <div class="info-block">
              <h2 class="info-block__title">{{ __('public.cards.effects') }}</h2>
              
              @if($card->restriction)
                <div class="effect-section">
                  <h3 class="effect-section__title">{{ __('entities.cards.restriction') }}</h3>
                  <div class="effect-content">{!! $card->restriction !!}</div>
                </div>
              @endif
              
              @if($card->effect)
                <div class="effect-section">
                  <h3 class="effect-section__title">{{ __('entities.cards.effect') }}</h3>
                  <div class="effect-content">{!! $card->effect !!}</div>
                </div>
              @endif
              
              @if($card->heroAbility)
                <div class="effect-section">
                  <h3 class="effect-section__title">{{ __('entities.hero_abilities.singular') }}</h3>
                  <div class="ability-item ability-item--active">
                    <div class="ability-header">
                      <div class="ability-info">
                        <h4 class="ability-name">{{ $card->heroAbility->name }}</h4>
                        <div class="ability-types">
                          {{ $card->heroAbility->attackRange->name }} - 
                          {{ __('entities.attack_subtypes.types.' . $card->heroAbility->attackSubtype->type) }} - 
                          {{ $card->heroAbility->attackSubtype->name }}
                          @if($card->heroAbility->area)
                            - {{ __('entities.hero_abilities.area') }}
                          @endif
                        </div>
                      </div>
                      <div class="ability-cost">
                        <x-cost-display :cost="$card->heroAbility->cost" />
                      </div>
                    </div>
                    <div class="ability-description">{!! $card->heroAbility->description !!}</div>
                  </div>
                </div>
              @endif
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>

  {{-- Related Cards Block --}}
  @php
    $relatedCardsBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Otras Cartas', 'en' => 'Other Cards'],
      'subtitle' => ['es' => 'Descubre más cartas del juego', 'en' => 'Discover more game cards'],
      'background_color' => 'none',
      'settings' => [
        'model_type' => 'card',
        'display_type' => 'random',
        'button_text' => __('public.view_all_cards'),
        'text_alignment' => 'left'
      ]
    ]);
  @endphp
  {!! $relatedCardsBlock->render() !!}

  {{-- Related Heroes Block --}}
  @php
    $relatedHeroesBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Héroes Relacionados', 'en' => 'Related Heroes'],
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
  {!! $relatedHeroesBlock->render() !!}
</x-public-layout>