@props([
  'heroSuperclass',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.admin.entity-card
  :borderColor="$heroSuperclass->color"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="hero-superclass-name"
  :deleteConfirmValue="$heroSuperclass->name"
  containerClass="hero-superclass-card"
  :title="$heroSuperclass->name"
>
  <div class="hero-superclass-summary">
    <div class="stat-item-grid">
      <x-common.stat-item icon="heroes" :count="$heroSuperclass->hero_classes_count" label="clase" />
    </div>
  </div>
</x-cards.admin.entity-card>