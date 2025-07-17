<x-entity.list 
  title="{{ __('pages.blocks.page_blocks') }}"
  :items="$blocks"
  :is-reorderable="true"
  :reorder-url="route('admin.pages.blocks.reorder', $page)"
  reorder-item-id-field="block-id"
  :showHeader="true"
>
  <x-slot:actions>
    <x-dropdown label="{{ __('pages.blocks.add_block') }}" icon="plus" align="left">
      @foreach(config('blocks.types') as $blockType => $blockConfig)
        <x-dropdown-item 
          :href="route('admin.pages.blocks.create', [$page, 'type' => $blockType])"
          :icon="$blockConfig['icon'] ?? 'default'"
        >
          {{ __('pages.blocks.types.' . $blockType) }}
        </x-dropdown-item>
      @endforeach
    </x-dropdown>
  </x-slot:actions>
  
  @foreach($blocks as $block)
    <x-entity.list-card 
      :title="__('pages.blocks.types.' . $block->type)"
      :edit-route="route('admin.pages.blocks.edit', [$page, $block])"
      :delete-route="route('admin.pages.blocks.destroy', [$page, $block])"
      :confirm-message="__('pages.blocks.confirm_delete')"
      data-block-id="{{ $block->id }}"
    >
      <x-slot:badges>
        <x-badge variant="primary">
          
          {{ $block->is_printable ? __('pages.blocks.printable') : __('pages.blocks.no_printable') }}
        </x-badge>
        <x-badge variant="primary">
          {{ $block->is_indexable ? __('pages.blocks.indexable') : __('pages.blocks.no_indexable') }}
        </x-badge>
        @if ($block->parent)
          <x-badge variant="secondary">
           {{ __('pages.blocks.parent') . ': ' . $block->parent->getDisplayLabelAttribute() }}
          </x-badge>
        @endif
      </x-slot:badges>
    
      <div class="block-item__text-preview">
        {!! $block->getDisplayLabelAttribute() !!}
      </div>
    </x-entity.list-card>
  @endforeach
</x-entity.list>