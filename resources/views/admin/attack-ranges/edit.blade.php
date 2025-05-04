<x-admin-layout
  title='{{ __("attack_ranges.edit") }}'
  headerTitle='{{ __("attack_ranges.edit") }}'
  containerTitle='{{ __("attack_ranges.title") }}'
  subtitle="{{ __('attack_ranges.edit_subtitle_with_name', ['name' => $attackRange->name]) }}"
  :createRoute="route('admin.attack-ranges.create')"
  :backRoute="route('admin.attack-ranges.index')"
>

  <x-admin.forms.attack-range-form 
    :attackRange="$attackRange"
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.attack-ranges.index')" 
  />

</x-admin-layout>