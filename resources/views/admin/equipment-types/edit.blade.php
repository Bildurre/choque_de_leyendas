<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.equipment_types.edit') }}: {{ $equipmentType->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.equipment-types._form', [
      'equipmentType' => $equipmentType,
      'categories' => $categories
    ])
  </div>
</x-admin-layout>