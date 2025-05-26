@props([
  'entity',
  'type' => 'card',
  'locale' => null,
  'class' => '',
  'alt' => null
])

@php
  $locale = $locale ?? app()->getLocale();
  $altText = $alt ?? $entity->name;
  $componentClass = $type . '-preview-image';
@endphp

@if($type === 'faction')
  <x-previews.faction :faction="$entity" />
@else
  @php
    $imageUrl = $entity->getPreviewImageUrl($locale);
  @endphp
  
  @if($imageUrl && $entity->hasPreviewImage($locale))
    <img 
      src="{{ $imageUrl }}" 
      alt="{{ $altText }}"
      class="preview-image {{ $componentClass }} {{ $class }}"
      loading="lazy"
    />
  @else
    @if($type === 'hero')
      <x-previews.hero :hero="$entity" />
    @elseif($type === 'card')
      <x-previews.card :card="$entity" />
    @endif
  @endif
@endif