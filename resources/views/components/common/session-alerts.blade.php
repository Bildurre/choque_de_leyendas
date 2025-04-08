<!-- resources/views/components/session-alerts.blade.php -->
@if(session('success'))
  <x-common.alert type="success">
    {{ session('success') }}
  </x-common.alert>
@endif

@if(session('error'))
  <x-common.alert type="danger">
    {{ session('error') }}
  </x-common.alert>
@endif

@if(session('warning'))
  <x-common.alert type="warning">
    {{ session('warning') }}
  </x-common.alert>
@endif

@if(session('info'))
  <x-common.alert type="info">
    {{ session('info') }}
  </x-common.alert>
@endif

@if(session('status'))
  <x-common.alert type="info">
    {{ session('status') }}
  </x-common.alert>
@endif