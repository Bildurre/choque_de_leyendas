<x-admin-layout
  title='Editar Tipo de Equipo'
  headerTitle='Editar Tipo de Equipo'
  containerTitle='Tipos de Equipo'
  subtitle="Modifica los detalles del tipo de equipo {{ $equipmentType->name }}"
  :createRoute="route('admin.equipment-types.create')"
  :backRoute="route('admin.equipment-types.index')"
>

  <x-forms.equipment-type-form 
    :equipmentType="$equipmentType"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.equipment-types.index')" 
  />

</x-admin-layout>