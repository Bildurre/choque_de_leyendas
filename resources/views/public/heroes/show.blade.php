<x-public-layout>
    {{-- Page background image --}}
    @if($hero->hasImage())
        <x-page-background :image="$hero->getImageUrl()" />
    @endif

    {{-- Header Block con acciones --}}
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
                'text_alignment' => 'justify'
            ]
        ]);
    @endphp

    @component('content.blocks.header', ['block' => $headerBlock])
        @slot('actions')
            <x-pdf.add-button
                data-entity-type="hero"
                data-entity-id="{{ $hero->id }}"
            >
            </x-pdf.add-button>
        @endslot
    @endcomponent

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
                    <x-entity-show.info-block title="public.heroes.basic_info">
                        <x-entity-show.info-list>
                            <x-entity-show.info-list-item label="entities.heroes.name" :value="$hero->name" />
                            
                            <x-entity-show.info-list-item label="entities.factions.singular">
                                <x-entity-show.info-link :href="route('public.factions.show', $hero->faction)">
                                    {{ $hero->faction->name }}
                                </x-entity-show.info-link>
                            </x-entity-show.info-list-item>
                            
                            <x-entity-show.info-list-item label="entities.hero_races.singular">
                                <x-entity-show.info-link :href="route('public.heroes.index') . '?' . http_build_query(['hero_race_id' => [$hero->hero_race_id]])">
                                    {{ $hero->getGenderizedRaceName() }}
                                </x-entity-show.info-link>
                            </x-entity-show.info-list-item>
                            
                            <x-entity-show.info-list-item label="entities.heroes.gender" :value="__('entities.heroes.genders.' . $hero->gender)" />
                            
                            <x-entity-show.info-list-item label="entities.hero_classes.singular">
                                <x-entity-show.info-link :href="route('public.heroes.index') . '?' . http_build_query(['hero_class_id' => [$hero->hero_class_id]])">
                                    {{ $hero->getGenderizedClassName() }}
                                </x-entity-show.info-link>
                            </x-entity-show.info-list-item>
                            
                            <x-entity-show.info-list-item label="entities.hero_superclasses.singular">
                                <x-entity-show.info-link :href="route('public.heroes.index') . '?' . http_build_query(['heroClass_hero_superclass_id' => [$hero->heroClass->hero_superclass_id]])">
                                    {{ $hero->getGenderizedSuperclassName() }}
                                </x-entity-show.info-link>
                            </x-entity-show.info-list-item>
                        </x-entity-show.info-list>
                    </x-entity-show.info-block>

                    {{-- Attributes Block --}}
                    <x-entity-show.info-block title="public.heroes.attributes">
                        <div class="attributes-grid">
                            <x-entity-show.attribute-item type="agility" :value="$hero->agility" />
                            <x-entity-show.attribute-item type="mental" :value="$hero->mental" />
                            <x-entity-show.attribute-item type="will" :value="$hero->will" />
                            <x-entity-show.attribute-item type="strength" :value="$hero->strength" />
                            <x-entity-show.attribute-item type="armor" :value="$hero->armor" />
                            <x-entity-show.attribute-item type="health" :value="$hero->health" />
                        </div>
                    </x-entity-show.info-block>
                </div>

                {{-- Abilities Block --}}
                <x-entity-show.info-block title="public.heroes.abilities" class="info-block--abilities">
                    {{-- Passive Abilities --}}
                    <div class="abilities-section">
                        <h3 class="abilities-section__subtitle">{{ __('public.heroes.passive_abilities') }}</h3>
                        
                        {{-- Class Passive --}}
                        <x-entity-show.ability-card
                            variant="passive"
                            :name="$hero->heroClass->name"
                            :description="$hero->heroClass->passive"
                        />
                        
                        {{-- Hero Passive --}}
                        <x-entity-show.ability-card
                            variant="passive"
                            :name="$hero->passive_name"
                            :description="$hero->passive_description"
                        />
                    </div>
                    
                    {{-- Active Abilities --}}
                    @if($hero->heroAbilities->count() > 0)
                        <div class="abilities-section">
                            <h3 class="abilities-section__subtitle">{{ __('public.heroes.active_abilities') }}</h3>
                            
                            @foreach($hero->heroAbilities as $ability)
                                <x-entity-show.ability-card
                                    variant="active"
                                    :name="$ability->name"
                                    :description="$ability->description"
                                    :cost="$ability->cost"
                                    :attack-range="$ability->attackRange"
                                    :attack-subtype="$ability->attackSubtype"
                                    :area="$ability->area"
                                />
                            @endforeach
                        </div>
                    @endif
                </x-entity-show.info-block>
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