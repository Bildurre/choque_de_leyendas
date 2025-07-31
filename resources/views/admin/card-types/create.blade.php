<x-admin-layout>
  <x-admin.page-header :title="__('entities.card_types.create')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.card-types.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.card-types._form', [
      'availableSuperclasses' => $availableSuperclasses
    ])
  </div>
</x-admin-layout>