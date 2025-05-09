<x-admin-layout>
  <x-entity.list 
    title="{{ __('pages.plural') }}"
    :create-route="!$trashed ? route('admin.pages.create') : null"
    :create-label="__('pages.create')"
    :items="$pages"
  >
    <x-slot:filters>
      <x-tabs>
        <x-slot:header>
          <x-tab-item 
            id="active" 
            :active="!$trashed" 
            :href="route('admin.pages.index')"
            icon="list"
          >
            {{ __('admin.active_items') }}
          </x-tab-item>
          
          <x-tab-item 
            id="trashed" 
            :active="$trashed" 
            :href="route('admin.pages.index', ['trashed' => 1])"
            icon="trash"
          >
            {{ __('admin.trashed_items') }}
          </x-tab-item>
        </x-slot:header>
      </x-tabs>
    </x-slot:filters>
    
    @foreach($pages as $page)
      @if($trashed)
        <x-entity.list-card 
          :title="$page->title"
          :delete-route="route('admin.pages.force-delete', $page->id)"
          :confirm-message="__('pages.confirm_force_delete')"
        >
          <x-slot:actions>
            <form action="{{ route('admin.pages.restore', $page->id) }}" method="POST" class="action-button-form">
              @csrf
              <button 
                type="submit" 
                class="action-button action-button--restore"
                title="{{ __('admin.restore') }}"
              >
                <x-icon name="refresh" size="sm" class="action-button__icon" />
              </button>
            </form>
          </x-slot:actions>
          
          <x-slot:badges>
            @if($page->is_published)
              <x-badge variant="success">
                {{ __('pages.published') }}
              </x-badge>
            @else
              <x-badge variant="danger">
                {{ __('pages.draft') }}
              </x-badge>
            @endif
            
            @if($page->template && $page->template !== 'default')
              <x-badge variant="info">
                {{ $page->template }}
              </x-badge>
            @endif
            
            @if($page->children_count > 0)
              <x-badge variant="primary">
                {{ __('pages.children_count', ['count' => $page->children_count]) }}
              </x-badge>
            @endif
            
            <x-badge variant="danger">
              {{ __('admin.deleted_at', ['date' => $page->deleted_at->format('d/m/Y H:i')]) }}
            </x-badge>
          </x-slot:badges>
          
          <x-slot:meta>
            <div class="list-card-meta">
              <div class="list-card-meta__item">
                <span class="list-card-meta__label">{{ __('pages.slug') }}:</span>
                <span class="list-card-meta__value">{{ $page->getTranslation('slug', app()->getLocale()) }}</span>
              </div>
              
              @if($page->parent)
              <div class="list-card-meta__item">
                <span class="list-card-meta__label">{{ __('pages.parent') }}:</span>
                <span class="list-card-meta__value">{{ $page->parent->title }}</span>
              </div>
              @endif
            </div>
          </x-slot:meta>
          
          @if($page->description)
            <div class="list-card-description">
              {{ Str::limit(strip_tags($page->description), 150) }}
            </div>
          @endif
        </x-entity.list-card>
      @else
        <x-entity.list-card 
          :title="$page->title"
          :edit-route="route('admin.pages.edit', $page)"
          :delete-route="route('admin.pages.destroy', $page)"
          :view-route="localized_route('content.page', $page, app()->getLocale())"
          :confirm-message="__('pages.confirm_delete')"
        >
          <x-slot:badges>
            @if($page->is_published)
              <x-badge variant="success">
                {{ __('pages.published') }}
              </x-badge>
            @else
              <x-badge variant="danger">
                {{ __('pages.draft') }}
              </x-badge>
            @endif
            
            @if($page->template && $page->template !== 'default')
              <x-badge variant="info">
                {{ $page->template }}
              </x-badge>
            @endif
            
            @if($page->children_count > 0)
              <x-badge variant="primary">
                {{ __('pages.children_count', ['count' => $page->children_count]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          <x-slot:meta>
            <div class="list-card-meta">
              <div class="list-card-meta__item">
                <span class="list-card-meta__label">{{ __('pages.slug') }}:</span>
                <span class="list-card-meta__value">{{ $page->getTranslation('slug', app()->getLocale()) }}</span>
              </div>
              
              @if($page->parent)
              <div class="list-card-meta__item">
                <span class="list-card-meta__label">{{ __('pages.parent') }}:</span>
                <span class="list-card-meta__value">{{ $page->parent->title }}</span>
              </div>
              @endif
            </div>
          </x-slot:meta>
          
          @if($page->description)
            <div class="list-card-description">
              {{ Str::limit(strip_tags($page->description), 150) }}
            </div>
          @endif
        </x-entity.list-card>
      @endif
    @endforeach
    
    <x-slot:pagination>
      {{ $pages->appends(['trashed' => $trashed ? 1 : null])->links() }}
    </x-slot:pagination>
  </x-entity.list>
</x-admin-layout>