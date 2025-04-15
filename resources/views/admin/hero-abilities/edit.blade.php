<x-admin-layout
  title='Editar Habilidad de Héroe'
  headerTitle='Editar Habilidad'
  containerTitle='Habilidades'
  subtitle="Modifica los detalles de la habilidad {{ $heroAbility->name }}"
  :createRoute="route('admin.hero-abilities.create')"
  createLabel='+ Nueva'
  :backRoute="route('admin.hero-abilities.index')"
  backLabel="Volver"
>

  <x-forms.hero-ability-form 
    :heroAbility="$heroAbility"
    :ranges="$ranges"
    :types="$types"
    :subtypes="$subtypes"
    :selectedHeroes="$selectedHeroes"
    :isDefault="$isDefault"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.hero-abilities.index')" 
  />

</x-admin-layout>