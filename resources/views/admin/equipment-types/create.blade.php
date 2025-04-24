<x-admin-layout
  title='Nuevo Tipo de Equipo'
  headerTitle='Crear Tipo de Equipo'
  containerTitle='Tipos de Equipo'
  subtitle='Crea un nuevo tipo de arma o armadura'
  :backRoute="route('admin.equipment-types.index')"
>

  <x-forms.equipment-type-form 
    :submitLabel="'Crear Tipo de Equipo'" 
    :cancelRoute="route('admin.equipment-types.index')" 
  />

</x-admin-layout>