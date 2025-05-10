<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('hero_superclasses.edit') }}: {{ $heroSuperclass->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.hero-superclasses._form', [
      'heroSuperclass' => $heroSuperclass
    ])
  </div>
</x-admin-layout>