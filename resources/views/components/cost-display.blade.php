@props(['cost', 'size' => 'sm'])

@if(!empty($cost))
  <div class="cost-display">
    @foreach(str_split(strtoupper($cost)) as $dice)
      @if($dice === 'R')
        <x-icon-dice type="red" :size="$size" />
      @elseif($dice === 'G')
        <x-icon-dice type="green" :size="$size" />
      @elseif($dice === 'B')
        <x-icon-dice type="blue" :size="$size" />
      @endif
    @endforeach
  </div>
@endif