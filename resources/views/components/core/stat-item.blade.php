@props(['icon', 'count' => 0, 'label' => 'items', 'iconSize' => '1rem'])

<div class="stat-item">
  <x-core.icon :name="$icon" :size="$iconSize" />
  <span class="stat-item__count">{{ $count }}</span>
  <span class="stat-item__label">{{ Str::plural($label, $count) }}</span>
</div>