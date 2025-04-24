@props([
  'cardType',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="card-type-name"
  :deleteConfirmValue="$cardType->name"
  containerClass="card-type-card"
  :title="$cardType->name"
>
  <div class="card-summary">
    @if($cardType->heroSuperclass)
      <x-info-item label="Superclase: {{ $cardType->heroSuperclass->name }}" />
    @else
      <x-info-item label="Sin superclase asignada" />
    @endif
  </div>
</x-cards.entity-card>