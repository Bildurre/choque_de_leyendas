@props([
  'id' => uniqid('chart-'),
  'type' => 'bar', // bar, line, pie, doughnut
  'height' => '300px',
  'data' => [],
  'options' => [],
])

<div class="dashboard-chart">
  <canvas 
    id="{{ $id }}" 
    data-chart-type="{{ $type }}"
    data-chart-data="{{ json_encode($data) }}"
    data-chart-options="{{ json_encode($options) }}"
    style="height: {{ $height }}; max-height: {{ $height }}"
  ></canvas>
</div>