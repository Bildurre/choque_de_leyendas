@php
  // Get icons from the icon component file
  $iconComponentPath = resource_path('views/components/icon.blade.php');
  $icons = [];
  
  if (File::exists($iconComponentPath)) {
    $content = File::get($iconComponentPath);
    
    // Extract all case statements from the switch
    preg_match_all("/@case\('([^']+)'\)/", $content, $matches);
    
    if (!empty($matches[1])) {
      $icons = $matches[1];
      // Remove 'default' if it exists
      $icons = array_filter($icons, function($icon) {
        return $icon !== 'default';
      });
      sort($icons);
    }
  }
@endphp

<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">Icons ({{ count($icons) }})</h1>
  </div>
  
  <div class="page-content">
    <div class="icons-page">
      <div class="icons-grid">
        @foreach($icons as $icon)
          <div class="icon-card">
            <div class="icon-card__preview">
              <x-icon name="{{ $icon }}" />
            </div>
            <div class="icon-card__name">{{ $icon }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  @push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/icons.css') }}">
  @endpush
</x-admin-layout>