@props([
  'id' => 'accordion-'.uniqid(),
  'isSidebar' => false
])

<div {{ $attributes->merge([
  'class' => 'accordion', 
  'id' => $id,
  'data-is-sidebar' => $isSidebar ? 'true' : 'false'
]) }}>
  {{ $slot }}
</div>