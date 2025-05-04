<x-admin-layout
  title='{{ __("hero_attributes.edit") }}'
  headerTitle='{{ __("hero_attributes.configuration") }}'
  containerTitle='{{ __("hero_attributes.title") }}'
  subtitle="{{ __('hero_attributes.configuration') }}"
  :backRoute="route('admin.dashboard')"
>

  <x-admin.forms.hero-attributes-configurations-form
    :configuration="$configuration"
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.dashboard')" 
  />

</x-admin-layout>