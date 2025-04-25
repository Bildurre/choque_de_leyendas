<x-admin-layout
  title="{{ $card->name }}"
  headerTitle='Detalle de Carta'
  containerTitle="{{ $card->name }}"
  subtitle='Información detallada de la carta'
  :createRoute="route('admin.cards.create')"
  :backRoute="route('admin.cards.index')"
>

  <x-detail-card 
    :title="$card->name"
    :accentColor="$card->faction->color ?? '#3d3df5'"
    :model="$card"
    :editRoute="route('admin.cards.edit', $card)"
    :deleteRoute="route('admin.cards.destroy', $card)"
    confirmAttribute="name"
  >
    <x-slot:headerSlot>
      <x-previews.card-preview :card="$card" />
    </x-slot:headerSlot>

    <x-detail-section title="Información General">
      <x-info-grid :columns="3">
        <x-info-grid-item label="Facción" :value="$card->faction->name ?? 'Sin facción'" />
        <x-info-grid-item label="Tipo de Carta" :value="$card->cardType->name ?? 'No asignado'" />
        
        @if($card->equipmentType)
          <x-info-grid-item label="Tipo de Equipo" :value="$card->equipmentType->name" />
          
          @if($card->isWeapon())
            <x-info-grid-item label="Manos" :value="$card->hands" />
          @endif
        @endif
        
        @if($card->attackRange)
          <x-info-grid-item label="Rango de Ataque" :value="$card->attackRange->name" />
        @endif
        
        @if($card->attackSubtype)
          <x-info-grid-item label="Subtipo de Ataque" :value="$card->attackSubtype->name" />
          <x-info-grid-item label="Tipo de Ataque" :value="$card->attackSubtype->typeName" />
        @endif
        
        @if($card->blast)
          <x-info-grid-item label="Área" :value="'Sí'" />
        @endif
        
        @if($card->cost)
          <x-info-grid-item label="Coste">
            <x-cost-display :cost="$card->cost"/>
          </x-info-grid-item>
        @endif
        
        @if($card->heroAbility)
          <x-info-grid-item label="Habilidad" :value="$card->heroAbility->name" />
        @endif
      </x-info-grid>
    </x-detail-section>
    
    @if($card->effect)
      <x-detail-section title="Efecto">
        <x-detail-text :content="$card->effect" isHtml="true" />
      </x-detail-section>
    @endif
    
    @if($card->restriction)
      <x-detail-section title="Restricción">
        <x-detail-text :content="$card->restriction" isHtml="true" />
      </x-detail-section>
    @endif

    @if($card->image)
      <x-detail-section title="Imagen">
        <x-detail-image 
          :src="asset('storage/' . $card->image)" 
          :alt="$card->name" 
          size="lg"
        />
      </x-detail-section>
    @endif
  </x-detail-card>

</x-admin-layout>