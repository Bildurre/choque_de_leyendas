@props(['image' => null])

@if($image)
  @section('page_background', true)
  <div {{ $attributes->merge(['class' => 'page-background']) }} style="background-image: url('{{ $image }}');"></div>
@endif