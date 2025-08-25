<x-blocks.block :block="$block">
  <div class="block__content">    
    <x-blocks.titles :block="$block">
      @if(isset($actions))
        <x-slot:actions>
          {{ $actions }}
        </x-slot:actions>
      @endif
    </x-blocks.titles>
  </div>
</x-blocks.block>