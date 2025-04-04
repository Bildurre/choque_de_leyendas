@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'admin-container']) }}>
  @if($title)
    <x-header-actions-bar 
      :title="$title"
      :subtitle="$subtitle"
      {{ $attributes->only('create_route', 'create_label', 'back_route', 'back_label') }}
    >
      {{ $actions ?? '' }}
    </x-header-actions-bar>
  @endif
  
  <x-session-alerts />
  
  {{ $slot }}
</div>