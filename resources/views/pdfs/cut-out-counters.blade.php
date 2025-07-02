@php
  // Corazón ajustado con más espacio arriba
  $simpleHeartSvg = '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" viewBox="1 0 22 22"><path d="M12,21.35l-1.45-1.32C5.4,15.36,2,12.28,2,8.5C2,5.42,4.42,3,7.5,3c1.74,0,3.41,0.81,4.5,2.09C13.09,3.81,14.76,3,16.5,3C19.58,3,22,5.42,22,8.5c0,3.78-3.4,6.86-8.55,11.54L12,21.35z" fill="rgb(255,166,200)" stroke="rgb(185,0,15)" stroke-width="1"/></svg>';
  $simpleHeartBase64 = 'data:image/svg+xml;base64,' . base64_encode($simpleHeartSvg);
@endphp

<!DOCTYPE html>
<html lang="{{ $locale ?? app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <title>{{ $title }} - {{ date('Y-m-d') }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    @page {
      size: A4;
      margin: 0;
    }
    
    body {
      margin: 0;
      padding: 10mm;
      background: white;
      font-family: Arial, sans-serif;
      font-size: 0; /* Remove spacing between inline-blocks */
    }
    
    .counters-container {
      text-align: center;
    }
    
    .counter {
      display: inline-block;
      width: 20mm;
      height: 20mm;
      margin: 1mm;
      border-radius: 50%;
      position: relative;
      overflow: hidden;
      page-break-inside: avoid;
      background-color: #f0f0f0;
      font-size: 12px; /* Reset font size */
    }

    .health {
      border: 1px solid #000;
      background-color: #fff; /* Fondo blanco para los health */
    }
    
    .counter-image {
      width: 20mm;
      height: 20mm;
      object-fit: contain; /* Importante: contain en lugar de cover */
      position: absolute;
      top: 0;
      left: 0;
    }
    
    .counter-name {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      width: 90%;
      line-height: 1;
      z-index: 1;
    }
    
    .counter-name-text {
      color: #000;
      font-family: Arial Black, Helvetica, sans-serif;
      font-weight: 900;
      font-size: 13px;
      text-transform: uppercase;
      z-index: 10;
      background-color: rgba(255, 255, 255, 0.7);
      padding: 2px 4px;
      border-radius: 3px;
    }
    
    .counter-name-health {
      font-size: 32px;
      box-shadow: 0 0 0 2px rgb(185, 0, 15);
      background-color: rgba(255, 255, 255, 0.24);
    }

    .counter-name-boon {
      box-shadow: 0 0 0 2px #2196F3;
    }
    
    .counter-name-bane {
      box-shadow: 0 0 0 2px #f44336;
    }
  </style>
</head>
<body>
  <div class="counters-container">
    {{-- Generate 10 copies of each boon counter --}}
    @foreach($boonCounters as $counter)
      @php
        $hasImage = $counter->hasImage();
        $imageUrl = $hasImage ? $counter->getImageUrl() : null;
        $imagePath = $imageUrl ? pdf_asset_to_path($imageUrl) : null;
        $imageData = $imagePath ? image_to_base64($imagePath) : null;
      @endphp
      
      @for($i = 0; $i < 10; $i++)
        <div class="counter boon">
          @if($imageData)
            <img src="{{ $imageData }}" alt="" class="counter-image">
          @endif
          <div class="counter-name">
            <span class="counter-name-text counter-name-boon">{{ strtoupper($counter->getTranslation('name', $locale)) }}</span>
          </div>
        </div>
      @endfor
    @endforeach
    
    {{-- Generate 10 copies of each bane counter --}}
    @foreach($baneCounters as $counter)
      @php
        $hasImage = $counter->hasImage();
        $imageUrl = $hasImage ? $counter->getImageUrl() : null;
        $imagePath = $imageUrl ? pdf_asset_to_path($imageUrl) : null;
        $imageData = $imagePath ? image_to_base64($imagePath) : null;
      @endphp
      
      @for($i = 0; $i < 10; $i++)
        <div class="counter bane">
          @if($imageData)
            <img src="{{ $imageData }}" alt="" class="counter-image">
          @endif
          <div class="counter-name">
            <span class="counter-name-text counter-name-bane">{{ strtoupper($counter->getTranslation('name', $locale)) }}</span>
          </div>
        </div>
      @endfor
    @endforeach

    {{-- Health counters with SVG --}}
    @for($i = 1; $i <= 15; $i++)
      <div class="counter health">
        <img src="{{ $simpleHeartBase64 }}" alt="" class="counter-image">
        <div class="counter-name">
          <span class="counter-name-text counter-name-health">1</span>
        </div>
      </div>
    @endfor
    @for($i = 1; $i <= 15; $i++)
      <div class="counter health">
        <img src="{{ $simpleHeartBase64 }}" alt="" class="counter-image">
        <div class="counter-name">
          <span class="counter-name-text counter-name-health">2</span>
        </div>
      </div>
    @endfor
    @for($i = 1; $i <= 10; $i++)
      <div class="counter health">
        <img src="{{ $simpleHeartBase64 }}" alt="" class="counter-image">
        <div class="counter-name">
          <span class="counter-name-text counter-name-health">5</span>
        </div>
      </div>
    @endfor
    @for($i = 1; $i <= 10; $i++)
      <div class="counter health">
        <img src="{{ $simpleHeartBase64 }}" alt="" class="counter-image">
        <div class="counter-name">
          <span class="counter-name-text counter-name-health">10</span>
        </div>
      </div>
    @endfor
    @for($i = 1; $i <= 10; $i++)
      <div class="counter health">
        <img src="{{ $simpleHeartBase64 }}" alt="" class="counter-image">
        <div class="counter-name">
          <span class="counter-name-text counter-name-health">20</span>
        </div>
      </div>
    @endfor
  </div>
</body>
</html>