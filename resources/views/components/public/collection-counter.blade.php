@props(['count' => null])

<a href="{{ route('public.pdf-collection.index') }}" 
  class="collection-icon"
  title="{{ __('public.menu.downloads') }}">
  <x-icon name="pdf-download" />
  <span class="collection-counter" 
        data-collection-count="{{ $count }}"
        style="{{ $count > 0 ? '' : 'display: none;' }}">
    {{ $count }}
  </span>
</a>