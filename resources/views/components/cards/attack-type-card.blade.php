<x-cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-type-name"
  :deleteConfirmValue="$type->name"
  containerClass="attack-type-card"
  :title="$type->name"
>
  <div class="card-summary">
    <x-stat-item icon="abilities" :count="$type->abilities_count ?? 0" label="habilidad" />
  </div>
</x-cards.entity-card>