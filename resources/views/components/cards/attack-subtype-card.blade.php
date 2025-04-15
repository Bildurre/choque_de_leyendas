<x-cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-subtype-name"
  :deleteConfirmValue="$subtype->name"
  containerClass="attack-subtype-card"
  :title="$subtype->name"
>
  <div class="card-summary">
    <x-stat-item icon="abilities" :count="$subtype->abilities_count ?? 0" label="habilidad" />
  </div>
</x-cards.entity-card>