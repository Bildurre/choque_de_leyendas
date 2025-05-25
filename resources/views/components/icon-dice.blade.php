@props([
  'type' => 'red',
])

@php
  $types = [
    'red' => 'icon-dice--red',
    'green' => 'icon-dice--green',
    'blue' => 'icon-dice--blue',
    'red-green' => 'icon-dice--red-green',
    'red-blue' => 'icon-dice--red-blue',
    'green-blue' => 'icon-dice--green-blue',
    'red-green-blue' => 'icon-dice--red-green-blue',
  ];

  $typeClass = $types[$type] ?? $types['red'];
@endphp

<span {{ $attributes->merge(['class' => "icon-dice {$typeClass}"]) }}>
  @if(in_array($type, ['red-green', 'red-blue', 'green-blue']))
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="16 16 168 168" stroke-linejoin="round">
      <polygon 
        points="100,180 30,140 30,60 100,100" 
        class="icon-dice__face1"
        stroke="black" 
        stroke-width="2"
      />
      
      <polygon 
        points="100,180 100,100 170,60 170,140" 
        class="icon-dice__face2"
        stroke="black" 
        stroke-width="2"
      />
      
      <polygon 
        points="100,100 30,60 100,20" 
        class="icon-dice__face1"
        stroke="black" 
        stroke-width="2"
      />
      
      <polygon 
        points="100,100 100,20 170,60" 
        class="icon-dice__face2"
        stroke="black" 
        stroke-width="2"
      />
    </svg>
  @else
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="16 16 168 168" stroke-linejoin="round">
      <polygon 
        points="100,180 30,140 30,60 100,100" 
        class="icon-dice__face"
        stroke="black" 
        stroke-width="2"
      />
      
      <polygon 
        points="100,180 100,100 170,60 170,140" 
        class="icon-dice__face"
        stroke="black" 
        stroke-width="2"
      />
      
      <polygon 
        points="100,100 30,60 100,20 170,60" 
        class="icon-dice__face {{ $type === 'red-green-blue' ? 'icon-dice__face--top' : '' }}"
        stroke="black" 
        stroke-width="2"
      />
    </svg>
  @endif
</span>