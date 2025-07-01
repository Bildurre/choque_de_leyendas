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
      size: A4 landscape;
      margin: 0;
    }
    
    body {
      margin: 0;
      padding: 10mm;
      background: white;
      font-family: Arial, sans-serif;
      color: #000;
      font-size: 14px;
    }
    
    .container {
      display: flex;
      gap: 20mm;
      height: 100%;
    }
    
    .column {
      flex: 1;
    }
    
    .section-title {
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 8mm;
      text-transform: uppercase;
      border-bottom: 1px solid #000;
      padding-bottom: 2mm;
    }
    
    .counters-table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .counters-table tr {
      border-bottom: 1px solid #eee;
    }
    
    .counters-table tr:last-child {
      border-bottom: none;
    }
    
    .counters-table td {
      padding: 0;
      vertical-align: middle;
    }
    
    .counter-icon {
      width: 50px;
      text-align: center;
      padding: 2mm 0;
    }
    
    .counter-icon img {
      width: 40px;
      height: 40px;
      object-fit: contain;
      border-radius: 50%;
    }
    
    .counter-info {
      padding: 4mm;
      padding-left: 5mm;
    }
    
    .counter-name {
      font-size: 15px;
      font-weight: bold;
      margin-bottom: 2mm;
    }
    
    .counter-effect {
      font-size: 13px;
      line-height: 1.4;
    }
    
    .no-counters {
      text-align: center;
      padding: 5mm;
      color: #666;
      font-style: italic;
    }
  </style>
</head>
<body>
  <div class="container">
    {{-- Columna izquierda: Boons --}}
    <div class="column">
      <h2 class="section-title">{{ __('entities.counters.types.boon') }}</h2>
      
      @if($boonCounters->isNotEmpty())
        <table class="counters-table">
          <tbody>
            @foreach($boonCounters as $counter)
              @php
                $hasImage = $counter->hasImage();
                $imageUrl = $hasImage ? $counter->getImageUrl() : null;
                $imagePath = $imageUrl ? pdf_asset_to_path($imageUrl) : null;
                $imageData = $imagePath ? image_to_base64($imagePath) : null;
              @endphp
              <tr>
                <td class="counter-icon">
                  @if($imageData)
                    <img src="{{ $imageData }}" alt="{{ $counter->getTranslation('name', $locale) }}">
                  @else
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40'%3E%3Ccircle cx='20' cy='20' r='19' fill='%23f0f0f0' stroke='%23333' stroke-width='1'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' font-family='Arial' font-size='16' fill='%23333'%3E+%3C/text%3E%3C/svg%3E" alt="{{ $counter->getTranslation('name', $locale) }}">
                  @endif
                </td>
                <td class="counter-info">
                  <div class="counter-name">{{ $counter->getTranslation('name', $locale) }}</div>
                  <div class="counter-effect">{{ $counter->getTranslation('effect', $locale) }}</div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <p class="no-counters">{{ __('pdf.no_boon_counters') }}</p>
      @endif
    </div>
    
    {{-- Columna derecha: Banes --}}
    <div class="column">
      <h2 class="section-title">{{ __('entities.counters.types.bane') }}</h2>
      
      @if($baneCounters->isNotEmpty())
        <table class="counters-table">
          <tbody>
            @foreach($baneCounters as $counter)
              @php
                $hasImage = $counter->hasImage();
                $imageUrl = $hasImage ? $counter->getImageUrl() : null;
                $imagePath = $imageUrl ? pdf_asset_to_path($imageUrl) : null;
                $imageData = $imagePath ? image_to_base64($imagePath) : null;
              @endphp
              <tr>
                <td class="counter-icon">
                  @if($imageData)
                    <img src="{{ $imageData }}" alt="{{ $counter->getTranslation('name', $locale) }}">
                  @else
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40'%3E%3Ccircle cx='20' cy='20' r='19' fill='%23f0f0f0' stroke='%23333' stroke-width='1'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' font-family='Arial' font-size='16' fill='%23333'%3E-%3C/text%3E%3C/svg%3E" alt="{{ $counter->getTranslation('name', $locale) }}">
                  @endif
                </td>
                <td class="counter-info">
                  <div class="counter-name">{{ $counter->getTranslation('name', $locale) }}</div>
                  <div class="counter-effect">{{ $counter->getTranslation('effect', $locale) }}</div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <p class="no-counters">{{ __('pdf.no_bane_counters') }}</p>
      @endif
    </div>
  </div>
</body>
</html>