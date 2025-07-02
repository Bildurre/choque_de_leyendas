<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }}</title>
  <style>
    /* Reset and base styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      font-size: 12pt;
      line-height: 1.6;
      color: #000;
      background: #fff;
      padding: 2cm;
    }
    
    /* Typography */
    h1, h2, h3, h4, h5, h6 {
      font-weight: 600;
      line-height: 1.2;
      margin-bottom: 0.5em;
      page-break-after: avoid;
    }
    
    h1 {
      font-size: 24pt;
      margin-bottom: 0.75em;
    }
    
    h2 {
      font-size: 20pt;
      margin-top: 1.5em;
      margin-bottom: 0.75em;
    }
    
    h3 {
      font-size: 16pt;
      margin-top: 1.2em;
      margin-bottom: 0.6em;
    }
    
    h4 {
      font-size: 14pt;
      margin-top: 1em;
      margin-bottom: 0.5em;
    }
    
    p {
      margin-bottom: 1em;
      text-align: justify;
    }
    
    /* Lists */
    ul, ol {
      margin-left: 2em;
      margin-bottom: 1em;
    }
    
    li {
      margin-bottom: 0.25em;
    }
    
    /* Links - make them visible in print */
    a {
      color: #000;
      text-decoration: underline;
    }
    
    /* Tables */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1em;
      page-break-inside: avoid;
    }
    
    th, td {
      border: 1px solid #000;
      padding: 0.5em;
      text-align: left;
    }
    
    th {
      font-weight: 600;
      background-color: #f5f5f5;
    }
    
    /* Images */
    img {
      max-width: 100%;
      height: auto;
      display: block;
      margin: 1em 0;
      page-break-inside: avoid;
    }
    
    /* Blocks */
    .block {
      margin-bottom: 2em;
      page-break-inside: avoid;
    }
    
    .block--header {
      text-align: center;
      margin-bottom: 3em;
      padding-bottom: 1em;
      border-bottom: 2px solid #000;
    }
    
    .block--header.text-left {
      text-align: left;
    }
    
    .block--header.text-right {
      text-align: right;
    }
    
    .block__title {
      font-size: 20pt;
      margin-bottom: 0.5em;
    }
    
    .block__subtitle {
      font-size: 14pt;
      font-weight: normal;
      color: #333;
      margin-bottom: 0.5em;
    }
    
    /* Text blocks */
    .block--text {
      margin-bottom: 2em;
    }
    
    .block--text.has-image {
      overflow: hidden;
    }
    
    .block--text .block__image {
      margin: 1em 0;
    }
    
    .block--text.has-image-left .block__content-wrapper {
      display: table;
      width: 100%;
    }
    
    .block--text.has-image-left .block__image,
    .block--text.has-image-left .block__content {
      display: table-cell;
      vertical-align: top;
    }
    
    .block--text.has-image-left .block__image {
      width: 40%;
      padding-right: 1em;
    }
    
    .block--text.has-image-right .block__content-wrapper {
      display: table;
      width: 100%;
    }
    
    .block--text.has-image-right .block__image,
    .block--text.has-image-right .block__content {
      display: table-cell;
      vertical-align: top;
    }
    
    .block--text.has-image-right .block__image {
      width: 40%;
      padding-left: 1em;
    }
    
    /* CTA blocks */
    .block--cta {
      border: 2px solid #000;
      padding: 2em;
      margin: 2em 0;
      text-align: center;
      page-break-inside: avoid;
    }
    
    .block--cta.text-left {
      text-align: left;
    }
    
    .block--cta.text-right {
      text-align: right;
    }
    
    .cta-block__action {
      margin-top: 1.5em;
    }
    
    .cta-button {
      display: inline-block;
      padding: 0.75em 1.5em;
      border: 2px solid #000;
      text-decoration: none;
      font-weight: 600;
      background: #fff;
      color: #000;
    }
    
    /* Related items block */
    .block--relateds {
      margin: 2em 0;
    }
    
    .related-items {
      margin-top: 1em;
    }
    
    .related-item {
      margin-bottom: 1em;
      padding-bottom: 1em;
      border-bottom: 1px solid #ccc;
    }
    
    .related-item:last-child {
      border-bottom: none;
    }
    
    .related-item__title {
      font-weight: 600;
      margin-bottom: 0.25em;
    }
    
    /* Page breaks */
    .page-break {
      page-break-after: always;
    }
    
    /* Utility classes */
    .text-center {
      text-align: center !important;
    }
    
    .text-left {
      text-align: left !important;
    }
    
    .text-right {
      text-align: right !important;
    }
    
    /* Print specific styles */
    @media print {
      body {
        padding: 0;
      }
      
      .block {
        page-break-inside: avoid;
      }
      
      h1, h2, h3, h4, h5, h6 {
        page-break-after: avoid;
      }
      
      img {
        page-break-inside: avoid;
      }
      
      table {
        page-break-inside: avoid;
      }
    }
  </style>
