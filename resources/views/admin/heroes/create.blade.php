<x-admin-layout
  title='Nuevo Héroe'
  headerTitle='Crear Héroe'
  containerTitle='Héroes'
  subtitle='Crea un nuevo héroe para el juego'
  :backRoute="route('admin.heroes.index')"
>

  <x-forms.hero-form 
    :factions="$factions"
    :heroRaces="$heroRaces"
    :heroClasses="$heroClasses"
    :attributesConfig="$attributesConfig"
    :submitLabel="'Crear Héroe'" 
    :cancelRoute="route('admin.heroes.index')" 
  />

</x-admin-layout>