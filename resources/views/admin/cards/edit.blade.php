<x-admin-layout
  title='Editar Carta'
  headerTitle='Editar Carta'
  containerTitle='Cartas'
  subtitle="Modifica los detalles de la carta {{ $card->name }}"
  :createRoute="route('admin.cards.create')"
  :backRoute="route('admin.cards.index')"
>

  <x-forms.card-form 
    :card="$card"
    :factions="$factions"
    :cardTypes="$cardTypes"
    :equipmentTypes="$equipmentTypes"
    :attackRanges="$attackRanges"
    :attackSubtypes="$attackSubtypes"
    :heroAbilities="$heroAbilities"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.cards.index')" 
  />

</x-admin-layout>