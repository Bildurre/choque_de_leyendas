@props(['modifiers' => []])

<div class="modifiers-container">
  @foreach($modifiers as $label => $value)
    @php
      $modifierClass = $value > 0 ? 'positive' : ($value < 0 ? 'negative' : 'neutral');
    @endphp
    <div class="modifier-item {{ $modifierClass }}">
      <span class="modifier-label">{{ $label }}</span>
      <span class="modifier-value">
        {{ $value > 0 ? '+' : '' }}{{ $value }}
      </span>
    </div>
  @endforeach
</div>