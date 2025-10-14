<x-admin-layout>
  <x-admin.page-header :title="__('entities.card_subtypes.create')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.card-subtypes.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.card-subtypes._form')
  </div>
</x-admin-layout>