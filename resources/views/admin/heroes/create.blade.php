<x-admin-layout
  title='{{ __("heroes.new") }}'
  headerTitle='{{ __("heroes.create") }}'
  containerTitle='{{ __("heroes.title") }}'
  subtitle='{{ __("heroes.create_subtitle") }}'
  :backRoute="route('admin.heroes.index')"
>

  <x-admin.forms.hero-form 
    :factions="$factions"
    :heroRaces="$heroRaces"
    :heroClasses="$heroClasses"
    :attributesConfig="$attributesConfig"
    :abilities="$abilities"
    :submitLabel="__('common.actions.create_entity', ['entity' => __('heroes.singular')])" 
    :cancelRoute="route('admin.heroes.index')" 
  />

</x-admin-layout>