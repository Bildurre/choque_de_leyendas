@props([
  'href' => null,
  'route' => null,
  'method' => 'GET',
  'icon' => null,
  'variant' => 'primary',
  'size' => 'md',
  'confirmMessage' => null,
  'title' => ''
])

@php
  $baseClass = 'action-button';
  $variantClass = "action-button--$variant";
  $sizeClass = "action-button--$size";
  $isForm = $route && ($method !== 'GET');
  $buttonId = 'action-' . uniqid();
@endphp

@if($isForm)
  <form action="{{ $route }}" method="POST" class="action-button-form">
    @csrf
    @method($method)
    
    <button 
      type="submit" 
      id="{{ $buttonId }}"
      title="{{ $title }}"
      {{ $attributes->merge(['class' => "$baseClass $variantClass $sizeClass"]) }}
      @if($confirmMessage) data-confirm-message="{{ $confirmMessage }}" @endif
    >
      @if($icon)
        <x-icon :name="$icon" size="sm" class="action-button__icon" />
      @endif
    </button>
  </form>
@else
  <a 
    href="{{ $href ?? $route ?? '#' }}"
    title="{{ $title }}" 
    {{ $attributes->merge(['class' => "$baseClass $variantClass $sizeClass"]) }}
  >
    @if($icon)
      <x-icon :name="$icon" size="sm" class="action-button__icon" />
    @endif
  </a>
@endif