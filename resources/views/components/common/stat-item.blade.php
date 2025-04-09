@props(['icon', 'count' => 0, 'label' => 'items', 'iconSize' => 16])

<div class="stat-item">
  <x-common.icon :name="$icon" :size="$iconSize" />
  <span class="stat-count">{{ $count }}</span>
  <span class="stat-label">{{ Str::plural($label, $count) }}</span>
</div>