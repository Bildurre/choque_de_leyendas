<x-admin-layout
  title='{{ __("attack_subtypes.new") }}'
  headerTitle='{{ __("attack_subtypes.create") }}'
  containerTitle='{{ __("attack_subtypes.title") }}'
  subtitle='{{ __("attack_subtypes.create_subtitle") }}'
  :backRoute="route('admin.attack-subtypes.index')"
>

  <x-forms.attack-subtype-form 
    :submitLabel="__('common.actions.create_entity', ['entity' => __('attack_subtypes.singular')])" 
    :cancelRoute="route('admin.attack-subtypes.index')" 
  />

</x-admin-layout>