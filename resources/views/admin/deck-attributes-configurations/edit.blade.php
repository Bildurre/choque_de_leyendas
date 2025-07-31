<x-admin-layout>
  <x-admin.page-header :title="__('entities.deck_attributes.edit')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.deck-attributes-configurations.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>

  <div class="page-content">
    @include('admin.deck-attributes-configurations._form', [
      'configuration' => $configuration,
      'gameModes' => $gameModes
    ])
  </div>
</x-admin-layout>