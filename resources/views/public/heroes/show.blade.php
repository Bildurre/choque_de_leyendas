<x-public-layout>
  <div class="hero-detail">
    <div class="hero-detail__header">
      <h1 class="hero-detail__name">{{ $hero->name }}</h1>
      <p class="hero-detail__faction">
        <a href="{{ route('public.factions.show', $hero->faction) }}">
          {{ $hero->faction->name }}
        </a>
      </p>
    </div>

    <div class="hero-detail__content">
      <div class="hero-detail__preview">
        <x-previews.preview-image :entity="$hero" type="hero" />
      </div>
      
      <div class="hero-detail__info">
        <div class="hero-detail__section">
          <h2>{{ __('public.heroes.details') }}</h2>
          <dl class="hero-detail__list">
            <dt>{{ __('entities.hero_races.singular') }}</dt>
            <dd>{{ $hero->getGenderizedRaceName() }}</dd>
            
            <dt>{{ __('entities.hero_classes.singular') }}</dt>
            <dd>{{ $hero->getGenderizedClassName() }}</dd>
            
            <dt>{{ __('entities.hero_superclasses.singular') }}</dt>
            <dd>{{ $hero->getGenderizedSuperclassName() }}</dd>
          </dl>
        </div>

        @if($hero->lore_text)
          <div class="hero-detail__section">
            <h2>{{ __('public.heroes.lore') }}</h2>
            <div class="hero-detail__lore">
              {!! $hero->lore_text !!}
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</x-public-layout>