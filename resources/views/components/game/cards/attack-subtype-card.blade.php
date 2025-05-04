<x-cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-subtype-name"
  :deleteConfirmValue="$subtype->name"
  containerClass="attack-subtype-card"
  :title="$subtype->name"
>
  <div class="card-summary">
    <x-badge variant="icon" :color="$subtype->type === 'physical' ? '#7a5a48' : '#5f5fb3'">
      {{ $subtype->type === 'physical' ? 'F' : 'M' }}
    </x-badge>
    <x-info-item label="{{ $subtype->typeName }}" />
    <x-stat-item icon="abilities" :count="$subtype->abilities_count ?? 0" label="habilidad" />
  </div>
</x-cards.entity-card>