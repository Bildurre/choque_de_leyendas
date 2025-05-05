@props(['name'])

@error($name)
  <div {{ $attributes->merge(['class' => 'form-error']) }}>
    {{ $message }}
  </div>
@enderror