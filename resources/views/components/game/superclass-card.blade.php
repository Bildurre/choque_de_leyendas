@props([
  'superclass',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-entity-card
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
    <div class="stat-item">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
      <span>{{ $superclass->hero_classes_count }} {{ Str::plural('clase', $superclass->hero_classes_count) }}</span>
    </div>
  </div>
  
  <x-slot:details>
    @if($superclass->description)
      <div class="superclass-description">
        <h4>Descripción</h4>
        <p>{{ $superclass->description }}</p>
      </div>
    @endif
  </x-slot:details>
</x-entity-card>