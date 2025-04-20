@props([
  'hero',
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.entity-card
  :borderColor="$hero->faction->color ?? null"
  :showRoute="$showRoute"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="hero-name"
  :deleteConfirmValue="$hero->name"
  containerClass="hero-card"
  :title="$hero->name"
  :hasDetails="true"
>
  <x-slot:badge>
    @if($hero->image)
      <x-badge variant="icon">
        <img src="{{ asset('storage/' . $hero->image) }}" alt="{{ $hero->name }}" class="hero-avatar">
      </x-badge>
    @else
      <x-badge variant="icon" :color="$hero->faction->color ?? '#444444'">
        {{ strtoupper(substr($hero->name, 0, 1)) }}
      </x-badge>
    @endif
  </x-slot:badge>

  <div class="card-summary">
    @if($hero->heroClass)
      <span class="hero-class">{{ $hero->heroClass->name }}</span>
    @endif
    @if($hero->race)
      <span class="hero-race">{{ $hero->race->name }}</span>
    @endif
  </div>
  
  <x-slot:details>
    <div class="hero-attributes">
      <div class="attribute">
        <span class="attribute-label">AG</span>
        <span class="attribute-value">{{ $hero->agility }}</span>
      </div>
      <div class="attribute">
        <span class="attribute-label">ME</span>
        <span class="attribute-value">{{ $hero->mental }}</span>
      </div>
      <div class="attribute">
        <span class="attribute-label">VO</span>
        <span class="attribute-value">{{ $hero->will }}</span>
      </div>
      <div class="attribute">
        <span class="attribute-label">FU</span>
        <span class="attribute-value">{{ $hero->strength }}</span>
      </div>
      <div class="attribute">
        <span class="attribute-label">AR</span>
        <span class="attribute-value">{{ $hero->armor }}</span>
      </div>
      <div class="attribute health">
        <span class="attribute-label">VIDA</span>
        <span class="attribute-value">{{ $hero->calculateHealth() }}</span>
      </div>
    </div>

    @if($hero->passive_name)
      <x-description title="Pasiva: {{ $hero->passive_name }}">
        <div>{!! $hero->passive_description !!}</div>
      </x-description>
    @endif
  </x-slot:details>
</x-cards.entity-card>