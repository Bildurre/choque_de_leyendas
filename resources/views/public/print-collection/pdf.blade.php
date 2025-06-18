<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Alanda Cards - {{ date('Y-m-d') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            size: A4;
            margin: 10mm;
        }
        
        body {
            font-family: Arial, sans-serif;
        }
        
        .page {
            page-break-after: always;
            position: relative;
            width: 190mm;
            height: 277mm;
        }
        
        .page:last-child {
            page-break-after: auto;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 2mm;
            width: 100%;
            height: 100%;
        }
        
        .card-slot {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        
        .card-wrapper {
            width: 63mm;
            height: 88mm;
            position: relative;
            margin: auto;
        }
        
        /* Cut marks */
        .cut-marks {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        
        .cut-mark {
            position: absolute;
            width: 5mm;
            height: 0.5mm;
            background: #ccc;
        }
        
        .cut-mark.horizontal {
            width: 5mm;
            height: 0.5mm;
        }
        
        .cut-mark.vertical {
            width: 0.5mm;
            height: 5mm;
        }
        
        .cut-mark.top-left.horizontal {
            top: -1mm;
            left: -6mm;
        }
        
        .cut-mark.top-left.vertical {
            top: -6mm;
            left: -1mm;
        }
        
        .cut-mark.top-right.horizontal {
            top: -1mm;
            right: -6mm;
        }
        
        .cut-mark.top-right.vertical {
            top: -6mm;
            right: -1mm;
        }
        
        .cut-mark.bottom-left.horizontal {
            bottom: -1mm;
            left: -6mm;
        }
        
        .cut-mark.bottom-left.vertical {
            bottom: -6mm;
            left: -1mm;
        }
        
        .cut-mark.bottom-right.horizontal {
            bottom: -1mm;
            right: -6mm;
        }
        
        .cut-mark.bottom-right.vertical {
            bottom: -6mm;
            right: -1mm;
        }
        
        /* Card content */
        .preview-content {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .preview-content img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
    @php
        $chunks = array_chunk($items, 9);
    @endphp
    
    @foreach($chunks as $pageItems)
        <div class="page">
            <div class="grid">
                @for($i = 0; $i < 9; $i++)
                    <div class="card-slot">
                        @if(isset($pageItems[$i]))
                            <div class="card-wrapper">
                                <div class="cut-marks">
                                    <div class="cut-mark horizontal top-left"></div>
                                    <div class="cut-mark vertical top-left"></div>
                                    <div class="cut-mark horizontal top-right"></div>
                                    <div class="cut-mark vertical top-right"></div>
                                    <div class="cut-mark horizontal bottom-left"></div>
                                    <div class="cut-mark vertical bottom-left"></div>
                                    <div class="cut-mark horizontal bottom-right"></div>
                                    <div class="cut-mark vertical bottom-right"></div>
                                </div>
                                <div class="preview-content">
                                    @if($pageItems[$i]['type'] === 'hero')
                                        {{-- For heroes, we need to render the preview or use the generated image --}}
                                        @if($pageItems[$i]['entity']->hasPreviewImage())
                                            <img src="{{ $pageItems[$i]['entity']->getPreviewImageUrl() }}" alt="{{ $pageItems[$i]['entity']->name }}">
                                        @else
                                            {{-- Fallback: render the component (note: this might not work well in PDF) --}}
                                            <x-previews.hero :hero="$pageItems[$i]['entity']" />
                                        @endif
                                    @else
                                        {{-- For cards --}}
                                        @if($pageItems[$i]['entity']->hasPreviewImage())
                                            <img src="{{ $pageItems[$i]['entity']->getPreviewImageUrl() }}" alt="{{ $pageItems[$i]['entity']->name }}">
                                        @else
                                            {{-- Fallback: render the component (note: this might not work well in PDF) --}}
                                            <x-previews.card :card="$pageItems[$i]['entity']" />
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
    @endforeach
</body>
</html>