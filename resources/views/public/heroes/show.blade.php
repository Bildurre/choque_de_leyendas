<x-public-layout>
  {{-- Page background image --}}
  @if($hero->hasImage())
    <x-page-background :image="$hero->getImageUrl()" />
  @endif

  {{-- Header Block --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = $hero->getTranslation('name', $locale);
      $subtitleTranslations[$locale] = $hero->getTranslation('lore_text', $locale);
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

  {{-- Hero Details Section --}}
  <section class="block hero-detail-block">
    <div class="block__inner">
      <div class="hero-detail-content">
        {{-- Preview Image --}}
        <div class="hero-detail__preview">
          <x-previews.preview-image :entity="$hero" type="hero" />
        </div>
        
        {{-- Hero Info --}}
        <div class="hero-detail__info">
          {{-- Basic Info Block --}}
          <div class="info-block">
            <h2 class="info-block__title">{{ __('public.heroes.basic_info') }}</h2>
            <dl class="info-list">
              <dt>{{ __('entities.heroes.name') }}</dt>
              <dd>{{ $hero->name }}</dd>
              
              <dt>{{ __('entities.factions.singular') }}</dt>
              <dd>
                <a href="{{ route('public.factions.show', $hero->faction) }}" class="info-link">
                  {{ $hero->faction->name }}
                </a>
              </dd>
              
              <dt>{{ __('entities.hero_races.singular') }}</dt>
              <dd>{{ $hero->getGenderizedRaceName() }}</dd>
              
              <dt>{{ __('entities.heroes.gender') }}</dt>
              <dd>{{ __('entities.heroes.genders.' . $hero->gender) }}</dd>
              
              <dt>{{ __('entities.hero_classes.singular') }}</dt>
              <dd>{{ $hero->getGenderizedClassName() }}</dd>
              
              <dt>{{ __('entities.hero_superclasses.singular') }}</dt>
              <dd>{{ $hero->getGenderizedSuperclassName() }}</dd>
            </dl>
          </div>

          {{-- Print Collection Button --}}
          <div class="info-block">
            <button 
              type="button" 
              class="print-collection-add"
              data-entity-type="hero"
              data-entity-id="{{ $hero->id }}"
            >
              <x-icon name="file-text" size="sm" />
              {{ __('public.save_to_pdf') }}
            </button>
          </div>

          {{-- Attributes Block --}}
          <div class="info-block">
            <h2 class="info-block__title">{{ __('public.heroes.attributes') }}</h2>
            <div class="attributes-grid">
              <div class="attribute-item">
                <x-icon-attribute type="agility" />
                <span class="attribute-value">{{ $hero->agility }}</span>
                <span class="attribute-label">{{ __('entities.heroes.attributes.agility') }}</span>
              </div>
              <div class="attribute-item">
                <x-icon-attribute type="mental" />
                <span class="attribute-value">{{ $hero->mental }}</span>
                <span class="attribute-label">{{ __('entities.heroes.attributes.mental') }}</span>
              </div>
              <div class="attribute-item">
                <x-icon-attribute type="will" />
                <span class="attribute-value">{{ $hero->will }}</span>
                <span class="attribute-label">{{ __('entities.heroes.attributes.will') }}</span>
              </div>
              <div class="attribute-item">
                <x-icon-attribute type="strength" />
                <span class="attribute-value">{{ $hero->strength }}</span>
                <span class="attribute-label">{{ __('entities.heroes.attributes.strength') }}</span>
              </div>
              <div class="attribute-item">
                <x-icon-attribute type="armor" />
                <span class="attribute-value">{{ $hero->armor }}</span>
                <span class="attribute-label">{{ __('entities.heroes.attributes.armor') }}</span>
              </div>
              <div class="attribute-item attribute-item--total">
                <x-icon-attribute type="health" />
                <span class="attribute-value">{{ $hero->health }}</span>
                <span class="attribute-label">{{ __('entities.heroes.attributes.health') }}</span>
              </div>
            </div>
          </div>

          {{-- Abilities Block --}}
          <div class="info-block">
            <h2 class="info-block__title">{{ __('public.heroes.abilities') }}</h2>
            
            {{-- Passive Abilities --}}
            <div class="abilities-section">
              <h3 class="abilities-section__subtitle">{{ __('public.heroes.passive_abilities') }}</h3>
              
              {{-- Class Passive --}}
              <div class="ability-item ability-item--passive">
                <h4 class="ability-name">{{ $hero->heroClass->name }}</h4>
                <div class="ability-description">{!! $hero->heroClass->passive !!}</div>
              </div>
              
              {{-- Hero Passive --}}
              <div class="ability-item ability-item--passive">
                <h4 class="ability-name">{{ $hero->passive_name }}</h4>
                <div class="ability-description">{!! $hero->passive_description !!}</div>
              </div>
            </div>
            
            {{-- Active Abilities --}}
            @if($hero->heroAbilities->count() > 0)
              <div class="abilities-section">
                <h3 class="abilities-section__subtitle">{{ __('public.heroes.active_abilities') }}</h3>
                
                @foreach($hero->heroAbilities as $ability)
                  <div class="ability-item ability-item--active">
                    <div class="ability-header">
                      <div class="ability-info">
                        <h4 class="ability-name">{{ $ability->name }}</h4>
                        <div class="ability-types">
                          {{ $ability->attackRange->name }} - 
                          {{ __('entities.attack_subtypes.types.' . $ability->attackSubtype->type) }} - 
                          {{ $ability->attackSubtype->name }}
                          @if($ability->area)
                            - {{ __('entities.hero_abilities.area') }}
                          @endif
                        </div>
                      </div>
                      <div class="ability-cost">
                        <x-cost-display :cost="$ability->cost" />
                      </div>
                    </div>
                    <div class="ability-description">{!! $ability->description !!}</div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Related Heroes Block --}}
  @php
    $relatedHeroesBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Otros Héroes', 'en' => 'Other Heroes'],
      'subtitle' => ['es' => 'Descubre más héroes del juego', 'en' => 'Discover more game heroes'],
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

  {{-- Related Cards Block --}}
  @php
    $relatedCardsBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Cartas Relacionadas', 'en' => 'Related Cards'],
      'subtitle' => ['es' => 'Explora las cartas del juego', 'en' => 'Explore the game cards'],
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
</x-public-layout>