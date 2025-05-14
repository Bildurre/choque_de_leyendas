<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.equipment_types.create') }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.equipment-types._form', [
      'categories' => $categories
    ])
  </div>
</x-admin-layout>