<x-admin-layout>
  <x-entity.list 
    title="{{ __('pages.plural') }}"
    :create-route="route('admin.pages.create')"
    :create-label="__('pages.create')"
    :items="$pages"
  >
    @foreach($pages as $page)
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
    @endforeach
    
    <x-slot:pagination>
      {{ $pages->links() }}
    </x-slot:pagination>
  </x-entity.list>
</x-admin-layout>