@props([
  'wrapper' => false,
  'title' => null,
  'row' => false
])

@if ($wrapper)
  <div class="description__wrapper">
    {{ $slot }}
  </div>
@else
  <div class="description @if($row)description--row @endif">
    @if ($title)
      <h3>{{ $title }}</h3>
    @endif
    <div class="description__content" >
      {{ $slot }}
    </div>
  </div>
@endif