@php
  $currentPage = \App\Models\Page::find($block->page_id);
  
  // Obtener todos los bloques indexables de la página actual
  $indexableBlocks = $currentPage ? $currentPage->blocks()
    ->where('is_indexable', true)
    ->where('id', '!=', $block->id)
    ->where('order', '>', $block->order)
    ->orderBy('order')
    ->get() : collect();

  $isCompact = $block->settings['compact'] ?? false;
  $isNumbered = $block->settings['numbered'] ?? false;
  
  // Construir clases adicionales
  $indexClasses = 'automatic-index';
  if ($isCompact) $indexClasses .= ' automatic-index--compact';
  if ($isNumbered) $indexClasses .= ' automatic-index--numbered';
  
  // Construir la estructura jerárquica
  $blockTree = $indexableBlocks->where('parent_id', null);
  
  // Función helper para generar el HTML de la lista anidada
  function buildIndexList($blocks, $allBlocks, $level = 0) {
    if ($blocks->isEmpty()) return '';
    
    $html = '<ul class="index-list index-list--level-' . $level . '">';
    
    foreach ($blocks as $block) {
      $label = $block->display_label;
      $children = $allBlocks->where('parent_id', $block->id);
      
      $html .= '<li class="index-list__item">';
      $html .= '<a href="#block-' . $block->order . '" class="index-list__link">' . e($label) . '</a>';
      
      if ($children->isNotEmpty()) {
        $html .= buildIndexList($children, $allBlocks, $level + 1);
      }
      
      $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    return $html;
  }
@endphp

<x-blocks.block :block="$block">
  <div class="{{ $indexClasses }}">
    <x-blocks.content-wrapper :block="$block">
      <div class="block__content">
        <x-blocks.titles :block="$block" />
        
        @if($indexableBlocks->isNotEmpty())
          <nav class="automatic-index__nav" aria-label="{{ __('blocks.automatic_index.navigation_label') }}">
            {!! buildIndexList($blockTree, $indexableBlocks) !!}
          </nav>
        @else
          <p class="automatic-index__empty">
            {{ __('blocks.automatic_index.no_content') }}
          </p>
        @endif
      </div>
    </x-blocks.content-wrapper>
  </div>
</x-blocks.block>