@props([
  'entity',
  'type' => 'card',
  'class' => '',
  'faction'
])

@php
  $componentClass = $type . '-preview';
  $faction = $faction ?? $entity->faction;
@endphp

<article 
  {{ $attributes->merge(['class' => "entity-preview $componentClass $class"]) }}
  style="--faction-color: {{ $faction->color }}; --faction-color-rgb: {{ $faction->rgb_values }}; --faction-text: {{ $faction->text_is_dark ? '#000000' : '#ffffff' }}"
>
  <header class="entity-preview__header">
    <div class="entity-preview__title-container">
      {{ $header ?? '' }}
    </div>
    <div class="entity-preview__faction-logo">
      <img src="{{ $faction->getImageUrl() }}" alt="{{ $faction->name }}">
    </div>
  </header>

  <div class="entity-preview__image-container">
    <img class="entity-preview__image" src="{{ $entity->getImageUrl() }}" alt="{{ $entity->name }}">
  </div>
  
  {{ $attributes_section ?? '' }}

  <section class="entity-preview__abilities">
    {{ $abilities ?? '' }}
  </section>

  <footer class="entity-preview__footer">
    <x-logo-icon />
    <span class="logo__title">{{ __('common.game_title') }}: </span>
    <span class="logo__subtitle">{{ __('common.game_subtitle') }}</span>
  </footer>
</article>