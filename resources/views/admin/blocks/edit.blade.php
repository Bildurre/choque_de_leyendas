<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('blocks.edit', ['type' => __('blocks.types.' . $block->type)]) }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.blocks._form', [
      'page' => $page,
      'block' => $block,
      'blockConfig' => $blockConfig
    ])
  </div>
</x-admin-layout>