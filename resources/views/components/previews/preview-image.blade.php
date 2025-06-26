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

{{-- Factions and Decks are always rendered as components, never as images --}}
@if($type === 'faction')
  <x-previews.faction :faction="$entity" />
@elseif($type === 'deck')
  <x-previews.deck :deck="$entity" />
@else
  {{-- Heroes and Cards can have generated preview images --}}
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
    <span>No Image Available</span>
  @endif
@endif