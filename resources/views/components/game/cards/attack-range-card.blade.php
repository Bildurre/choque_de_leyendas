@props([
  'range',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-range-name"
  :deleteConfirmValue="$range->name"
  containerClass="attack-range-card"
  :title="$range->name"
>
  <x-slot:badge>
    @if($range->icon)
      <x-badge variant="icon">
        <img src="{{ asset('storage/' . $range->icon) }}" alt="{{ $range->name }}">
      </x-badge>
    @else
      <x-badge variant="icon">
        {{ strtoupper(substr($range->name, 0, 1)) }}
      </x-badge>
    @endif
  </x-slot:badge>

  <div class="card-summary">
    <x-stat-item icon="abilities" :count="$range->abilities_count ?? 0" label="habilidad" />
  </div>
</x-cards.entity-card>