<x-cards.admin.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-subtype-name"
  :deleteConfirmValue="$subtype->name"
  containerClass="attack-subtype-card"
  :title="$subtype->name"
>
  <div class="card-summary">
    <div class="stat-item-grid">
      <x-common.stat-item icon="heroes" :count="$subtype->abilities_count ?? 0" label="{{ Str::plural('habilidad', $subtype->abilities_count ?? 0) }}" />
    </div>
  </div>
</x-cards.admin.entity-card>