<x-admin-layout
  title='Crear Página'
  headerTitle='Páginas de Contenido'
  containerTitle='Crear Página'
  subtitle='Crea una nueva página de contenido'
  :backRoute="route('admin.content.pages.index')"
>

  <form action="{{ route('admin.content.pages.store') }}" method="POST" enctype="multipart/form-data" class="content-page-form">
    @csrf
    
    <x-form.card submit_label="Crear Página" :cancel_route="route('admin.content.pages.index')">
      <div class="form-row">
        <x-form.translate-field 
          name="title" 
          label="Título de la Página" 
          required
          maxlength="255" 
        />
        
        <x-form.field 
          name="slug" 
          label="Slug" 
          placeholder="Dejar vacío para generarlo automáticamente"
          maxlength="255" 
        />
      </div>
      
      <x-form.translate-textarea 
        name="meta_description" 
        label="Meta Descripción (SEO)" 
        help="Breve descripción para los motores de búsqueda"
        rows="3"
      />
      
      <div class="form-row">
        <x-form.select
          name="parent_slug" 
          label="Página Padre" 
          placeholder="Selecciona una página padre (opcional)"
          :options="$pages->pluck('title', 'slug')->toArray()"
        />
        
        <x-form.field 
          name="order" 
          label="Orden" 
          type="number"
          :value="0" 
          min="0"
          help="Posición en el menú (menor número = primero)"
        />
      </div>
      
      <div class="form-row">
        <x-form.checkbox
          name="is_published" 
          label="Publicar página"
          help="Si se marca, la página será visible en la web"
        />
        
        <x-form.checkbox
          name="show_in_menu" 
          label="Mostrar en menú"
          :checked="true"
          help="Si se marca, la página aparecerá en el menú principal"
        />
      </div>
      
      <x-form.image-uploader
        name="background_image" 
        label="Imagen de Fondo" 
        help="Imagen de fondo para la página (opcional)"
      />
    </x-form.card>
  </form>

</x-admin-layout>