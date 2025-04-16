<x-admin-layout
  title='Editar Clase de Héroe'
  headerTitle='Editar Clase de Héroee'
  containerTitle='Clasess'
  subtitle="Modifica los detalles de la clase {{ $heroClass->nam }}e"
  :createRoute="route('admin.hero-classes.create')"
  :backRoute="route('admin.hero-classes.index')"
>

  <x-forms.hero-class-form 
    :heroClass="$heroClass"
    :heroSuperclasses="$heroSuperclasses"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.hero-classes.index')" 
  />

</x-admin-layout>