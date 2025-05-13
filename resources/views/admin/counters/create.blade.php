<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('counters.create_with_type', ['type' => __('counters.types.' . $type)]) }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.counters._form', [
      'type' => $type,
      'types' => $types
    ])
  </div>
</x-admin-layout>