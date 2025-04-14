<x-admin-layout
  title='Editar Facción'
  headerTitle='Editar Facción'
  containerTitle='Facciones'
  subtitle="Modifica los detalles de la facción {{ $faction->name }}"
  :createRoute="route('admin.factions.create')"
  createLabel='+ Nueva'
  :backRoute="route('admin.factions.index')"
  backLabel="Volver"
>

  <x-admin.forms.faction-form 
  :faction="$faction" 
  submitLabel="Guardar Cambios" 
  :cancelRoute="route('admin.factions.index')" 
  />

</x-admin-layout>