@props(['cost' => '', 'showTotal' => true])

@php
  // Use the CostTranslatorService to translate the cost string
  $costArray = app(App\Services\CostTranslatorService::class)->translateToArray($cost);
  $totalCount = array_sum($costArray);
@endphp

<div {{ $attributes->merge(['class' => 'cost-display']) }}>
  @foreach($costArray as $color => $count)
    @if($count > 0)
      @for($i = 0; $i < $count; $i++)
        <x-game-dice variant="mono-{{ $color }}" size="sm" class="cost-dice" />
      @endfor
    @endif
  @endforeach
  
  @if($showTotal && $totalCount > 0)
    <span class="cost-total">{{ $totalCount }}</span>
  @endif
</div>