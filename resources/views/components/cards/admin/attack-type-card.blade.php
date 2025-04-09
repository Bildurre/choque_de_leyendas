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
  
  <x-slot:details>
    @if($type->description)
      <x-common.description-section title="Descripción">
        <p>{{ $type->description }}</p>
      </x-common.description-section>
    @endif
    
    @if($type->subtypes_count > 0 && isset($type->subtypes))
      <div class="type-subtypes">
        <h4>Subtipos</h4>
        <div class="subtypes-list">
          @foreach($type->subtypes as $subtype)
            <div class="subtype-item">
              <span class="subtype-name">{{ $subtype->name }}</span>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </x-slot:details>
</x-cards.admin.entity-card>