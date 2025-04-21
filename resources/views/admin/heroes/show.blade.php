<x-admin-layout
  title="{{ $hero->name }}"
  headerTitle='Detalle de Héroe'
  containerTitle="{{ $hero->name }}"
  subtitle='Información detallada del héroe'
  :createRoute="route('admin.heroes.create')"
  :backRoute="route('admin.heroes.index')"
>

  <div class="hero-detail-card">
    <div class="hero-actions" style="border-color: {{ $hero->faction->color }}">      
      <x-action-button 
        variant="edit" 
        :route="route('admin.heroes.edit', $hero)"
        icon="edit" 
      />
      
      <x-action-button 
        variant="delete" 
        :route="route('admin.heroes.destroy', $hero)"
        method="DELETE" 
        icon="delete" 
        confirm="true" 
        :confirmAttribute="$hero->name"
        deleteConfirmValue='hero-name'
      />
    </div>
    
    <div class="hero-body">

      @if($hero->lore_text)
        <div class="hero-lore">
          <p>{{ $hero->lore_text }}</p>
        </div>
      @endif

      @if($hero->image)
        <div class="hero-image">
          <img src="{{ asset('storage/' . $hero->image) }}" alt="{{ $hero->name }}" class="hero-portrait">
        </div>
      @endif
      
      <div class="info-grid">
        <div class="info-item">
          <span class="label">Facción:</span>
          <span class="value">{{ $hero->faction->name }}</span>
        </div>
      
        <div class="info-item">
          <span class="label">Raza:</span>
          <span class="value">{{ $hero->race->name ?? 'No asignada' }}</span>
        </div>
        
        <div class="info-item">
          <span class="label">Clase:</span>
          <span class="value">{{ $hero->heroClass->name ?? 'No asignada' }}</span>
        </div>
        
        <div class="info-item">
          <span class="label">Superclase:</span>
          <span class="value">{{ $hero->heroClass->heroSuperclass->name ?? 'No asignada' }}</span>
        </div>
        
        <div class="info-item">
          <span class="label">Género:</span>
          <span class="value">{{ $hero->gender == 'male' ? 'Masculino' : 'Femenino' }}</span>
        </div>
      </div>
        
      <div class="attributes-grid">
        <div class="attribute-item">
          <span class="attribute-label">Agilidad</span>
          <span class="attribute-value">{{ $hero->agility }}</span>
        </div>
        <div class="attribute-item">
          <span class="attribute-label">Mental</span>
          <span class="attribute-value">{{ $hero->mental }}</span>
        </div>
        <div class="attribute-item">
          <span class="attribute-label">Voluntad</span>
          <span class="attribute-value">{{ $hero->will }}</span>
        </div>
        <div class="attribute-item">
          <span class="attribute-label">Fuerza</span>
          <span class="attribute-value">{{ $hero->strength }}</span>
        </div>
        <div class="attribute-item">
          <span class="attribute-label">Armadura</span>
          <span class="attribute-value">{{ $hero->armor }}</span>
        </div>
        <div class="attribute-item health">
          <span class="attribute-label">Vida</span>
          <span class="attribute-value">{{ $hero->calculateHealth() }}</span>
        </div>
        <div class="attribute-item total">
          <span class="attribute-label">Total</span>
          <span class="attribute-value">{{ $hero->getTotalAttributePoints() }}</span>
        </div>
      </div>
      
      @if($hero->passive_name)
        <div class="hero-section">
          <h3>Habilidad Pasiva: {{ $hero->passive_name }}</h3>
          <div class="hero-passive">
            {!! $hero->passive_description !!}
          </div>
        </div>
      @endif
      
    </div>
  </div>

</x-admin-layout>