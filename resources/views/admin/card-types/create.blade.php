<x-admin-layout
  title='Nuevo Tipo de Carta'
  headerTitle='Crear Tipo de Carta'
  containerTitle='Tipos de Carta'
  subtitle='Crea un nuevo tipo de carta para el juego'
  :backRoute="route('admin.card-types.index')"
>

  <x-forms.card-type-form 
    :availableSuperclasses="$availableSuperclasses"
    :submitLabel="'Crear Tipo de Carta'" 
    :cancelRoute="route('admin.card-types.index')" 
  />

</x-admin-layout>