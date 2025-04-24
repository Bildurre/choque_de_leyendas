<x-admin-layout
  title='Editar Tipo de Carta'
  headerTitle='Editar Tipo de Carta'
  containerTitle='Tipos de Carta'
  subtitle="Modifica los detalles del tipo de carta {{ $cardType->name }}"
  :createRoute="route('admin.card-types.create')"
  :backRoute="route('admin.card-types.index')"
>

  <x-forms.card-type-form 
    :cardType="$cardType"
    :availableSuperclasses="$availableSuperclasses"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.card-types.index')" 
  />

</x-admin-layout>