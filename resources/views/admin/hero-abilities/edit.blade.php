<x-admin-layout
  title='Editar Habilidad de Héroe'
  headerTitle='Editar Habilidad'
  containerTitle='Habilidades'
  subtitle="Modifica los detalles de la habilidad {{ $heroAbility->name }}"
  :createRoute="route('admin.hero-abilities.create')"
  :backRoute="route('admin.hero-abilities.index')"
>

  <x-forms.hero-ability-form 
    :heroAbility="$heroAbility"
    :ranges="$ranges"
    :subtypes="$subtypes"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.hero-abilities.index')" 
  />

</x-admin-layout>