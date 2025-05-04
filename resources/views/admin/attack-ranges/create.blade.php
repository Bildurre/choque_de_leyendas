<x-admin-layout
  title='{{ __("attack_ranges.new") }}'
  headerTitle='{{ __("attack_ranges.create") }}'
  containerTitle='{{ __("attack_ranges.title") }}'
  subtitle='{{ __("attack_ranges.create_subtitle") }}'
  :backRoute="route('admin.attack-ranges.index')"
>

  <x-admin.forms.attack-range-form 
    submitLabel="{{ __('common.actions.create_entity', ['entity' => __('attack_ranges.singular')]) }}" 
    :cancelRoute="route('admin.attack-ranges.index')" 
  />

</x-admin-layout>