<x-admin-layout
  title='{{ __("attack_subtypes.edit") }}'
  headerTitle='{{ __("attack_subtypes.edit") }}'
  containerTitle='{{ __("attack_subtypes.title") }}'
  subtitle="{{ __('attack_subtypes.edit_subtitle_with_name', ['name' => $attackSubtype->name]) }}"
  :createRoute="route('admin.attack-subtypes.create')"
  :backRoute="route('admin.attack-subtypes.index')"
>

  <x-admin.forms.attack-subtype-form 
    :attackSubtype="$attackSubtype"
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.attack-subtypes.index')" 
  />

</x-admin-layout>