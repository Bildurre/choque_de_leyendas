<x-admin-layout>
  <x-admin.page-header :title="__('pages.plural')" />
  
  <div class="page-content">
    <x-entity.list 
      :create-route="!$trashed ? route('admin.pages.create') : null"
      :create-label="__('pages.create')"
      :items="$pages"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.pages.index"
      :is-reorderable="!$trashed"
      :reorder-url="route('admin.pages.reorder')"
      reorder-item-id-field="id"
    >
    @foreach($pages as $page)
        <x-entity.list-card 
          :title="($page->parent ? '↳ ' : '') . $page->title"
          :view-route="!$trashed ? route('content.page', $page) : null"
          :edit-route="!$trashed ? route('admin.pages.edit', $page) : null"
          :delete-route="$trashed 
            ? route('admin.pages.force-delete', $page->id) 
            : route('admin.pages.destroy', $page)"
          :restore-route="$trashed ? route('admin.pages.restore', $page->id) : null"
          :toggle-published-route="!$trashed ? route('admin.pages.toggle-published', $page) : null"
          :is-published="$page->isPublished()"
          :confirm-message="$trashed 
            ? __('pages.confirm_force_delete') 
            : __('pages.confirm_delete')"
          data-id="{{ $page->id }}"
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
            
            @if(!$page->show_in_nav)
              <x-badge variant="info">
                {{ __('pages.hidden_from_nav') }}
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
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $page->deleted_at->format('d/m/Y H:i')]) }}
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
    @endforeach
      
      <x-slot:pagination>
        {{ $pages->appends(['trashed' => $trashed ? 1 : null])->links() }}
      </x-slot:pagination>
    </x-entity.list>
  </div>
</x-admin-layout>