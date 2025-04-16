<x-admin-layout
  title='Editar Superclase'
  headerTitle='Editar Superclase'
  containerTitle='Superclases'
  subtitle="Modifica los detalles de la superclase {{ $heroSuperclass->name }}"
  :createRoute="route('admin.hero-superclasses.create')"
  :backRoute="route('admin.hero-superclasses.index')"
>

  <x-forms.hero-superclass-form 
    :heroSuperclass="$heroSuperclass" 
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.hero-superclasses.index')" 
  />

</x-admin-layout>