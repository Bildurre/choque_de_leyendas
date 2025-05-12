@props(['name'])

@if(isset($errors) && $errors->has($name))
  <div {{ $attributes->merge(['class' => 'form-error']) }}>
    {{ $errors->first($name) }}
  </div>
@endif