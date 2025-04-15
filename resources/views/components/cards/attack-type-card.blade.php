<x-cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-type-name"
  :deleteConfirmValue="$type->name"
  containerClass="attack-type-card"
  :title="$type->name"
>
  <div class="card-summary">
    <div class="stat-item-grid">
      <x-stat-item icon="subtypes" :count="$type->subtypes_count ?? 0" label="subtipo" />
    </div>
  </div>
</x-cards.entity-card>