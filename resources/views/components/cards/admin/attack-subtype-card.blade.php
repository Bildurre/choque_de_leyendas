<x-cards.admin.entity-card
  :borderColor="$subtype->color"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-subtype-name"
  :deleteConfirmValue="$subtype->name"
  containerClass="attack-subtype-card"
  :title="$subtype->name"
  :hasDetails="$subtype->description ? true : false"
>
  <x-slot:badge>
    @if($subtype->type)
      <x-common.badge>{{ $subtype->type->name }}</x-common.badge>
    @endif
  </x-slot:badge>

  <div class="subtype-summary">
    <div class="stat-item-grid">
      <x-common.stat-item icon="heroes" :count="$subtype->abilities_count ?? 0" label="{{ Str::plural('habilidad', $subtype->abilities_count ?? 0) }}" />
    </div>
  </div>
  
  @if($subtype->description)
    <x-slot:details>
      <x-common.description-section title="Descripción">
        <p>{{ $subtype->description }}</p>
      </x-common.description-section>
    </x-slot:details>
  @endif
</x-cards.admin.entity-card>