<x-admin-layout>
  <x-admin.page-header :title="__('blocks.create', ['type' => __('blocks.types.' . $type)])">
    <x-slot:actions>
      <x-button-link 
        :href="route('admin.pages.edit', $page)" 
        icon="arrow-left" 
        variant="secondary"
      >
        {{ __('blocks.back_to_page') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.blocks._form', [
      'page' => $page,
      'type' => $type,
      'blockConfig' => $blockConfig
    ])
  </div>
</x-admin-layout>