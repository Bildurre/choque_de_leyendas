<x-admin-layout
  title='Editar Raza de Héroe'
  headerTitle='Editar Raza de Héroe'
  containerTitle='Razas'
  subtitle="Modifica los detalles de la raza {{ $heroRace->name }}"
  :createRoute="route('admin.hero-races.create')"
  :backRoute="route('admin.hero-races.index')"
>

  <x-forms.hero-race-form 
    :heroRace="$heroRace"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.hero-races.index')" 
  />

</x-admin-layout>