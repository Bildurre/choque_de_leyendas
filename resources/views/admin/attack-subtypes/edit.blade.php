<x-admin-layout
  title='Editar Subtipo de Ataque'
  headerTitle='Editar Subtipo de Ataque'
  containerTitle='Subtipos de Ataque'
  subtitle="Modifica los detalles del subtipo {{ $attackSubtype->name }}"
  :createRoute="route('admin.attack-subtypes.create')"
  :backRoute="route('admin.attack-subtypes.index')"
>

  <x-forms.attack-subtype-form 
    :attackSubtype="$attackSubtype"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.attack-subtypes.index')" 
  />

</x-admin-layout>