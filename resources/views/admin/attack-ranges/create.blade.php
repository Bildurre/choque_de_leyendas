<x-admin-layout
  title='Nuevo Rango de Ataque'
  headerTitle='Crear Rango de Ataque'
  containerTitle='Rangos de Ataque'
  subtitle='Crea un nuevo rango para los ataques y habilidades'
  :backRoute="route('admin.attack-ranges.index')"
>

  <x-forms.attack-range-form 
    submitLabel="Crear Rango" 
    :cancelRoute="route('admin.attack-ranges.index')" 
  />

</x-admin-layout>