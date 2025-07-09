<x-admin-layout>
  <x-admin.page-header :title="__('pages.blocks.edit', ['type' => __('pages.blocks.types.' . $block->type)])">
    <x-slot:actions>
      <x-button-link 
        :href="route('admin.pages.edit', $page)" 
        icon="arrow-left" 
        variant="secondary"
      >
        {{ __('pages.blocks.back_to_page') }}
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