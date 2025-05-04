<x-admin-layout
  title='{{ __("heroes.edit") }}'
  headerTitle='{{ __("heroes.edit") }}'
  containerTitle='{{ __("heroes.title") }}'
  subtitle="{{ __('heroes.edit_subtitle_with_name', ['name' => $hero->name]) }}"
  :createRoute="route('admin.heroes.create')"
  :backRoute="route('admin.heroes.index')"
>

  <x-admin.forms.hero-form 
    :hero="$hero"
    :factions="$factions"
    :heroRaces="$heroRaces"
    :heroClasses="$heroClasses"
    :attributesConfig="$attributesConfig"
    :abilities="$abilities"
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.heroes.index')" 
  />

</x-admin-layout>