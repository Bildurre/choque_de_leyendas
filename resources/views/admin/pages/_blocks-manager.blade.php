<x-entity.list 
  title="{{ __('pages.blocks.page_blocks') }}"
  :items="$page->blocks"
  :is-reorderable="true"
  :reorder-url="route('admin.pages.blocks.reorder', $page)"
  reorder-item-id-field="block-id"
  :showHeader="true"
>
  <x-slot:actions>
    <x-dropdown label="{{ __('pages.blocks.add_block') }}" icon="plus">
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
  
  @foreach($page->blocks as $block)
    <x-entity.list-card 
      :title="__('pages.blocks.types.' . $block->type)"
      :edit-route="route('admin.pages.blocks.edit', [$page, $block])"
      :delete-route="route('admin.pages.blocks.destroy', [$page, $block])"
      :confirm-message="__('pages.blocks.confirm_delete')"
      data-block-id="{{ $block->id }}"
    >
      @if($block->title)
        <x-slot:badges>
          <x-badge variant="primary">
            {{ $block->title }}
          </x-badge>
        </x-slot:badges>
      @endif
      
      @if($block->type === 'text' && $block->content)
        <div class="block-item__text-preview">
          {!! Str::limit(strip_tags($block->content), 150) !!}
        </div>
      @endif
    </x-entity.list-card>
  @endforeach
</x-entity.list>