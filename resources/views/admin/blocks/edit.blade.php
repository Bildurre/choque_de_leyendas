<x-admin-layout>
  <x-admin.page-header :title="__('blocks.edit', ['type' => __('blocks.types.' . $block->type)])">
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
      'block' => $block,
      'blockConfig' => $blockConfig
    ])
  </div>
</x-admin-layout>