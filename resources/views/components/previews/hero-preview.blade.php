@props(['hero'])

<x-previews.base-preview
  :name="$hero->name"
  :background-image="$hero->image ? asset('storage/' . $hero->image) : ''"
  :headerTextLeft="($hero->race?->name . ' - ' ?? '') . ($hero->heroClass?->name ?? '')"
  :headerTextRight="$hero->heroClass?->heroSuperclass?->name ?? ''"
  :factionColor="$hero->faction?->color ?? '#3d3df5'"
  :factionTextIsDark="$hero->faction?->text_is_dark ?? true"
  :factionIcon="$hero->faction?->icon ? asset('storage/' . $hero->faction->icon) : ''"
  {{ $attributes }}
>
  <x-slot name="sideInfo">
    <div class="preview-hero-attributes">
      <div class="preview-hero-attribute">
        <x-attribute-icon type="agility" />
        <span class="preview-hero-attribute__value">{{ $hero->agility }}</span>
      </div>
      <div class="preview-hero-attribute">
        <x-attribute-icon type="mental" />
        <span class="preview-hero-attribute__value">{{ $hero->mental }}</span>
      </div>
      <div class="preview-hero-attribute">
        <x-attribute-icon type="will" />
        <span class="preview-hero-attribute__value">{{ $hero->will }}</span>
      </div>
      <div class="preview-hero-attribute">
        <x-attribute-icon type="strength" />
        <span class="preview-hero-attribute__value">{{ $hero->strength }}</span>
      </div>
      <div class="preview-hero-attribute">
        <x-attribute-icon type="armor" />
        <span class="preview-hero-attribute__value">{{ $hero->armor }}</span>
      </div>
      <div class="preview-hero-attribute preview-hero-attribute--health">
        <x-attribute-icon type="health" />
        <span class="preview-hero-attribute__value">{{ $hero->calculateHealth() }}</span>
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    @if($hero->heroClass && $hero->heroClass->passive)
      <div class="preview-hero-passive">
        <span class="preview-hero-passive__title">
          {{ $hero->heroClass?->name ? $hero->heroClass?->name . ': ' : '' }}
        </span>
        {!! $hero->heroClass->passive !!}
      </div>
    @endif

    @if($hero->passive_description)
      <div class="preview-hero-passive">
        <span class="preview-hero-passive__title">
          {{ $hero->passive_name ? $hero->passive_name . ': ' : '' }}
        </span>
        {!! $hero->passive_description !!}
      </div>
    @endif

    @if($hero->abilities->isNotEmpty())
        @foreach($hero->abilities as $ability)
          <div class="preview-hero-ability">
            <div class="preview-hero-ability__header">
              <h5 class="preview-hero-ability__name">{{ $ability->name }}</h5>
              <div class="preview-hero-ability__meta">
                <span class="preview-hero-ability__type">{{ $ability->subtype ? ($ability->subtype->type === 'physical' ? 'Físico' : 'Mágico') : 'Sin tipo' }} - </span>
                <span class="preview-hero-ability__subtype">{{ $ability->subtype?->name ?? 'Sin subtipo' }} - </span>
                <span class="preview-hero-ability__range">{{ $ability->range?->name ?? 'Sin rango' }}</span>
                @if($ability->blast)
                  <span class="preview-hero-ability__area"> - Área</span>
                @endif
              </div>
              <div class="preview-hero-ability__cost">
                <x-cost-display :cost="$ability->cost"/>
              </div>
            </div>
            <div class="preview-hero-ability__description">{!! $ability->description !!}</div>
          </div>
        @endforeach
    @endif
  </x-slot>
</x-previews.base-preview>