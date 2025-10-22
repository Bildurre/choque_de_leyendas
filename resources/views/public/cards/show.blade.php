<x-public-layout
  :title="__('entities.cards.page_title', ['name' => $card->name])"
  :metaDescription="__('entities.cards.page_description', [
    'name' => $card->name,
    'faction' => $card->faction->name,
    'type' => $card->cardType->name,
    'description' => Str::limit(strip_tags($card->lore_text), 80)
  ])"
  ogType="article"
  :ogImage="$card->getImageUrl()"
>
    {{-- Page background image --}}
    @if($card->hasImage())
      <x-page-background :image="$card->getImageUrl()" />
    @endif

    {{-- Header Block con acciones --}}
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
        'background_color' => 'theme-border',
        'settings' => [
          'text_alignment' => 'left'
        ]
      ]);
    @endphp

    @component('content.blocks.header', ['block' => $headerBlock])
      @slot('actions')
        <x-pdf.add-button
          entityType="card"
          entityId="{{ $card->id }}"
          type="outlined"
        >
          {{ __('pdf.collection.add_button_title') }}
        </x-pdf.add-button>
      @endslot
    @endcomponent

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
            <x-entity-show.info-block title="public.cards.basic_info">
              <x-entity-show.info-list>
                <x-entity-show.info-list-item label="{{ __('entities.cards.name') }}" :value="$card->name" />
                  
                <x-entity-show.info-list-item label="{{ __('entities.factions.singular') }}">
                  <x-entity-show.info-link :href="route('public.factions.show', $card->faction)">
                    {{ $card->faction->name }}
                  </x-entity-show.info-link>
                </x-entity-show.info-list-item>

                @if ($card->is_unique)
                  <x-entity-show.info-list-item label="{{ __('entities.cards.is_unique') }}" value="{{ __('common.yes') }}" />
                @endif
                
                <x-entity-show.info-list-item label="{{ __('entities.card_types.singular') }}">
                  <x-entity-show.info-link :href="route('public.cards.index') . '?' . http_build_query(['card_type_id' => [$card->card_type_id]])">
                    {{ $card->cardType->name }}
                  </x-entity-show.info-link>
                </x-entity-show.info-list-item>

                @if($card->cardSubtype)
                  <x-entity-show.info-list-item label="{{ __('entities.hero_superclasses.singular') }}">
                    <x-entity-show.info-link :href="route('public.cards.index') . '?' . http_build_query(['card_subtype_id' => [$card->card_subtype_id]])">
                      {{ $card->cardSubtype->name }}
                    </x-entity-show.info-link>
                  </x-entity-show.info-list-item>
                @endif

                @if($card->cardType->heroSuperclass)
                  <x-entity-show.info-list-item label="{{ __('entities.hero_superclasses.singular') }}">
                    <x-entity-show.info-link :href="route('public.heroes.index') . '?' . http_build_query(['heroClass_hero_superclass_id' => [$card->cardType->hero_superclass_id]])">
                      {{ $card->cardType->heroSuperclass->name }}
                    </x-entity-show.info-link>
                  </x-entity-show.info-list-item>
                @endif
                  
                @if($card->cardType->id == 1 && $card->equipmentType) {{-- Equipment type --}}
                  <x-entity-show.info-list-item label="{{ __('entities.equipment_types.singular') }}">
                    <x-entity-show.info-link :href="route('public.cards.index') . '?' . http_build_query(['card_type_id' => [1], 'equipment_type_id' => [$card->equipment_type_id]])">
                      {{ $card->equipmentType->name }}
                    </x-entity-show.info-link>
                  </x-entity-show.info-list-item>
                    
                  @if($card->equipmentType->category == 'weapon' && $card->hands)
                    <x-entity-show.info-list-item label="{{ __('entities.cards.hands_required') }}">
                      {{ $card->hands }} 
                      {{ $card->hands > 1 ? __('entities.cards.hands') : __('entities.cards.hand') }}
                    </x-entity-show.info-list-item>
                  @endif
                @endif
                  
                @if($card->cost)
                  <x-entity-show.info-list-item label="{{ __('entities.cards.cost') }}">
                    <x-cost-display :cost="$card->cost" />
                  </x-entity-show.info-list-item>
                @endif
              </x-entity-show.info-list>
            </x-entity-show.info-block>

            {{-- Attack Info Block --}}
            @if($card->attackRange || $card->attackSubtype)
              <x-entity-show.info-block title="public.cards.attack_info">
                <x-entity-show.info-list>
                  @if($card->attackRange)
                    <x-entity-show.info-list-item label="{{ __('entities.attack_ranges.singular') }}">
                      <x-entity-show.info-link :href="route('public.cards.index') . '?' . http_build_query(['card_type_id' => [4, 5, 6, 7], 'attack_range_id' => [$card->attack_range_id]])">
                        {{ $card->attackRange->name }}
                      </x-entity-show.info-link>
                    </x-entity-show.info-list-item>
                  @endif

                  @if($card->attack_type)
                    <x-entity-show.info-list-item label="{{ __('entities.attack_subtypes.type') }}">
                      <x-entity-show.info-link :href="route('public.cards.index') . '?' . http_build_query(['card_type_id' => [4, 5, 6, 7], 'attackSubtype_type' => $card->attackSubtype->type])">
                        {{ __('entities.attack_subtypes.types.' . $card->attack_type) }}
                      </x-entity-show.info-link>
                    </x-entity-show.info-list-item>
                  @endif
                    
                  @if($card->attackSubtype)
                    <x-entity-show.info-list-item label="{{ __('entities.attack_subtypes.singular') }}">
                      <x-entity-show.info-link :href="route('public.cards.index') . '?' . http_build_query(['card_type_id' => [4, 5, 6, 7], 'attack_subtype_id' => [$card->attack_subtype_id]])">
                        {{ $card->attackSubtype->name }}
                      </x-entity-show.info-link>
                    </x-entity-show.info-list-item>
                  @endif
                  
                  @if($card->area)
                    <x-entity-show.info-list-item label="{{ __('entities.cards.area') }}">
                      <x-entity-show.info-link :href="route('public.cards.index') . '?' . http_build_query(['card_type_id' => [4, 5, 6, 7], 'area' => 1])">
                        {{ __('common.yes') }}
                      </x-entity-show.info-link>
                    </x-entity-show.info-list-item>
                  @endif
                </x-entity-show.info-list>
              </x-entity-show.info-block>
            @endif

            {{-- Effects Block --}}
            @if($card->restriction || $card->effect || $card->heroAbility)
              <x-entity-show.info-block title="public.cards.effects">
                @if($card->restriction)
                  <x-entity-show.effect-section>
                    {!! $card->restriction !!}
                  </x-entity-show.effect-section>
                @endif
                
                @if($card->effect)
                  <x-entity-show.effect-section>
                    {!! $card->effect !!}
                  </x-entity-show.effect-section>
                @endif
                
                @if($card->heroAbility)
                  <x-entity-show.effect-section :title="__('entities.hero_abilities.singular')">
                    <x-entity-show.ability-card
                      variant="active"
                      :name="$card->heroAbility->name"
                      :description="$card->heroAbility->description"
                      :cost="$card->heroAbility->cost"
                      :attack-range="$card->heroAbility->attackRange"
                      :attack-type="$card->heroAbility->attack_type"
                      :attack-subtype="$card->heroAbility->attackSubtype"
                      :area="$card->heroAbility->area"
                    />
                  </x-entity-show.effect-section>
                @endif
              </x-entity-show.info-block>
            @endif
          </div>
        </div>
      </div>
    </section>

    {{-- epic quote --}}
    @php
      $epicText = new \App\Models\Block([
        'type' => 'quote',
        'title' => null,
        'subtitle' => [
          'es' => "<p>" . $card->getTranslation('epic_quote', 'es') . "</p>", 
          'en' => "<p>" . $card->getTranslation('epic_quote', 'en') . "</p>"
        ],
        'background_color' => 'theme-border',
        'content' => null,
        'settings' => [
          'text_alignment' => 'center'
        ]
      ]);
    @endphp
    {!! $epicText->render() !!}

    {{-- Related Cards Block --}}
    @php
      $relatedCardsBlock = new \App\Models\Block([
        'type' => 'relateds',
        'title' => null,
        'subtitle' => ['es' => 'Descubre más cartas del juego', 'en' => 'Discover more game cards'],
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
          'text_alignment' => 'left',
          'button_size' => 'md',
          'button_variant' => 'secondary',
        ]
      ]);
    @endphp
    {!! $relatedCardsBlock->render() !!}
</x-public-layout>