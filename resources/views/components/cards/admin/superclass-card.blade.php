@props([
  'superclass',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.admin.entity-card
  :borderColor="$superclass->color"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="superclass-name"
  :deleteConfirmValue="$superclass->name"
  containerClass="superclass-card"
  :title="$superclass->name"
  :hasDetails="$superclass->description ? true : false"
>
  <div class="superclass-summary">
    <div class="stat-item-grid">
      <x-common.stat-item icon="heroes" :count="$superclass->hero_classes_count" label="clase" />
    </div>
  </div>
  
  <x-slot:details>
    @if($superclass->description)
      <x-common.description-section title="Descripción">
        <p>{{ $superclass->description }}</p>
      </x-common.description-section>
    @endif
  </x-slot:details>
</x-cards.admin.entity-card>