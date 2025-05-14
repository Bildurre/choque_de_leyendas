<!-- resources/views/admin/attack-ranges/edit.blade.php -->
<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.attack_ranges.edit') }}: {{ $attackRange->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.attack-ranges._form', [
      'attackRange' => $attackRange
    ])
  </div>
</x-admin-layout>