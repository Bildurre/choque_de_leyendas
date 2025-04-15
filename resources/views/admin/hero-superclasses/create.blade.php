<x-admin-layout
  title='Nueva Superclase'
  headerTitle='Crear Superclase'
  containerTitle='Superclases'
  subtitle='Crea una nueva superclase'
  backLabel="⬅ Volver"
  :backRoute="route('admin.hero-superclasses.index')"
>

  <x-forms.hero-superclass-form 
    :submitLabel="'Crear Superclase'" 
    :cancelRoute="route('admin.hero-superclasses.index')" 
  />

</x-admin-layout>