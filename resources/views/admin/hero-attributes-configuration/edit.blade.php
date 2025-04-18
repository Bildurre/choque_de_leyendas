<x-admin-layout
  title='Editar Configuración de Atributos de Héroe'
  headerTitle='Editar Configuración'
  containerTitle='Atributos'
  subtitle="Modifica la configuración de los atributos de héroe"
  :backRoute="route('admin.dashboard')"
>

  <x-forms.hero-attributes-configuration-form
    :configuration="$configuration"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.dashboard')" 
  />

</x-admin-layout>