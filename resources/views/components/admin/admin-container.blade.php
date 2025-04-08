@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'admin-container']) }}>
  @if($title)
    <x-common.header-actions-bar 
      :title="$title"
      :subtitle="$subtitle"
      {{ $attributes->only('create_route', 'create_label', 'back_route', 'back_label') }}
    >
      {{ $actions ?? '' }}
    </x-common.header-actions-bar>
  @endif
  
  <x-common.session-alerts />
  
  {{ $slot }}
</div>