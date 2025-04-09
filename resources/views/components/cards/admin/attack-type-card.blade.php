<x-cards.admin.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-type-name"
  :deleteConfirmValue="$type->name"
  containerClass="attack-type-card"
  :title="$type->name"
  :hasDetails="$type->description || $type->subtypes_count > 0"
>
  <div class="type-summary">
    <div class="stat-item-grid">
      <x-common.stat-item icon="subtypes" :count="$type->subtypes_count ?? 0" label="subtipo" />
    </div>
  </div>
</x-cards.admin.entity-card>