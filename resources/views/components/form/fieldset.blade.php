@props([
  'legend' => null,
])
<fieldset class="form-fieldset">
  @if ($legend)
    <legend>{{ $legend }}</legend>
  @endif
  <div class="form-grid">
    {{ $slot }}
  </div>
</fieldset>