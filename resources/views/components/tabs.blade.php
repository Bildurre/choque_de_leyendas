@props([
  'activeTab' => '',
  'align' => 'left'
])

<div {{ $attributes->merge(['class' => "tabs tabs--$align"]) }}>
  <div class="tabs__header">
    {{ $header }}
  </div>
  
  <div class="tabs__content">
    {{ $content }}
  </div>
</div>