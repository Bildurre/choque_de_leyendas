@props([
  'card',
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.entity-card
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
      <x-badge variant="icon">
        <img src="{{ asset('storage/' . $card->image) }}" alt="{{ $card->name }}">
      </x-badge>
    @else
      <x-badge variant="icon" :color="$card->faction->color ?? '#3d3df5'">
        {{ strtoupper(substr($card->name, 0, 1)) }}
      </x-badge>
    @endif
  </x-slot:badge>

  <div class="card-summary">
    @if($card->cost)
      <x-cost-display :cost="$card->cost"/>
    @endif
    <x-info-item label="{{ $card->cardType->name ?? 'Sin tipo' }}" />
    @if($card->equipmentType)
      <x-info-item label="{{ $card->equipmentType->name }}" />
    @endif
  </div>
  
  <x-slot:details>
    @if($card->effect)
      <x-description title="Efecto">
        <div>{!! $card->effect !!}</div>
      </x-description>
    @endif
    
    @if($card->restriction)
      <x-description title="Restricción">
        <div>{!! $card->restriction !!}</div>
      </x-description>
    @endif
  </x-slot:details>
</x-cards.entity-card>