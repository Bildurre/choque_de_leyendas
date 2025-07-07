<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }}</title>
  <style>
    /* Font faces - Using base64 embedded fonts for PDF compatibility */
    @php
      // Helper function to convert font to base64
      $fontToBase64 = function($path) {
        if (file_exists($path)) {
          $fontData = file_get_contents($path);
          return 'data:font/ttf;base64,' . base64_encode($fontData);
        }
        return null;
      };
      
      // Convert TTF fonts to base64
      $almendraRegular = $fontToBase64(public_path('fonts/almendra/Almendra-Regular.ttf'));
      $almendraBold = $fontToBase64(public_path('fonts/almendra/Almendra-Bold.ttf'));
      $almendraItalic = $fontToBase64(public_path('fonts/almendra/Almendra-Italic.ttf'));
      $almendraBoldItalic = $fontToBase64(public_path('fonts/almendra/Almendra-BoldItalic.ttf'));
      
      $robotoRegular = $fontToBase64(public_path('fonts/roboto/Roboto-Regular.ttf'));
      $robotoBold = $fontToBase64(public_path('fonts/roboto/Roboto-Bold.ttf'));
      $robotoItalic = $fontToBase64(public_path('fonts/roboto/Roboto-Italic.ttf'));
      $robotoBoldItalic = $fontToBase64(public_path('fonts/roboto/Roboto-BoldItalic.ttf'));
    @endphp
    
    @if($almendraRegular)
    @font-face {
      font-family: 'almendra';
      src: url('{{ $almendraRegular }}') format('truetype');
      font-weight: 400;
      font-style: normal;
    }
    @endif
    
    @if($almendraBold)
    @font-face {
      font-family: 'almendra';
      src: url('{{ $almendraBold }}') format('truetype');
      font-weight: 700;
      font-style: normal;
    }
    @endif
    
    @if($almendraItalic)
    @font-face {
      font-family: 'almendra';
      src: url('{{ $almendraItalic }}') format('truetype');
      font-weight: 400;
      font-style: italic;
    }
    @endif
    
    @if($almendraBoldItalic)
    @font-face {
      font-family: 'almendra';
      src: url('{{ $almendraBoldItalic }}') format('truetype');
      font-weight: 700;
      font-style: italic;
    }
    @endif
    
    @if($robotoRegular)
    @font-face {
      font-family: 'roboto';
      src: url('{{ $robotoRegular }}') format('truetype');
      font-weight: 400;
      font-style: normal;
    }
    @endif
    
    @if($robotoBold)
    @font-face {
      font-family: 'roboto';
      src: url('{{ $robotoBold }}') format('truetype');
      font-weight: 700;
      font-style: normal;
    }
    @endif
    
    @if($robotoItalic)
    @font-face {
      font-family: 'roboto';
      src: url('{{ $robotoItalic }}') format('truetype');
      font-weight: 400;
      font-style: italic;
    }
    @endif
    
    @if($robotoBoldItalic)
    @font-face {
      font-family: 'roboto';
      src: url('{{ $robotoBoldItalic }}') format('truetype');
      font-weight: 700;
      font-style: italic;
    }
    @endif
    
    /* Reset and base styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'roboto', sans-serif;
      font-size: 12pt;
      line-height: 1.6;
      color: #000;
      background: #fff;
      padding: 2cm;
      text-align: justify;
    }
    
    /* Page title */
    h1 {
      font-family: 'almendra', serif;
      font-size: 28pt;
      text-align: center;
      margin-bottom: 1.5em;
      page-break-after: avoid;
    }
    
    /* Block titles */
    h2 {
      font-family: 'almendra', serif;
      font-size: 22pt;
      margin-top: 1em;
      margin-bottom: 0.5em;
      text-align: justify;
      page-break-after: avoid;
    }
    
    h3 {
      font-family: 'almendra', serif;
      font-size: 18pt;
      margin-top: 0.8em;
      margin-bottom: 0.4em;
      text-align: justify;
      page-break-after: avoid;
    }
    
    /* Header block titles - slightly larger */
    .block--header h2 {
      font-size: 24pt;
    }
    
    .block--header h3 {
      font-size: 20pt;
    }
    
    /* Paragraphs and content */
    p {
      margin-bottom: 1em;
      text-align: justify;
    }
    
    /* Images */
    img {
      max-width: 50%;
      height: auto;
      float: left;
      margin-right: 1em;
      margin-bottom: 0.5em;
      page-break-inside: avoid;
    }
    
    /* Blocks */
    .block {
      margin-bottom: 2em;
      clear: both;
    }
    
    .block--header {
      margin-bottom: 2em;
      padding-bottom: 1em;
      border-bottom: 2px solid #000;
    }
    
    /* Clear floats */
    .clearfix::after {
      content: "";
      display: table;
      clear: both;
    }
    
    /* Print specific styles */
    @media print {
      body {
        padding: 0;
      }
    }
  </style>
</head>
<body>
  {{-- Page Title --}}
  <h1>{{ $title }}</h1>
  
  {{-- Render blocks --}}
  @foreach($blocks as $block)
    @if($block->type === 'header')
      <div class="block block--header">
        @if($block->title)
          <h2>{{ $block->title }}</h2>
        @endif
        
        @if($block->subtitle)
          <h3>{!! $block->subtitle !!}</h3>
        @endif
      </div>
    @elseif($block->type === 'text')
      <div class="block block--text">
        @if($block->title)
          <h2>{{ $block->title }}</h2>
        @endif
        
        @if($block->subtitle)
          <h3>{!! $block->subtitle !!}</h3>
        @endif
        
        <div class="clearfix">
          @if($block->hasImage())
            @php
              $imagePath = public_path('storage/' . $block->image);
              $imageBase64 = image_to_base64($imagePath);
            @endphp
            @if($imageBase64)
              <img src="{{ $imageBase64 }}" alt="{{ $block->title ?? '' }}">
            @endif
          @endif
          
          @if($block->content)
            {!! $block->content !!}
          @endif
        </div>
      </div>
    @endif
  @endforeach
</body>
</html>