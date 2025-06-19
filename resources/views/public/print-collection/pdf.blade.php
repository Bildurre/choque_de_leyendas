<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <title>Alanda Cards - {{ date('Y-m-d') }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    @page {
      size: A4;
      margin: 0; /* Quitamos el margen de @page para controlarlo nosotros */
    }
    
    body {
      margin: 0;
      padding: 13mm 7mm; /* vertical: 13mm, horizontal: 7mm */
      padding-right: 4mm;
      background: white;
      font-size: 0; /* Eliminar espacios entre inline-blocks */
    }
    
    /* Contenedor principal */
    .container {
      width: 100%;
      line-height: 0;
    }
    
    /* Items con inline-block */
    .card, .hero {
      display: inline-block;
      vertical-align: top;
      page-break-inside: avoid;
      break-inside: avoid;
      font-size: 12px; /* Restaurar font-size para contenido */
    }
    
    /* Sin gap - cartas pegadas */
    .container.no-gap .card,
    .container.no-gap .hero {
      margin: 0;
    }
    
    /* Con gap - añadir márgenes */
    .container.with-gap .card,
    .container.with-gap .hero {
      margin-right: 3mm;
      margin-bottom: 3mm;
    }
    
    /* Tamaños */
    .card {
      width: 63mm;
      height: 88mm;
    }
    
    .hero {
      width: 88mm;
      height: 126mm;
    }
    
    .hero.reduced {
      width: 63mm;
      height: 88mm;
    }
    
    img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      display: block;
    }
  </style>
</head>
<body>

@php
  // Configuración
  $reduceHeroes = request()->get('reduce_heroes', false);
  $withGap = request()->get('with_gap', false);
  
  // Funciones helper
  function assetToPath($url) {
    $url = parse_url($url, PHP_URL_PATH);
    if (strpos($url, '/storage/') === 0) {
      return storage_path('app/public/' . substr($url, 9));
    }
    if (strpos($url, 'storage/') === 0) {
      return storage_path('app/public/' . substr($url, 8));
    }
    return public_path($url);
  }
  
  function imageToBase64($path) {
    if (file_exists($path)) {
      $type = pathinfo($path, PATHINFO_EXTENSION);
      $data = file_get_contents($path);
      return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    return null;
  }
@endphp

<div class="container {{ $withGap ? 'with-gap' : 'no-gap' }}">
  @foreach($items as $item)
    @php
      $entity = $item['entity'];
      $locale = app()->getLocale();
      
      $hasPreview = $entity->hasPreviewImage($locale);
      $previewUrl = $hasPreview ? $entity->getPreviewImageUrl($locale) : null;
      $hasMainImage = $entity->hasImage();
      $mainImageUrl = $hasMainImage ? $entity->getImageUrl() : null;
      
      $imageUrl = $previewUrl ?: $mainImageUrl;
      $imagePath = $imageUrl ? assetToPath($imageUrl) : null;
      $imageData = $imagePath ? imageToBase64($imagePath) : null;
      
      $isHero = $item['type'] === 'hero';
      $class = $isHero ? 'hero' : 'card';
      if ($isHero && $reduceHeroes) {
        $class .= ' reduced';
      }
    @endphp
    
    <div class="{{ $class }}">
      @if($imageData)
        <img src="{{ $imageData }}" alt="{{ $entity->name }}">
      @else
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='280'%3E%3Crect width='200' height='280' fill='%23f0f0f0'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' font-family='Arial' font-size='14' fill='%23999'%3E{{ $entity->name }}%3C/text%3E%3C/svg%3E" alt="{{ $entity->name }}">
      @endif
    </div>
  @endforeach
</div>

</body>
</html>