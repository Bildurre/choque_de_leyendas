@props([
  'equipmentType',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="equipment-type-name"
  :deleteConfirmValue="$equipmentType->name"
  containerClass="equipment-type-card"
  :title="$equipmentType->name"
>
  <div class="card-summary">
    <x-badge variant="icon" :color="$equipmentType->isWeapon() ? '#d86f30' : '#3073d8'">
      {{ $equipmentType->isWeapon() ? 'A' : 'D' }}
    </x-badge>
    <x-info-item label="{{ $equipmentType->isWeapon() ? 'Arma' : 'Armadura' }}" />
    <x-stat-item icon="cards" :count="$equipmentType->equipment_count ?? 0" label="equipo" />
  </div>
</x-cards.entity-card>