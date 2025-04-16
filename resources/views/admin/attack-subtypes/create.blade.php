<x-admin-layout
  title='Nuevo Subtipo de Ataque'
  headerTitle='Crear Subtipo de Ataque'
  containerTitle='Subtipos de Ataque'
  subtitle='Crea un nuevo subtipo para categorizar ataques y habilidades'
  :backRoute="route('admin.attack-subtypes.index')"
>

  <x-forms.attack-subtype-form 
    :submitLabel="'Crear Subtipo'" 
    :cancelRoute="route('admin.attack-subtypes.index')" 
  />

</x-admin-layout>