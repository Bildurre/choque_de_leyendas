@props([
  'type' => 'admin', // 'admin' or 'public'
  'emptyMessage' => null,
  'emptyIcon' => 'alert-triangle',
  'class' => '',
])

<div class="pdf-list {{ $class }}">
  <div class="pdf-list__content">
    <div class="pdf-list__grid">
      {{ $slot }}
    </div>
    
    @if(isset($empty) && $empty)
      <div class="pdf-list__empty">
        <x-icon :name="$emptyIcon" class="pdf-list__empty-icon" />
        <p class="pdf-list__empty-text">
          {{ $emptyMessage ?? __('admin.no_results') }}
        </p>
      </div>
    @endif
  </div>
</div>