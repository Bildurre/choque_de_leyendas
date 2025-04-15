<x-admin-layout
  title='Editar Clase de Héroe'
  headerTitle='Editar Clase de Héroee'
  containerTitle='Clasess'
  subtitle="Modifica los detalles de la clase {{ $heroClass->nam }}e"
  :createRoute="route('admin.hero-classes.create')"
  createLabel='+ Nueva'
  :backRoute="route('admin.hero-classes.index')"
  backLabel="Volver"
>

  <x-forms.hero-class-form 
    :heroClass="$heroClass"
    :heroSuperclasses="$heroSuperclasses"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.hero-classes.index')" 
  />

</x-admin-layout>