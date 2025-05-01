<x-admin-layout
  title='{{ __("factions.edit") }}'
  headerTitle='{{ __("factions.edit") }}'
  containerTitle='{{ __("factions.title") }}'
  subtitle="{{ __('factions.edit_subtitle_with_name', ['name' => $faction->name]) }}"
  :createRoute="route('admin.factions.create')"
  :backRoute="route('admin.factions.index')"
>

  <x-forms.faction-form 
    :faction="$faction" 
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.factions.index')" 
  />

</x-admin-layout>