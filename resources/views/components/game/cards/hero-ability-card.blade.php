@props([
  'ability',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-game.cards.entity-card
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
    <x-core.stat-item icon="heroes" :count="$ability->heroes_count ?? 0" label="héroe" />
  </div>
  
  <x-slot:details>

    <x-core.description wrapper>
      <x-core.description title="Coste:" row>
        <x-game.cost-display :cost="$ability->cost"/>  
      </x-core.description>
      <x-core.description title="Rango:" row>{{ $ability->range->name }}</x-core.description>
      <x-core.description title="Tipo:" row>{{ $ability->subtype->typeName ?? 'N/A' }}</x-core.description>
      <x-core.description title="Subtipo:" row>{{ $ability->subtype->name }}</x-core.description>
      <x-core.description title="Área:" row>{{ $ability->area ? "Área" : "Un Objetivo" }}</x-core.description>
    </x-core.description>    
    
    <x-core.description title="Descripción">{!! $ability->description !!}</x-core.description>
  </x-slot:details>
</x-game.cards.entity-card>