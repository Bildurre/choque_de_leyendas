@props([
    'title' => '',
    'class' => ''
])

<div class="info-block {{ $class }}">
    <h2 class="info-block__title">{{ __($title) }}</h2>
    {{ $slot }}
</div>