<x-admin-layout
  title="{{ $faction->name }}"
  headerTitle='Detalle de Facción'
  containerTitle="{{ $faction->name }}"
  subtitle='Información detallada de la facción'
  createLabel="+ Nueva"
  :createRoute="route('admin.factions.create')"
  backLabel="⬅ Volver"
  :backRoute="route('admin.factions.index')"
>


  <div class="faction-detail-card">
    <div class="faction-actions" style="border-color: {{ $faction->color }}">      
      <x-action-button 
        variant="edit" 
        :route="route('admin.factions.create', $faction)"
        icon="edit" 
      />
      
      <x-action-button 
        variant="delete" 
        :route="route('admin.factions.destroy', $faction)"
        method="DELETE" 
        icon="delete" 
        confirm="true" 
        :confirmAttribute="$faction->name"
        deleteConfirmValue='faction-name'
      />
    </div>
    
    <div class="faction-body">
      @if($faction->lore_text)
        <div class="faction-lore">
          <p>{{ $faction->lore_text }}</p>
        </div>
      @endif


      <div class="faction-info">
        <h3>Información General y Estadísticas</h3>
        <div class="faction-info-content">
          @if($faction->icon)
            <div class="faction-item">
              <div class="faction-icon-container">
                <img src="{{ asset('storage/' . $faction->icon) }}" alt="{{ $faction->name }}" class="faction-icon">
              </div>
              <span class="label">Icono</span>
            </div>
          @endif

          <div class="faction-item">
            <span class="value color-sample" style="background-color: {{ $faction->color }}"></span>
            <span class="label">Color</span>
          </div>
          
          <div class="faction-item">
            <span class="value">{{ $faction->heroes_count ?? 0 }}</span>
            <span class="label">{{ Str::plural('Héroe', $faction->heroes_count ?? 0) }}</span>
          </div>
          
          <div class="faction-item">
            <span class="value">{{ $faction->cards_count ?? 0 }}</span>
            <span class="label">{{ Str::plural('Carta', $faction->cards_count ?? 0) }}</span>
          </div>

        </div>
      </div>
    </div>
  </div>

</x-admin-layout>