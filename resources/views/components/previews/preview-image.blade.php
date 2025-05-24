@props([
  'entity',
  'type' => 'card',
  'locale' => null,
  'class' => '',
  'alt' => null
])

@php
  $locale = $locale ?? app()->getLocale();
  $imageUrl = $entity->getPreviewImageUrl($locale);
  $altText = $alt ?? $entity->name;
  $componentClass = $type . '-preview-image';
@endphp

@if($imageUrl && $entity->hasPreviewImage($locale))
  <img 
    src="{{ $imageUrl }}" 
    alt="{{ $altText }}"
    class="preview-image {{ $componentClass }} {{ $class }}"
    loading="lazy"
  />
@else
  {{-- Fallback to component if preview image doesn't exist --}}
  @if($type === 'hero')
    <x-previews.hero :hero="$entity" />
  @elseif($type === 'card')
    <x-previews.card :card="$entity" />
  @endif
@endif