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
    
    .counter-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
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
    }
    
    .counter-name-wrapper {
      position: relative;
      display: inline-block;
    }
    
    .counter-name-text {
      position: relative;
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
            <div class="counter-name-wrapper">
              <div class="counter-name-text counter-name-boon">{{ strtoupper($counter->getTranslation('name', $locale)) }}</div>
            </div>
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
            <div class="counter-name-wrapper">
              <div class="counter-name-text counter-name-bane">{{ strtoupper($counter->getTranslation('name', $locale)) }}</div>
            </div>
          </div>
        </div>
      @endfor
    @endforeach
  </div>
</body>
</html>