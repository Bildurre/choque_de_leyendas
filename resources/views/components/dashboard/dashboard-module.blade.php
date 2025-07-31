@props([
  'title' => '',
  'icon' => null,
  'class' => '',
])

<div class="dashboard-module {{ $class }}">
  <div class="dashboard-module__header">
    @if($icon)
      <div class="dashboard-module__icon">
        <x-icon :name="$icon" size="lg" />
      </div>
    @endif
    <h3 class="dashboard-module__title">{{ $title }}</h3>
  </div>
  <div class="dashboard-module__content">
    {{ $slot }}
  </div>
</div>