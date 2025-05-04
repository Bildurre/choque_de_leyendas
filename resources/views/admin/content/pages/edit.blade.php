<x-admin-layout
  title='Editar Página'
  headerTitle='Páginas de Contenido'
  containerTitle="Editar: {{ $page->title }}"
  subtitle='Edita los detalles de la página y gestiona sus secciones'
  :backRoute="route('admin.content.pages.index')"
>

  <div class="content-edit-layout">
    <div class="content-edit-sidebar">
      <div class="content-edit-sidebar__header">
        <h3>Detalles de la Página</h3>
      </div>
      
      <form action="{{ route('admin.content.pages.update', $page) }}" method="POST" enctype="multipart/form-data" class="content-page-form">
        @csrf
        @method('PUT')
        
        <div class="sidebar-form-container">
          <x-form.translate-field 
            name="title" 
            label="Título" 
            :value="$page->getTranslations('title')" 
            required
            maxlength="255" 
          />
          
          <x-form.field 
            name="slug" 
            label="Slug" 
            :value="$page->slug"
            maxlength="255" 
          />
          
          <x-form.translate-textarea 
            name="meta_description" 
            label="Meta Descripción" 
            :value="$page->getTranslations('meta_description')"
            rows="3"
          />
          
          <x-form.select
            name="parent_slug" 
            label="Página Padre" 
            placeholder="Sin página padre"
            :value="$page->parent_slug"
            :options="$pages->pluck('title', 'slug')->toArray()"
          />
          
          <x-form.field 
            name="order" 
            label="Orden" 
            type="number"
            :value="$page->order" 
            min="0"
          />
          
          <x-form.checkbox
            name="is_published" 
            label="Publicada"
            :checked="$page->is_published"
          />
          
          <x-form.checkbox
            name="show_in_menu" 
            label="Visible en menú"
            :checked="$page->show_in_menu"
          />
          
          <x-form.image-uploader
            name="background_image" 
            label="Imagen de Fondo" 
            :currentImage="$page->background_image"
          />
          
          <div class="form-buttons">
            <button type="submit" class="btn btn--filled">Guardar Cambios</button>
            
            @if($page->is_published)
              <a href="{{ route('content.show', $page->slug) }}" class="btn" target="_blank">
                Ver Página
              </a>
            @endif
          </div>
        </div>
      </form>
    </div>
    
    <div class="content-edit-main">
      <div class="content-edit-main__header">
        <h3>Secciones de Contenido</h3>
        <a href="{{ route('admin.content.sections.create', $page) }}" class="btn btn--sm">
          <x-core.icon name="plus" />
          Añadir Sección
        </a>
      </div>
      
      <div class="content-sections-container" id="sections-container" data-reorder-url="{{ route('admin.content.sections.reorder', $page) }}">
        @if($page->sections->isEmpty())
          <div class="empty-state">
            <p>Esta página no tiene secciones todavía.</p>
            <a href="{{ route('admin.content.sections.create', $page) }}" class="btn btn--filled btn--sm">
              Crear Primera Sección
            </a>
          </div>
        @else
          @foreach($page->sections as $section)
            <div class="content-section-item" data-id="{{ $section->id }}">
              <div class="content-section-item__header" style="background-color: {{ $section->background_color }}">
                <div class="content-section-item__drag-handle">
                  <x-core.icon name="drag" />
                </div>
                
                <h4 class="content-section-item__title">{{ $section->title }}</h4>
                
                <div class="content-section-item__actions">
                  <a href="{{ route('admin.content.sections.edit', [$page, $section]) }}" class="action-btn action-btn--edit action-btn--sm">
                    <x-core.icon name="edit" />
                  </a>
                  
                  <form action="{{ route('admin.content.sections.destroy', [$page, $section]) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn action-btn--delete action-btn--sm" data-name="{{ $section->title }}">
                      <x-core.icon name="delete" />
                    </button>
                  </form>
                  
                  <a href="{{ route('admin.content.blocks.create', [$page, $section]) }}" class="action-btn action-btn--add action-btn--sm">
                    <x-core.icon name="plus" />
                  </a>
                  
                  <button type="button" class="action-btn action-btn--toggle action-btn--sm section-toggle">
                    <x-core.icon name="chevron-down" class="icon-down" />
                    <x-core.icon name="chevron-up" class="icon-up" />
                  </button>
                </div>
              </div>
              
              <div class="content-section-item__content">
                <div class="content-blocks-container" data-section-id="{{ $section->id }}" data-reorder-url="{{ route('admin.content.blocks.reorder', [$page, $section]) }}">
                  @if($section->blocks->isEmpty())
                    <div class="empty-state empty-state--sm">
                      <p>Esta sección no tiene bloques.</p>
                      <a href="{{ route('admin.content.blocks.create', [$page, $section]) }}" class="btn btn--filled btn--sm">
                        Añadir Bloque
                      </a>
                    </div>
                  @else
                    @foreach($section->blocks as $block)
                      <div class="content-block-item" data-id="{{ $block->id }}">
                        <div class="content-block-item__drag-handle">
                          <x-core.icon name="drag" />
                        </div>
                        
                        <div class="content-block-item__type">
                          <span class="badge badge--{{ $block->type }}">{{ ContentBlock::getTypes()[$block->type] }}</span>
                        </div>
                        
                        <div class="content-block-item__content">
                          @if($block->type === 'text')
                            <div class="block-preview block-preview--text">
                              {!! Str::limit($block->getTranslation('content', app()->getLocale()), 100) !!}
                            </div>
                          @elseif($block->type === 'title' || $block->type === 'subtitle' || $block->type === 'header')
                            <div class="block-preview block-preview--title">
                              {{ $block->getTranslation('content', app()->getLocale()) }}
                            </div>
                          @elseif($block->type === 'image')
                            <div class="block-preview block-preview--image">
                              @if($block->image)
                                <img src="{{ asset('storage/' . $block->image) }}" alt="Preview">
                              @else
                                [Sin imagen]
                              @endif
                            </div>
                          @elseif($block->type === 'model_list')
                            <div class="block-preview block-preview--model-list">
                              Lista de {{ ContentBlock::getModelTypes()[$block->model_type] ?? 'modelos' }}
                            </div>
                          @else
                            <div class="block-preview">
                              {{ $block->getTranslation('content', app()->getLocale()) }}
                            </div>
                          @endif
                        </div>
                        
                        <div class="content-block-item__actions">
                          <a href="{{ route('admin.content.blocks.edit', [$page, $section, $block]) }}" class="action-btn action-btn--edit action-btn--sm">
                            <x-core.icon name="edit" />
                          </a>
                          
                          <form action="{{ route('admin.content.blocks.destroy', [$page, $section, $block]) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn action-btn--delete action-btn--sm" data-name="bloque">
                              <x-core.icon name="delete" />
                            </button>
                          </form>
                        </div>
                      </div>
                    @endforeach
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Toggle section content
        document.querySelectorAll('.section-toggle').forEach(button => {
          button.addEventListener('click', function() {
            const sectionItem = this.closest('.content-section-item');
            sectionItem.classList.toggle('content-section-item--expanded');
          });
        });
        
        // Initialize section dragging with Sortable.js
        if (typeof Sortable !== 'undefined') {
          const sectionsContainer = document.getElementById('sections-container');
          
          if (sectionsContainer) {
            new Sortable(sectionsContainer, {
              handle: '.content-section-item__drag-handle',
              animation: 150,
              onEnd: function(evt) {
                const sections = Array.from(sectionsContainer.querySelectorAll('.content-section-item')).map(item => item.dataset.id);
                
                fetch(sectionsContainer.dataset.reorderUrl, {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                  },
                  body: JSON.stringify({ ids: sections })
                })
                .then(response => response.json())
                .then(data => {
                  if (!data.success) {
                    console.error('Error reordering sections:', data.message);
                  }
                })
                .catch(error => {
                  console.error('Error reordering sections:', error);
                });
              }
            });
          }
          
          // Initialize block dragging
          document.querySelectorAll('.content-blocks-container').forEach(container => {
            new Sortable(container, {
              handle: '.content-block-item__drag-handle',
              animation: 150,
              onEnd: function(evt) {
                const blocks = Array.from(container.querySelectorAll('.content-block-item')).map(item => item.dataset.id);
                
                fetch(container.dataset.reorderUrl, {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                  },
                  body: JSON.stringify({ ids: blocks })
                })
                .then(response => response.json())
                .then(data => {
                  if (!data.success) {
                    console.error('Error reordering blocks:', data.message);
                  }
                })
                .catch(error => {
                  console.error('Error reordering blocks:', error);
                });
              }
            });
          });
        }
      });
    </script>
  @endpush

</x-admin-layout>