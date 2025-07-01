<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
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
    }
    
    .container {
      width: 100%;
    }
    
    .tokens-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 5mm;
      justify-content: center;
    }
    
    .token {
      width: 40mm;
      height: 40mm;
      border: 2px solid #333;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f9f9f9;
      page-break-inside: avoid;
      position: relative;
    }
    
    .token-content {
      text-align: center;
    }
    
    .token-icon {
      font-size: 24px;
      margin-bottom: 5px;
    }
    
    .token-name {
      font-size: 10px;
      font-weight: bold;
      text-transform: uppercase;
    }
    
    .token-value {
      position: absolute;
      bottom: 5mm;
      right: 5mm;
      width: 12mm;
      height: 12mm;
      background: #333;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 14px;
    }
    
    h1 {
      text-align: center;
      margin-bottom: 20mm;
      font-size: 24px;
      color: #333;
    }
    
    .page-break {
      page-break-after: always;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>{{ $title }}</h1>
    
    <div class="tokens-grid">
      {{-- Tokens temporales de ejemplo --}}
      @php
        $tokens = [
          ['name' => __('pdf.token_damage'), 'icon' => '⚔️', 'values' => [1, 3, 5]],
          ['name' => __('pdf.token_shield'), 'icon' => '🛡️', 'values' => [1, 2, 3]],
          ['name' => __('pdf.token_poison'), 'icon' => '☠️', 'values' => [1, 2]],
          ['name' => __('pdf.token_stun'), 'icon' => '💫', 'values' => []],
          ['name' => __('pdf.token_burn'), 'icon' => '🔥', 'values' => [1, 2, 3]],
          ['name' => __('pdf.token_freeze'), 'icon' => '❄️', 'values' => []],
        ];
      @endphp
      
      @foreach($tokens as $tokenType)
        @if(empty($tokenType['values']))
          {{-- Token sin valores, generar 4 copias --}}
          @for($i = 0; $i < 4; $i++)
            <div class="token">
              <div class="token-content">
                <div class="token-icon">{{ $tokenType['icon'] }}</div>
                <div class="token-name">{{ $tokenType['name'] }}</div>
              </div>
            </div>
          @endfor
        @else
          {{-- Token con valores, generar 2 de cada valor --}}
          @foreach($tokenType['values'] as $value)
            @for($i = 0; $i < 2; $i++)
              <div class="token">
                <div class="token-content">
                  <div class="token-icon">{{ $tokenType['icon'] }}</div>
                  <div class="token-name">{{ $tokenType['name'] }}</div>
                </div>
                <div class="token-value">{{ $value }}</div>
              </div>
            @endfor
          @endforeach
        @endif
      @endforeach
    </div>
  </div>
</body>
</html>