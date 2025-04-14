<x-admin-layout
  title='Nueva Facción'
  headerTitle='Crear Facción'
  containerTitle='Facciones'
  subtitle='Crea una nueva facción para el juego'
  backLabel="Volver"
  :backRoute="route('admin.factions.index')"
>

  <x-admin.forms.faction-form 
  submitLabel="Crear Facción" 
  :cancelRoute="route('admin.factions.index')" 
  />

</x-admin-layout>