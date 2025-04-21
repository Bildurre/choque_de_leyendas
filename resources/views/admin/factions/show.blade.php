<x-admin-layout
  title="{{ $faction->name }}"
  headerTitle='Detalle de Facción'
  containerTitle="{{ $faction->name }}"
  subtitle='Información detallada de la facción'
  :createRoute="route('admin.factions.create')"
  :backRoute="route('admin.factions.index')"
>

  <x-detail-card 
    :title="$faction->name"
    :accentColor="$faction->color"
    :model="$faction"
    :editRoute="route('admin.factions.edit', $faction)"
    :deleteRoute="route('admin.factions.destroy', $faction)"
    confirmAttribute="name"
  >
    @if($faction->lore_text)
      <x-detail-section>
        <x-detail-text :content="$faction->lore_text" />
      </x-detail-section>
    @endif

    <x-detail-section title="Información General y Estadísticas">
      <x-info-grid :columns="4">
        @if($faction->icon)
          <div class="faction-item">
            <x-detail-image 
              :src="asset('storage/' . $faction->icon)" 
              :alt="$faction->name" 
              size="sm" 
            />
            <span class="label">Icono</span>
          </div>
        @endif
        
        <div class="faction-item">
          <x-color-sample :color="$faction->color" label="Color" />
        </div>
        
        <x-info-grid-item 
          label="{{ Str::plural('Héroe', $faction->heroes_count ?? 0) }}" 
          :value="$faction->heroes_count ?? 0" 
        />
        
        <x-info-grid-item 
          label="{{ Str::plural('Carta', $faction->cards_count ?? 0) }}" 
          :value="$faction->cards_count ?? 0" 
        />
      </x-info-grid>
    </x-detail-section>
    
  </x-detail-card>

</x-admin-layout>