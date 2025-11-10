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
      $imfellenglishRegular = $fontToBase64(public_path('fonts/imfellenglish/IMFellEnglish-Regular.ttf'));
      $imfellenglishItalic = $fontToBase64(public_path('fonts/imfellenglish/IMFellEnglish-Italic.ttf'));
      
      $robotoRegular = $fontToBase64(public_path('fonts/roboto/Roboto-Regular.ttf'));
      $robotoBold = $fontToBase64(public_path('fonts/roboto/Roboto-Bold.ttf'));
      $robotoItalic = $fontToBase64(public_path('fonts/roboto/Roboto-Italic.ttf'));
      $robotoBoldItalic = $fontToBase64(public_path('fonts/roboto/Roboto-BoldItalic.ttf'));
    @endphp
    
    @if($imfellenglishRegular)
    @font-face {
      font-family: 'imfellenglish';
      src: url('{{ $imfellenglishRegular }}') format('truetype');
      font-weight: 400;
      font-style: normal;
    }
    @endif
    
    @if($imfellenglishItalic)
    @font-face {
      font-family: 'imfellenglish';
      src: url('{{ $imfellenglishItalic }}') format('truetype');
      font-weight: 400;
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
      font-size: 11pt;
      line-height: 1.2;
      color: #000;
      background: #fff;
      padding: 2cm;
      text-align: justify;
    }
    
    /* All headings take full width and clear floats */
    h1, h2, h3, h4, h5, h6 {
      clear: both !important;
      width: 100% !important;
      display: block !important;
      page-break-after: avoid !important;
      page-break-inside: avoid !important;
    }
    
    /* Ensure at least 3 lines of content after heading before page break */
    h1 + *, h2 + *, h3 + *, h4 + *, h5 + *, h6 + * {
      page-break-before: avoid !important;
    }
    
    /* Paragraphs and lists flow around floated images */
    p, ul, ol {
      clear: none !important;
    }
    
    /* Content wrapper for float context */
    .block__content-wrapper {
      /* Contains the float */
    }
    
    /* Content area that flows around image */
    .block__content {
      /* Content flows naturally around floats */
    }
    
    /* Ensure headings inside WYSIWYG content also clear floats */
    .block__content h1,
    .block__content h2,
    .block__content h3,
    .block__content h4,
    .block__content h5,
    .block__content h6 {
      clear: both !important;
      width: 100% !important;
      display: block !important;
      page-break-after: avoid !important;
      page-break-inside: avoid !important;
    }
    
    /* Page title */
    h1 {
      font-family: 'imfellenglish', serif;
      font-size: 21pt;
      text-align: center;
      margin-bottom: 1.5em;
    }
    
    /* Block titles */
    h2 {
      font-family: 'imfellenglish', serif;
      font-size: 19pt;
      margin-top: 1em;
      margin-bottom: 0.5em;
      text-align: justify;
    }
    
    h3 {
      font-family: 'imfellenglish', serif;
      font-size: 17pt;
      margin-top: 0.8em;
      margin-bottom: 0.4em;
      text-align: justify;
    }

    h4 {
      font-family: 'imfellenglish', serif;
      font-size: 15pt;
      margin-top: 0.8em;
      margin-bottom: 0.4em;
      text-align: justify;
    }

    h5 {
      font-family: 'imfellenglish', serif;
      font-size: 13pt;
      margin-top: 0.8em;
      margin-bottom: 0.4em;
      text-align: justify;
    }
    
    /* Header block titles - slightly larger */
    .block--header h2 {
      font-size: 21pt;
    }
    
    .block--header h3 {
      font-size: 19pt;
    }
    
    /* Paragraphs and content */
    p {
      margin-bottom: 1em;
      text-align: justify;
    }

    /* Lists styling */
    ul, ol {
      list-style-position: outside;
      margin-left: 2em;
      margin-bottom: 1em;
    }

    /* Ordered lists - numbers */
    ol {
      list-style-type: decimal;
    }

    /* Unordered lists - discs */
    ul {
      list-style-type: disc;
    }

    /* Nested lists */
    ul ul, ol ol, ul ol, ol ul {
      margin-left: 1.5em;
      margin-bottom: 0;
    }

    /* Nested unordered lists - different markers */
    ul ul {
      list-style-type: circle;
    }

    ul ul ul {
      list-style-type: square;
    }

    /* List items */
    li {
      margin-bottom: 0.3em;
      text-align: justify;
    }

    /* Last item in list shouldn't have bottom margin */
    li:last-child {
      margin-bottom: 0;
    }

    .block--text table {
      width: 100%;
      border-collapse: collapse;
    }

    .block--text td {
      text-align: center;
      border-bottom: 1px solid grey;
      vertical-align: middle;
      padding: 1mm;
    }

    .block--text table img {
      display: inline-block !important;
      margin: 0 !important;
      vertical-align: middle !important;
      float: none !important;
      max-width: none !important;
      width: 11pt !important;
      height: 11pt !important;
    }
    
    /* Images - only for text blocks */
    .block--text img {
      max-width: 50%;
      height: auto;
      float: right;
      margin-left: 1em;
      margin-bottom: 0.5em;
      page-break-inside: avoid;
    }
    
    /* Blocks */
    .block {
      margin-bottom: 2em;
    }
    
    .block--header {
      margin-bottom: 2em;
      padding-bottom: 1em;
      border-bottom: 2px solid #000;
    }
    
    /* Counter list specific styles */
    .block--counters-list .counters-table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .block--counters-list .counters-table tr {
      border-bottom: 1px solid #eee;
    }
    
    .block--counters-list .counters-table tr:last-child {
      border-bottom: none;
    }
    
    .block--counters-list .counters-table td {
      
      padding: 0 !important;
      margin: 0 !important;
      vertical-align: middle;
    }
    
    .block--counters-list .counter-icon {
      text-align: center;
      width: 1.5cm;
    }
    
    .block--counters-list .counter-icon img {
      object-fit: contain;
      border-radius: 50%;
    }
    
    .block--counters-list .counter-info {
      padding: 0 !important;
      margin: 0 !important;
    }
    
    .block--counters-list .counter-name {
      font-weight: bold;
      font-family: "imfellenglish";
    }
    
    .block--counters-list .counter-effect {
      font-size: 9pt;
      line-height: 1;
      padding: 3px !important;
      margin: 0 !important;
    }
    
    .block--counters-list .counter-effect * {
      font-size: 9pt;
      line-height: 1;
      padding: 0 !important;
      margin: 0 !important;
    }

    .block--counters-list .block__empty {
      text-align: center;
      padding: 5mm;
      color: #666;
      font-style: italic;
    }
    
    /* WYSIWYG content images (dice icons, etc) - only inside text blocks */
    .block__text img {
      width: 11pt !important;
      height: 11pt !important;
      display: inline-block;
      vertical-align: middle;
    }
    
    /* Ensure counter icon images are not affected */
    .block--counters-list .counter-icon img {
      width: .8cm !important;
      height: .8cm !important;
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
        {{-- Titles always appear first, full width --}}
        @if($block->title)
          <h2>{{ $block->title }}</h2>
        @endif
        
        @if($block->subtitle)
          <h3>{!! $block->subtitle !!}</h3>
        @endif
        
        {{-- Extract initial headings from content --}}
        @php
          $content = $block->content ?? '';
          $initialHeadings = [];
          $remainingContent = $content;
          
          if ($content) {
            // Create a DOM document to parse HTML
            $dom = new \DOMDocument();
            // Suppress warnings for HTML5 tags
            libxml_use_internal_errors(true);
            // Add UTF-8 meta tag and wrap content to ensure proper parsing
            $wrappedContent = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><div>' . $content . '</div>';
            $dom->loadHTML($wrappedContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();
            
            // Get the wrapper div
            $wrapper = $dom->getElementsByTagName('div')->item(0);
            
            if ($wrapper) {
              $nodesToRemove = [];
              
              // Extract initial headings
              foreach ($wrapper->childNodes as $node) {
                if ($node->nodeType === XML_ELEMENT_NODE) {
                  $tagName = strtolower($node->nodeName);
                  
                  // If it's a heading, add to initial headings
                  if (in_array($tagName, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) {
                    $initialHeadings[] = $dom->saveHTML($node);
                    $nodesToRemove[] = $node;
                  } else {
                    // Stop at the first non-heading element
                    break;
                  }
                }
              }
              
              // Remove initial headings from DOM
              foreach ($nodesToRemove as $node) {
                $wrapper->removeChild($node);
              }
              
              // Get remaining content
              $remainingContent = '';
              foreach ($wrapper->childNodes as $node) {
                $remainingContent .= $dom->saveHTML($node);
              }
            }
          }
        @endphp
        
        {{-- Display initial headings from WYSIWYG --}}
        @foreach($initialHeadings as $heading)
          {!! $heading !!}
        @endforeach
        
        {{-- Content area with floated image --}}
        <div class="block__content-wrapper clearfix">
          @if($block->hasMultilingualImage($locale))
            @php
              // Get the image array
              $images = $block->image;
              $imagePath = null;
              
              // First, try to get the image for the current locale
              if (is_array($images) && isset($images[$locale])) {
                $imagePath = public_path('storage/' . $images[$locale]);
                
                // Check if the file exists
                if (!file_exists($imagePath)) {
                  $imagePath = null;
                }
              }
              
              // If no image found for current locale, get the first available image
              if (!$imagePath && is_array($images) && !empty($images)) {
                foreach ($images as $localeKey => $image) {
                  $tempPath = public_path('storage/' . $image);
                  if (file_exists($tempPath)) {
                    $imagePath = $tempPath;
                    break;
                  }
                }
              }
              
              // Convert to base64 if path exists
              $imageBase64 = null;
              if ($imagePath && file_exists($imagePath)) {
                $imageBase64 = image_to_base64($imagePath);
              }
            @endphp
            
            @if($imageBase64)
              <img src="{{ $imageBase64 }}" alt="{{ $block->title ?? '' }}">
            @endif
          @endif
          
          @if($remainingContent)
            @php
              // Convert dice images and other WYSIWYG images to base64
              $remainingContent = preg_replace_callback('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', function($matches) {
                $fullTag = $matches[0];
                $src = $matches[1];
                
                try {
                  // Skip if already base64
                  if (strpos($src, 'data:') === 0) {
                    return $fullTag;
                  }
                  
                  $imagePath = null;
                  
                  // Handle relative paths from WYSIWYG (../../../../../storage/images/dices/dice-red.svg)
                  if (strpos($src, '../') === 0) {
                    // Extract the filename and check common locations
                    $filename = basename($src);
                    
                    // Check if it's a dice image
                    if (strpos($src, 'dice-') !== false) {
                      $imagePath = storage_path('app/public/images/dices/' . $filename);
                    } else {
                      // Try to extract the path after 'storage/'
                      if (preg_match('/storage\/(.+)$/', $src, $pathMatches)) {
                        $imagePath = storage_path('app/public/' . $pathMatches[1]);
                      }
                    }
                  }
                  // Handle absolute storage paths
                  elseif (strpos($src, 'storage/') === 0) {
                    $imagePath = storage_path('app/public/' . substr($src, 8));
                  } elseif (strpos($src, '/storage/') === 0) {
                    $imagePath = storage_path('app/public/' . substr($src, 9));
                  }
                  
                  // If path not found, try common locations based on filename
                  if (!$imagePath || !file_exists($imagePath)) {
                    $filename = basename($src);
                    $possiblePaths = [
                      storage_path('app/public/images/dices/' . $filename),
                      storage_path('app/public/images/' . $filename),
                      public_path('images/dices/' . $filename),
                      public_path('images/' . $filename),
                    ];
                    
                    foreach ($possiblePaths as $path) {
                      if (file_exists($path)) {
                        $imagePath = $path;
                        break;
                      }
                    }
                  }
                  
                  if ($imagePath && file_exists($imagePath)) {
                    $dataUri = image_to_base64($imagePath);
                    if ($dataUri) {
                      return str_replace($src, $dataUri, $fullTag);
                    }
                  }
                  
                  return $fullTag;
                } catch (\Exception $e) {
                  return $fullTag;
                }
              }, $remainingContent);
            @endphp
            <div class="block__content">
              {!! $remainingContent !!}
            </div>
          @endif
        </div>
      </div>
    @elseif($block->type === 'counters-list')
      <div class="block block--counters-list">
        {{-- Titles always appear first, full width --}}
        @if($block->title)
          <h2>{{ $block->title }}</h2>
        @endif
        
        @if($block->subtitle)
          <h3>{!! $block->subtitle !!}</h3>
        @endif
        
        <div class="block__content">
          @if($block->content)
            @php
              // Convert dice images and other WYSIWYG images to base64
              $processedContent = preg_replace_callback('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', function($matches) {
                $fullTag = $matches[0];
                $src = $matches[1];
                
                try {
                  // Skip if already base64
                  if (strpos($src, 'data:') === 0) {
                    return $fullTag;
                  }
                  
                  $imagePath = null;
                  
                  // Handle relative paths from WYSIWYG (../../../../../storage/images/dices/dice-red.svg)
                  if (strpos($src, '../') === 0) {
                    // Extract the filename and check common locations
                    $filename = basename($src);
                    
                    // Check if it's a dice image
                    if (strpos($src, 'dice-') !== false) {
                      $imagePath = storage_path('app/public/images/dices/' . $filename);
                    } else {
                      // Try to extract the path after 'storage/'
                      if (preg_match('/storage\/(.+)$/', $src, $pathMatches)) {
                        $imagePath = storage_path('app/public/' . $pathMatches[1]);
                      }
                    }
                  }
                  // Handle absolute storage paths
                  elseif (strpos($src, 'storage/') === 0) {
                    $imagePath = storage_path('app/public/' . substr($src, 8));
                  } elseif (strpos($src, '/storage/') === 0) {
                    $imagePath = storage_path('app/public/' . substr($src, 9));
                  }
                  
                  // If path not found, try common locations based on filename
                  if (!$imagePath || !file_exists($imagePath)) {
                    $filename = basename($src);
                    $possiblePaths = [
                      storage_path('app/public/images/dices/' . $filename),
                      storage_path('app/public/images/' . $filename),
                      public_path('images/dices/' . $filename),
                      public_path('images/' . $filename),
                    ];
                    
                    foreach ($possiblePaths as $path) {
                      if (file_exists($path)) {
                        $imagePath = $path;
                        break;
                      }
                    }
                  }
                  
                  if ($imagePath && file_exists($imagePath)) {
                    $dataUri = image_to_base64($imagePath);
                    if ($dataUri) {
                      return str_replace($src, $dataUri, $fullTag);
                    }
                  }
                  
                  return $fullTag;
                } catch (\Exception $e) {
                  return $fullTag;
                }
              }, $block->content);
            @endphp
            <div class="block__text">{!! $processedContent !!}</div>
          @endif
          
          @php
            // Get counters based on discriminator (boon or bane)
            $counterType = $block->settings['counter_type'] ?? 'boon';
            $counters = \App\Models\Counter::published()
              ->where('type', $counterType)
              ->orderBy('name')
              ->get();
          @endphp
          
          @if($counters->isNotEmpty())
            <table class="counters-table">
              <tbody>
                @foreach($counters as $counter)
                  @php
                    $hasImage = $counter->hasImage();
                    $imageUrl = $hasImage ? $counter->getImageUrl() : null;
                    $imagePath = $imageUrl ? pdf_asset_to_path($imageUrl) : null;
                    $imageData = $imagePath ? image_to_base64($imagePath) : null;
                  @endphp
                  <tr>
                    <td class="counter-icon">
                      @if($imageData)
                        <img src="{{ $imageData }}" alt="{{ $counter->name }}">
                      @else
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40'%3E%3Ccircle cx='20' cy='20' r='19' fill='%23f0f0f0' stroke='%23333' stroke-width='1'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' font-family='Arial' font-size='16' fill='%23333'%3E{{ $counterType == 'boon' ? '+' : '-' }}%3C/text%3E%3C/svg%3E" alt="{{ $counter->name }}">
                      @endif
                    </td>
                    <td class="counter-info">
                      <div class="counter-name">{{ $counter->name }}</div>
                      <div class="counter-effect">{!! $counter->effect !!}</div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @else
            <div class="block__empty">
              <p>{{ __('pages.blocks.counter_list.no_counters', ['type' => __('entities.counters.types.' . $counterType)]) }}</p>
            </div>
          @endif
        </div>
      </div>
    @endif
  @endforeach
</body>
</html>