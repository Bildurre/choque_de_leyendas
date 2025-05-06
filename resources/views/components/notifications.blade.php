@if(session('success'))
    <x-notification type="success">
        {{ session('success') }}
    </x-notification>
@endif

@if(session('error'))
    <x-notification type="error">
        {{ session('error') }}
    </x-notification>
@endif

@if(session('warning'))
    <x-notification type="warning">
        {{ session('warning') }}
    </x-notification>
@endif

@if(session('info'))
    <x-notification type="info">
        {{ session('info') }}
    </x-notification>
@endif