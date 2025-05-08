<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('blocks.create', ['type' => __('blocks.types.' . $type)]) }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.blocks._form', [
      'page' => $page,
      'type' => $type,
      'blockConfig' => $blockConfig
    ])
  </div>
</x-admin-layout>