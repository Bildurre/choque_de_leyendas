@props([
    'title' => null
])

<div class="effect-section">
    @if($title)
        <h3 class="effect-section__title">{{ $title }}</h3>
    @endif
    <div class="effect-content">
        {{ $slot }}
    </div>
</div>