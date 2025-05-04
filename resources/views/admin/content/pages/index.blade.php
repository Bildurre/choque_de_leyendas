<x-admin-layout
  title='Gestión de Páginas'
  headerTitle='Páginas de Contenido'
  containerTitle='Gestión de Páginas'
  subtitle='Administra las páginas de contenido de la web'
  :createRoute="route('admin.content.pages.create')"
>

  @if($pages->isEmpty())
    <div class="empty-state">
      <p>No hay páginas de contenido creadas todavía.</p>
      <a href="{{ route('admin.content.pages.create') }}" class="btn btn--filled">Crear Primera Página</a>
    </div>
  @else
    <div class="content-pages-list">
      <div class="content-pages-list__header">
        <div class="content-pages-list__cell">Título</div>
        <div class="content-pages-list__cell">Slug</div>
        <div class="content-pages-list__cell">Estado</div>
        <div class="content-pages-list__cell">Menú</div>
        <div class="content-pages-list__cell">Orden</div>
        <div class="content-pages-list__cell">Acciones</div>
      </div>
      
      @foreach($pages as $page)
        <div class="content-pages-list__item @if($page->parent_slug) content-pages-list__item--child @endif">
          <div class="content-pages-list__cell">
            @if($page->parent_slug)
              <span class="parent-indicator">↳</span>
            @endif
            {{ $page->title }}
          </div>
          <div class="content-pages-list__cell">{{ $page->slug }}</div>
          <div class="content-pages-list__cell">
            <span class="status-badge {{ $page->is_published ? 'status-badge--published' : 'status-badge--draft' }}">
              {{ $page->is_published ? 'Publicada' : 'Borrador' }}
            </span>
          </div>
          <div class="content-pages-list__cell">
            <span class="status-badge {{ $page->show_in_menu ? 'status-badge--enabled' : 'status-badge--disabled' }}">
              {{ $page->show_in_menu ? 'Visible' : 'Oculta' }}
            </span>
          </div>
          <div class="content-pages-list__cell">{{ $page->order }}</div>
          <div class="content-pages-list__cell">
            <div class="action-buttons">
              <a href="{{ route('admin.content.pages.edit', $page) }}" class="action-btn action-btn--edit">
                <x-core.icon name="edit" />
              </a>
              
              <form action="{{ route('admin.content.pages.destroy', $page) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn action-btn--delete" data-name="{{ $page->title }}">
                  <x-core.icon name="delete" />
                </button>
              </form>
              
              @if($page->is_published)
                <a href="{{ route('content.show', $page->slug) }}" class="action-btn action-btn--view" target="_blank">
                  <x-core.icon name="view" />
                </a>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif

</x-admin-layout>