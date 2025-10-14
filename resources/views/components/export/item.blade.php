@props([
  'filename',
  'size',
  'date',
  'downloadRoute',
  'deleteRoute',
])

@php
  $isZip = str_ends_with(strtolower($filename), '.zip');
  $icon = $isZip ? 'file-archive' : 'database';
@endphp

<div class="export-item export-item--admin">
  <div class="export-item__status">
    <x-badge variant="success">
      <x-icon :name="$icon" size="md" />
    </x-badge>
  </div>

  <div class="export-item__header">
    <h3 class="export-item__title">{{ $filename }}</h3>
    
    <div class="export-item__info">
      <span class="export-item__size">{{ $size }}</span>
      <span class="export-item__separator">•</span>
      <span class="export-item__date">{{ $date }}</span>
    </div>
  </div>
  
  <div class="export-item__actions">
    <x-action-button
      :href="$downloadRoute"
      variant="view"
      size="sm"
      icon="download"
    />
    
    <x-action-button
      :route="$deleteRoute"
      method="DELETE"
      variant="delete"
      size="sm"
      icon="trash"
      :confirmMessage="__('export.confirm_delete_single')"
    />
  </div>
</div>