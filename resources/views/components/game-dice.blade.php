@props([
  'color1' => null,
  'color2' => null, 
  'color3' => null,
  'stroke' => "#000000",
  'variant' => 'solid',
  'animation' => 'static',
  'size' => 'md'

])

@php
// Define default colors if not provided
$color1 = $color1 ?? '#f53d3d'; // Default red
$color2 = $color2 ?? '#3df53d'; // Default green
$color3 = $color3 ?? '#3d3df5'; // Default blue

// Handle different variants
switch ($variant) {
  case 'outline':
    $facesFill = ['none', 'none', 'none'];
    break;
  case 'mono-red':
    $facesFill = [$color1, $color1, $color1];
    break;
  case 'mono-green':
    $facesFill = [$color2, $color2, $color2];
    break;
  case 'mono-blue':
    $facesFill = [$color3, $color3, $color3];
    break;
  case 'red-blue':
    $facesFill = [$color1, $color3, $color1];
    break;
  case 'green-blue':
    $facesFill = [$color2, $color3, $color2];
    break;
  case 'red-green':
    $facesFill = [$color1, $color2, $color1];
    break;
  default:
    $facesFill = [$color2, $color3, $color1];
    break;
}
@endphp

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" {{ $attributes->merge(['class' => 'dice__'.$animation . ' dice ' . 'dice__'.$size]) }}>
  {{-- Left face --}}
  <polygon 
    points="100,180 30,140 30,60 100,100" 
    fill="{{ $facesFill[0] }}" 
    stroke="{{ $stroke }}" 
    stroke-width="2"
    stroke-linejoin="round"
  />
  
  {{-- Right face --}}
  <polygon 
    points="100,180 100,100 170,60 170,140" 
    fill="{{ $facesFill[1] }}" 
    stroke="{{ $stroke }}" 
    stroke-width="2"
    stroke-linejoin="round"
  />
  
  {{-- Top face --}}
  @if($variant == 'outline' || in_array($variant, ['solid', 'mono-red', 'mono-green', 'mono-blue', 'tricolor']))
    <polygon 
      points="100,100 30,60 100,20 170,60" 
      fill="{{ $facesFill[2] }}" 
      stroke="{{ $stroke }}" 
      stroke-width="2"
      stroke-linejoin="round"
    />
  @else
    {{-- Split top face for two-color variants --}}
    <polygon 
      points="100,100 30,60 100,20" 
      fill="{{ $facesFill[0] }}" 
      stroke="{{ $stroke }}" 
      stroke-width="2"
      stroke-linejoin="round"
    />
    <polygon 
      points="100,100 100,20 170,60" 
      fill="{{ $facesFill[1] }}" 
      stroke="{{ $stroke }}" 
      stroke-width="2"
      stroke-linejoin="round"
    />
  @endif
</svg>