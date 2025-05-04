@props([
  'cardType',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-game.cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="card-type-name"
  :deleteConfirmValue="$cardType->name"
  containerClass="card-type-card"
  :title="$cardType->name"
>
  <div class="card-summary">
    @if($cardType->heroSuperclass)
      <x-core.info-item label="Superclase: {{ $cardType->heroSuperclass->name }}" />
    @else
      <x-core.info-item label="Sin superclase asignada" />
    @endif
  </div>
</x-game.cards.entity-card>