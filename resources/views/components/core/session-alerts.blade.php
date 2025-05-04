@if(session('success'))
  <x-core.alert type="success">
    {{ session('success') }}
  </x-core.alert>
@endif

@if(session('error'))
  <x-core.alert type="danger">
    {{ session('error') }}
  </x-core.alert>
@endif

@if(session('warning'))
  <x-core.alert type="warning">
    {{ session('warning') }}
  </x-core.alert>
@endif

@if(session('info'))
  <x-core.alert type="info">
    {{ session('info') }}
  </x-core.alert>
@endif

@if(session('status'))
  <x-core.alert type="info">
    {{ session('status') }}
  </x-core.alert>
@endif