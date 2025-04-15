<x-admin-layout
  title='Editar Tipo de Ataque'
  headerTitle='Editar Tipo de Ataque'
  containerTitle='Tipos de Ataques'
  subtitle="Modifica los detalles del tipo {{ $attackType->name }}"
  :createRoute="route('admin.attack-types.create')"
  createLabel='+ Nueva'
  :backRoute="route('admin.attack-types.index')"
  backLabel="⬅ Volver"
>

  <x-forms.attack-type-form 
    :attackType="$attackType"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.attack-types.index')" 
  />

</x-admin-layout>