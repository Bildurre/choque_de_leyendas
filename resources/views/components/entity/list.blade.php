@props([
  'title',
  'createRoute' => null,
  'createLabel' => __('admin.create'),
  'emptyMessage' => __('admin.no_records'),
  'items' => null,
  'isReorderable' => false,
  'reorderUrl' => null,
  'reorderItemIdField' => 'id',
  'withTabs' => false,
  'activeTabId' => 'active',
  'trashedTabId' => 'trashed',
  'trashed' => false,
  'activeCount' => null,
  'trashedCount' => null,
  'baseRoute' => null,
  'showHeader' => true
])

<div {{ $attributes->merge(['class' => 'entity-list']) }}>
  @if($withTabs)
    <div class="entity-list__tabs">
      <x-tabs>
        <x-slot:header>
          <x-tab-item 
            :id="$activeTabId"
            :active="!$trashed" 
            :href="route($baseRoute)"
            icon="list"
            :count="$activeCount"
          >
            {{ __('admin.active_items') }}
          </x-tab-item>
          
          <x-tab-item 
            :id="$trashedTabId"
            :active="$trashed" 
            :href="route($baseRoute, ['trashed' => 1])"
            icon="trash"
            :count="$trashedCount"
          >
            {{ __('admin.trashed_items') }}
          </x-tab-item>
          
          {{ $extraTabs ?? '' }}
        </x-slot:header>
        
        <x-slot:content>
          @if($showHeader)
            <div class="entity-list__header">
              <h1 class="entity-list__title">{{ $title }}</h1>
              
              <div class="entity-list__actions">
                @if($isReorderable)
                  <div id="reorder-buttons" style="display: none;" class="entity-list__reorder-buttons">
                    <button type="button" id="save-reorder-button" class="btn btn--primary">
                      {{ __('admin.save_order') }}
                    </button>
                    <button type="button" id="cancel-reorder-button" class="btn btn--secondary">
                      {{ __('admin.cancel') }}
                    </button>
                  </div>
                @endif
                
                @if($createRoute && !$trashed)
                  <a href="{{ $createRoute }}" class="btn btn--primary">
                    {{ $createLabel }}
                  </a>
                @endif
                
                {{ $actions ?? '' }}
              </div>
            </div>
          @endif
          
          @if(isset($filters))
            <div class="entity-list__filters">
              {{ $filters }}
            </div>
          @endif
          
          <div class="entity-list__content">
            @if(isset($items) && (is_countable($items) ? count($items) : 0) > 0)
              <div class="entity-list__items {{ $isReorderable ? 'entity-list__items--reorderable' : '' }}" 
                  @if($isReorderable) id="reorderable-items" data-reorder-url="{{ $reorderUrl }}" data-id-field="{{ $reorderItemIdField }}" @endif>
                {{ $slot }}
              </div>
              
              @if($isReorderable)
                <form id="reorder-form" method="POST" style="display: none;">
                  @csrf
                  <input type="hidden" name="item_ids" id="item-ids-input">
                </form>
              @endif
              
              @if(isset($pagination))
                <div class="entity-list__pagination">
                  {{ $pagination }}
                </div>
              @endif
            @else
              <div class="entity-list__empty">
                {{ $emptyMessage }}
              </div>
            @endif
          </div>
        </x-slot:content>
      </x-tabs>
    </div>
  @else
    @if($showHeader)
      <div class="entity-list__header">
        <h1 class="entity-list__title">{{ $title }}</h1>
        
        <div class="entity-list__actions">
          @if($isReorderable)
            <div id="reorder-buttons" style="display: none;" class="entity-list__reorder-buttons">
              <button type="button" id="save-reorder-button" class="btn btn--primary">
                {{ __('admin.save_order') }}
              </button>
              <button type="button" id="cancel-reorder-button" class="btn btn--secondary">
                {{ __('admin.cancel') }}
              </button>
            </div>
          @endif
          
          @if($createRoute)
            <a href="{{ $createRoute }}" class="btn btn--primary">
              {{ $createLabel }}
            </a>
          @endif
          
          {{ $actions ?? '' }}
        </div>
      </div>
    @endif
    
    @if(isset($filters))
      <div class="entity-list__filters">
        {{ $filters }}
      </div>
    @endif
    
    <div class="entity-list__content">
      @if(isset($items) && (is_countable($items) ? count($items) : 0) > 0)
        <div class="entity-list__items {{ $isReorderable ? 'entity-list__items--reorderable' : '' }}" 
             @if($isReorderable) id="reorderable-items" data-reorder-url="{{ $reorderUrl }}" data-id-field="{{ $reorderItemIdField }}" @endif>
          {{ $slot }}
        </div>
        
        @if($isReorderable)
          <form id="reorder-form" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="item_ids" id="item-ids-input">
          </form>
        @endif
        
        @if(isset($pagination))
          <div class="entity-list__pagination">
            {{ $pagination }}
          </div>
        @endif
      @else
        <div class="entity-list__empty">
          {{ $emptyMessage }}
        </div>
      @endif
    </div>
  @endif
</div>