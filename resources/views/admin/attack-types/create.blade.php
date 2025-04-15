<x-admin-layout
  title='Nuevo Tipo de Ataque'
  headerTitle='Crear Tipo de Ataque'
  containerTitle='Tipos de Ataque'
  subtitle='Crea un nuevo tipo principal para categorizar ataques y habilidades'
  backLabel="Volver"
  :backRoute="route('admin.attack-types.index')"
>

  <x-forms.attack-type-form 
    :submitLabel="'Crear Tipo'" 
    :cancelRoute="route('admin.attack-types.index')" 
  />

</x-admin-layout>