</head>
<body>
  {{-- Page Title --}}
  <h1>{{ $title }}</h1>
  
  {{-- Render all blocks --}}
  @foreach($blocks as $block)
    @switch($block->type)
      @case('header')
        <div class="block block--header text-{{ $block->settings['text_alignment'] ?? 'center' }}">
          @if($block->title)
            <h2 class="block__title">{{ $block->title }}</h2>
          @endif
          
          @if($block->subtitle)
            <p class="block__subtitle">{{ $block->subtitle }}</p>
          @endif
        </div>
        @break
        
      @case('text')
        @php
          $hasImage = $block->hasImage();
          $imagePosition = $hasImage ? ($block->settings['image_position'] ?? 'top') : null;
          $textAlignment = $block->settings['text_alignment'] ?? 'left';
        @endphp
        
        <div class="block block--text {{ $hasImage ? 'has-image has-image-' . $imagePosition : '' }}">
          <div class="block__content-wrapper">
            @if($hasImage && in_array($imagePosition, ['top', 'left']))
              <div class="block__image">
                <img src="{{ public_path('storage/' . $block->image) }}" alt="{{ $block->title ?? '' }}">
              </div>
            @endif
            
            <div class="block__content text-{{ $textAlignment }}">
              @if($block->title)
                <h3 class="block__title">{{ $block->title }}</h3>
              @endif
              
              @if($block->subtitle)
                <h4 class="block__subtitle">{{ $block->subtitle }}</h4>
              @endif
              
              @if($block->content)
                <div class="block__text">
                  {!! $block->content !!}
                </div>
              @endif
            </div>
            
            @if($hasImage && in_array($imagePosition, ['bottom', 'right']))
              <div class="block__image">
                <img src="{{ public_path('storage/' . $block->image) }}" alt="{{ $block->title ?? '' }}">
              </div>
            @endif
          </div>
        </div>
        @break
        
      @case('cta')
        @php
          $ctaContent = $block->getTranslation('content', $locale);
          $ctaText = $ctaContent['text'] ?? '';
          $buttonText = $ctaContent['button_text'] ?? '';
          $buttonLink = $ctaContent['button_link'] ?? '';
          $textAlignment = $block->settings['text_alignment'] ?? 'center';
        @endphp
        
        <div class="block block--cta text-{{ $textAlignment }}">
          @if($block->title)
            <h3 class="block__title">{{ $block->title }}</h3>
          @endif
          
          @if($block->subtitle)
            <h4 class="block__subtitle">{{ $block->subtitle }}</h4>
          @endif
          
          @if($ctaText)
            <div class="block__text">
              {!! $ctaText !!}
            </div>
          @endif
          
          @if($buttonText && $buttonLink)
            <div class="cta-block__action">
              <span class="cta-button">{{ $buttonText }}</span>
              <br>
              <small>({{ $buttonLink }})</small>
            </div>
          @endif
        </div>
        @break
        
      @case('relateds')
        @php
          $modelType = $block->settings['model_type'] ?? 'hero';
          $displayType = $block->settings['display_type'] ?? 'latest';
          $buttonText = $block->settings['button_text'] ?? __('pages.blocks.relateds.view_all');
          
          // Get related items based on model type
          $relatedItems = collect();
          
          switch($modelType) {
            case 'hero':
              $query = \App\Models\Hero::published();
              break;
            case 'card':
              $query = \App\Models\Card::published();
              break;
            case 'faction':
              $query = \App\Models\Faction::published();
              break;
            default:
              $query = null;
          }
          
          if ($query) {
            if ($displayType === 'random') {
              $relatedItems = $query->inRandomOrder()->limit(3)->get();
            } else {
              $relatedItems = $query->latest()->limit(3)->get();
            }
          }
        @endphp
        
        @if($relatedItems->isNotEmpty())
          <div class="block block--relateds">
            @if($block->title)
              <h3 class="block__title">{{ $block->title }}</h3>
            @endif
            
            @if($block->subtitle)
              <p class="block__subtitle">{{ $block->subtitle }}</p>
            @endif
            
            <div class="related-items">
              @foreach($relatedItems as $item)
                <div class="related-item">
                  <h4 class="related-item__title">{{ $item->name }}</h4>
                  @if(method_exists($item, 'getLoreText') && $item->lore_text)
                    <p>{{ Str::limit(strip_tags($item->lore_text), 200) }}</p>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
        @endif
        @break
        
      @default
        {{-- Unknown block type, just show the content if available --}}
        <div class="block">
          @if($block->title)
            <h3 class="block__title">{{ $block->title }}</h3>
          @endif
          
          @if($block->content)
            <div class="block__content">
              {!! $block->content !!}
            </div>
          @endif
        </div>
    @endswitch
  @endforeach
</body>
</html>