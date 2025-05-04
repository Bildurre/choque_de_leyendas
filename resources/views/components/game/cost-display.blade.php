@props(['cost' => ''])

@php
  $costArray = app(App\Services\Game\CostTranslatorService::class)->translateToArray($cost);
@endphp

<div {{ $attributes->merge(['class' => 'cost-display']) }}>
  @foreach($costArray as $color => $count)
    @if($count > 0)
      @for($i = 0; $i < $count; $i++)
        <x-game.game-dice variant="mono-{{ $color }}" size="md" class="cost-dice" />
      @endfor
    @endif
  @endforeach
</div>