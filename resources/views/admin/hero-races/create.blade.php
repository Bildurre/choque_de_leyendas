<x-admin-layout
  title='Crear Raza'
  headerTitle='Crear Raza de Héroe'
  containerTitle='Razas'
  subtitle='Crea los detalles de una nueva raza'
  :backRoute="route('admin.hero-races.index')"
>

  <x-forms.hero-race-form 
    :submitLabel="'Crear Raza'" 
    :cancelRoute="route('admin.hero-races.index')" 
  />

</x-admin-layout>