@props([
    'type' => 'agility', // agility, mental, will, strength, armor, health
    'value' => 0
])

<div class="attribute-item">
    <x-icon-attribute :type="$type" />
    <span class="attribute-label">{{ __('entities.heroes.attributes.' . $type) }}</span>
    <span class="attribute-value">{{ $value }}</span>
</div>