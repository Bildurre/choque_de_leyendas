<x-admin-layout
  title='Nueva Carta'
  headerTitle='Crear Carta'
  containerTitle='Cartas'
  subtitle='Crea una nueva carta para el juego'
  :backRoute="route('admin.cards.index')"
>

  <x-forms.card-form 
    :factions="$factions"
    :cardTypes="$cardTypes"
    :equipmentTypes="$equipmentTypes"
    :attackRanges="$attackRanges"
    :attackSubtypes="$attackSubtypes"
    :heroAbilities="$heroAbilities"
    :submitLabel="'Crear Carta'" 
    :cancelRoute="route('admin.cards.index')" 
  />

</x-admin-layout>