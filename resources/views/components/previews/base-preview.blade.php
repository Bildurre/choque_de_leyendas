@props([
  'name' => '',
  'backgroundImage' => '',
  'headerTextLeft' => '',
  'headerTextRight' => '',
  'factionColor' => '#3d3df5',
  'factionTextIsDark' => true,
  'factionIcon' => '',
  'collectionNumber' => '0',
  'deckIcons' => []
])

<div {{ $attributes->merge(['class' => 'preview-card']) }} style="--faction-color: {{ $factionColor.'cc' }}; --text-color: {{ $factionTextIsDark ? '#000000' : '#ffffff' }}">
  <div class="preview-card__background" style="background-image: url('{{ $backgroundImage }}')"></div>
  <div class="preview-card__faction-icon" style="background-image: url('{{ $factionIcon }}')"></div>
  <div class="preview-card__side-info">
    {{ $sideInfo ?? '' }}
  </div>

  <div class="preview-card__frame">
    <h3 class="preview-card__text preview-card__text--name">
      {{ $name }}
    </h3>
  </div>

  <div class="preview-card__box">
    <div class="preview-card__frame"></div>
    <div></div>
    <div class="preview-card__frame"></div>
  </div>

  <div class="preview-card__frame preview-card__header">
    <p class="preview-card__text">{{ $headerTextLeft ?? '' }}</p>
    <p class="preview-card__text">{{ $headerTextRight ?? '' }}</p>
  </div>

  <div class="preview-card__box">
    <div class="preview-card__frame"></div>
    <div class="preview-card__box--content">
      {{ $content ?? '' }}
    </div>
    <div class="preview-card__frame"></div>
  </div>

  <div class="preview-card__frame preview-card__footer">
    <div class="preview-card__decks"></div>
    <p class="preview-card__text">
      Alanda: Choque de Leyendas 
      <x-application-logo-small />
    </p>
    <div class="preview-card__collection"></div>
  </div>
</div>