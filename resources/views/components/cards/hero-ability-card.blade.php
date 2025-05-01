@props([
  'ability',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.entity-card
  :borderColor="null"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="ability-name"
  :deleteConfirmValue="$ability->name"
  containerClass="ability-card"
  :title="$ability->name"
  :hasDetails="true"
>
  <div class="card-summary">
    <x-stat-item icon="heroes" :count="$ability->heroes_count ?? 0" label="héroe" />
  </div>
  
  <x-slot:details>

    <x-description wrapper>
      <x-description title="Coste:" row>
        <x-cost-display :cost="$ability->cost"/>  
      </x-description>
      <x-description title="Rango:" row>{{ $ability->range->name }}</x-description>
      <x-description title="Tipo:" row>{{ $ability->subtype->typeName ?? 'N/A' }}</x-description>
      <x-description title="Subtipo:" row>{{ $ability->subtype->name }}</x-description>
      <x-description title="Área:" row>{{ $ability->area ? "Área" : "Un Objetivo" }}</x-description>
    </x-description>    
    
    <x-description title="Descripción">{!! $ability->description !!}</x-description>
  </x-slot:details>
</x-cards.entity-card>