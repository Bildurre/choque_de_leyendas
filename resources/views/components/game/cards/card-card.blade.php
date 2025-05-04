@props([
  'card',
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null
])

<x-game.cards.entity-card
  :borderColor="$card->faction->color ?? null"
  :showRoute="$showRoute"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="card-name"
  :deleteConfirmValue="$card->name"
  containerClass="card-card"
  :title="$card->name"
  :hasDetails="true"
>
  <x-slot:badge>
    @if($card->image)
      <x-core.badge variant="icon">
        <img src="{{ asset('storage/' . $card->image) }}" alt="{{ $card->name }}">
      </x-core.badge>
    @else
      <x-core.badge variant="icon" :color="$card->faction->color ?? '#3d3df5'">
        {{ strtoupper(substr($card->name, 0, 1)) }}
      </x-core.badge>
    @endif
  </x-slot:badge>

  <div class="card-summary">
    @if($card->cost)
      <x-game.cost-display :cost="$card->cost"/>
    @endif
    <x-core.info-item label="{{ $card->cardType->name ?? 'Sin tipo' }}" />
    @if($card->equipmentType)
      <x-core.info-item label="{{ $card->equipmentType->name }}" />
    @endif
  </div>
  
  <x-slot:details>
    @if($card->effect)
      <x-core.description title="Efecto">
        <div>{!! $card->effect !!}</div>
      </x-core.description>
    @endif
    
    @if($card->restriction)
      <x-core.description title="Restricción">
        <div>{!! $card->restriction !!}</div>
      </x-core.description>
    @endif
  </x-slot:details>
</x-game.cards.entity-card>