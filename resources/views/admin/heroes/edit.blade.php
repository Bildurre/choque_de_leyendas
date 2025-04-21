<x-admin-layout
  title='Editar Héroe'
  headerTitle='Editar Héroe'
  containerTitle='Héroes'
  subtitle="Modifica los detalles del héroe {{ $hero->name }}"
  :createRoute="route('admin.heroes.create')"
  :backRoute="route('admin.heroes.index')"
>

  <x-forms.hero-form 
    :hero="$hero"
    :factions="$factions"
    :heroRaces="$heroRaces"
    :heroClasses="$heroClasses"
    :attributesConfig="$attributesConfig"
    :abilities="$abilities"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.heroes.index')" 
  />

</x-admin-layout>