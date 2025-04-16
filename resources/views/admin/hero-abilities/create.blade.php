<x-admin-layout
  title='Nueva Habilidad de Héroe'
  headerTitle='Crear Habilidad'
  containerTitle='Habilidades'
  subtitle='Crea una nueva habilidad para héroes'
  backLabel="⬅ Volver"
  :backRoute="route('admin.hero-abilities.index')"
>

  <x-forms.hero-ability-form 
    :ranges="$ranges"
    :subtypes="$subtypes"
    :selectedHeroes="[]"
    :isDefault="false"
    :submitLabel="'Crear Habilidad'" 
    :cancelRoute="route('admin.hero-abilities.index')" 
  />

</x-admin-layout>