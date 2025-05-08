<!-- resources/views/admin/pages/_blocks_manager.blade.php -->
<div class="blocks-manager">
  <div class="blocks-manager__header">
    <h2 class="blocks-manager__title">{{ __('blocks.page_blocks') }}</h2>
    
    <div class="blocks-manager__actions">
      <div class="dropdown">
        <button class="btn btn--primary dropdown-toggle">
          <span class="btn__icon">+</span>
          <span class="btn__text">{{ __('blocks.add_block') }}</span>
        </button>
        
        <div class="dropdown-menu">
          @foreach(config('blocks.types') as $blockType => $blockConfig)
            <a href="{{ route('admin.pages.blocks.create', [$page, 'type' => $blockType]) }}" class="dropdown-item">
              <span class="dropdown-item__icon">
                <x-icon :name="$blockConfig['icon'] ?? 'default'" />
              </span>
              <span class="dropdown-item__text">{{ __('blocks.types.' . $blockType) }}</span>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  
  <div class="blocks-manager__content" id="blocks-container" data-reorder-url="{{ route('admin.pages.blocks.reorder', $page) }}">
    @foreach($page->blocks as $block)
      <div class="block-item" data-block-id="{{ $block->id }}">
        <div class="block-item__header">
          <div class="block-item__title">
            <span class="block-item__type">{{ __('blocks.types.' . $block->type) }}</span>
            @if($block->title)
              <span class="block-item__name">{{ $block->title }}</span>
            @endif
          </div>
          
          <div class="block-item__actions">
            <a href="{{ route('admin.pages.blocks.edit', [$page, $block]) }}" class="action-button action-button--edit">
              <x-icon name="edit" size="sm" class="action-button__icon" />
            </a>
            
            <form action="{{ route('admin.pages.blocks.destroy', [$page, $block]) }}" method="POST" class="action-button-form">
              @csrf
              @method('DELETE')
              <button 
                type="submit" 
                class="action-button action-button--delete"
                data-confirm-message="{{ __('blocks.confirm_delete') }}"
              >
                <x-icon name="trash" size="sm" class="action-button__icon" />
              </button>
            </form>
          </div>
        </div>
        
        <div class="block-item__preview">
          @if($block->type === 'text' && $block->content)
            <div class="block-item__text-preview">
              {!! Str::limit(strip_tags($block->content), 150) !!}
            </div>
          @endif
        </div>
      </div>
    @endforeach
    
    @if($page->blocks->isEmpty())
      <div class="blocks-manager__empty">
        {{ __('blocks.no_blocks') }}
      </div>
    @endif
  </div>
  
  <!-- No se necesita crear aquí el botón, se creará mediante JavaScript -->
</div>

<!-- Formulario oculto para enviar el nuevo orden -->
<form id="reorder-form" method="POST" style="display: none;">
  @csrf
  <input type="hidden" name="block_ids" id="block-ids-input">
</form>

<!-- Agrega el texto localizado para el botón de guardar -->
<div style="display: none;" data-save-order-text="{{ __('blocks.save_order') }}"></div>