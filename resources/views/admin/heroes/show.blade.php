<x-admin-layout
  title="{{ $hero->name }}"
  headerTitle='Detalle de Héroe'
  containerTitle="{{ $hero->name }}"
  subtitle='Información detallada del héroe'
  :createRoute="route('admin.heroes.create')"
  :backRoute="route('admin.heroes.index')"
>

  <x-detail-card 
    :title="$hero->name"
    :accentColor="$hero->faction->color"
    :model="$hero"
    :editRoute="route('admin.heroes.edit', $hero)"
    :deleteRoute="route('admin.heroes.destroy', $hero)"
    confirmAttribute="name"
  >
    <x-detail-section>
      @if($hero->image)
        <x-detail-image 
          :src="asset('storage/' . $hero->image)" 
          :alt="$hero->name" 
          size="lg"
        />
      @endif
      
      <x-previews.hero-preview :hero="$hero" />
      
    </x-detail-section>
    
    @if($hero->lore_text)
      <x-detail-section>
        <x-detail-text :content="$hero->lore_text" />
      </x-detail-section>
    @endif
    
    <x-detail-section title="Información General">
      <x-info-grid :columns="2">
        <x-info-grid-item label="Facción" :value="$hero->faction->name" />
        <x-info-grid-item label="Raza" :value="$hero->race->name ?? 'No asignada'" />
        <x-info-grid-item label="Clase" :value="$hero->heroClass->name ?? 'No asignada'" />
        <x-info-grid-item label="Superclase" :value="$hero->heroClass->heroSuperclass->name ?? 'No asignada'" />
        <x-info-grid-item label="Género" :value="$hero->gender == 'male' ? 'Masculino' : 'Femenino'" />
      </x-info-grid>
    </x-detail-section>
    
    <x-detail-section title="Atributos">
      <x-attributes-grid 
        :columns="5" 
        :showTotal="true" 
        :totalValue="$hero->getTotalAttributePoints()" 
        :showHealth="true" 
        :healthValue="$hero->calculateHealth()"
      >
        <x-attribute-item label="Agilidad" :value="$hero->agility" />
        <x-attribute-item label="Mental" :value="$hero->mental" />
        <x-attribute-item label="Voluntad" :value="$hero->will" />
        <x-attribute-item label="Fuerza" :value="$hero->strength" />
        <x-attribute-item label="Armadura" :value="$hero->armor" />
      </x-attributes-grid>
    </x-detail-section>
    
    @if($hero->passive_name)
      <x-detail-section title="Habilidad Pasiva: {{ $hero->passive_name }}">
        <x-detail-text :content="$hero->passive_description" isHtml="true" />
      </x-detail-section>
    @endif

    @if($hero->abilities->isNotEmpty())
      <x-detail-section title="Habilidades">
        <div class="hero-abilities-grid">
          @foreach($hero->abilities as $ability)
            <div class="hero-ability-item">
              <div class="ability-header">
                <div class="ability-cost">
                  <x-cost-display :cost="$ability->cost"/>
                </div>
                <h4 class="ability-name">{{ $ability->name }}</h4>
              </div>
              
              <div class="ability-meta">
                <span class="ability-type">{{ $ability->subtype->type->name ?? 'Sin tipo' }}</span>
                <span class="ability-subtype">{{ $ability->subtype->name ?? 'Sin subtipo' }}</span>
                <span class="ability-range">{{ $ability->range->name ?? 'Sin rango' }}</span>
                @if($ability->blast)
                  <span class="ability-area">Área</span>
                @endif
              </div>
              
              <div class="ability-description">
                {!! $ability->description !!}
              </div>
            </div>
          @endforeach
        </div>
      </x-detail-section>
    @endif

  </x-detail-card>

</x-admin-layout>