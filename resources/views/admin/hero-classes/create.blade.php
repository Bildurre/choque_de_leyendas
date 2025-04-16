<x-admin-layout
  title='Crear Clase'
  headerTitle='Crear Clase de Héroe'
  containerTitle='Clases'
  subtitle='Crea los detalles de una nueva clase'
  :backRoute="route('admin.hero-classes.index')"
>

  <x-forms.hero-class-form 
    :heroSuperclasses="$heroSuperclasses"
    :submitLabel="'Crear Clase'" 
    :cancelRoute="route('admin.hero-classes.index')" 
  />

</x-admin-layout>