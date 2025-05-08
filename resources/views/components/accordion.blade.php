@props(['id' => 'accordion-'.uniqid()])

<div {{ $attributes->merge(['class' => 'accordion', 'id' => $id]) }}>
    {{ $slot }}
</div>