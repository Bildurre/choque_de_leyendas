<x-admin-layout
  title='Editar Rango de Ataque'
  headerTitle='Editar Rango de Ataque'
  containerTitle='Rangos de Ataque'
  subtitle="Modifica los detalles del rango {{ $attackRange->name }}"
  :createRoute="route('admin.attack-ranges.create')"
  createLabel='+ Nueva'
  :backRoute="route('admin.attack-ranges.index')"
  backLabel="Volver"
>

  <x-forms.attack-range-form 
    :attackRange="$attackRange"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.attack-ranges.index')" 
  />

</x-admin-layout>