<x-game.cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-subtype-name"
  :deleteConfirmValue="$subtype->name"
  containerClass="attack-subtype-card"
  :title="$subtype->name"
>
  <div class="card-summary">
    <x-core.badge variant="icon" :color="$subtype->type === 'physical' ? '#7a5a48' : '#5f5fb3'">
      {{ $subtype->type === 'physical' ? 'F' : 'M' }}
    </x-core.badge>
    <x-core.info-item label="{{ $subtype->typeName }}" />
    <x-core.stat-item icon="abilities" :count="$subtype->abilities_count ?? 0" label="habilidad" />
  </div>
</x-game.cards.entity-card>