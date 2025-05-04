<x-admin-layout
  title='{{ __("factions.new") }}'
  headerTitle='{{ __("factions.create") }}'
  containerTitle='{{ __("factions.title") }}'
  subtitle='{{ __("factions.create_subtitle") }}'
  :backRoute="route('admin.factions.index')"
>

  <x-admin.forms.faction-form 
    :submitLabel="__('common.actions.create_entity', ['entity' => __('factions.singular')])" 
    :cancelRoute="route('admin.factions.index')" 
  />

</x-admin-layout>