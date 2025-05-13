<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('counters.edit', ['name' => $counter->name]) }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.counters._form', [
      'counter' => $counter,
      'types' => $types
    ])
  </div>
</x-admin-layout>