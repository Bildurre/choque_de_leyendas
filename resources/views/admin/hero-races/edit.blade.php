<x-admin-layout
  title='{{ __("hero_races.edit") }}'
  headerTitle='{{ __("hero_races.edit") }}'
  containerTitle='{{ __("hero_races.title") }}'
  subtitle="{{ __('hero_races.edit_subtitle_with_name', ['name' => $heroRace->name]) }}"
  :createRoute="route('admin.hero-races.create')"
  :backRoute="route('admin.hero-races.index')"
>

  <x-admin.forms.hero-race-form 
    :heroRace="$heroRace"
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.hero-races.index')" 
  />

</x-admin-layout>