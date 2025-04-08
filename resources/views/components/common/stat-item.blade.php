@props(['icon', 'count' => 0, 'label' => 'items', 'iconSize' => 16])

<div class="stat-item">
  <x-common.icon :name="$icon" :size="$iconSize" />
  <span>{{ $count }} {{ Str::plural($label, $count) }}</span>
</div>