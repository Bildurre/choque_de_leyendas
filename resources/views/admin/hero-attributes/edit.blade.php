@extends('admin.layouts.page', [
  'title' => 'Hero Attribute Configuration',
  'headerTitle' => 'Hero Attribute Configuration',
  'containerTitle' => 'Hero Attribute Configuration',
  'subtitle' => 'Configure base attributes and total points for hero creation'
])

@section('page-content')
  <x-session-alerts />

  <div class="configuration-info">
    <h2>Current Configuration Summary</h2>
    <table class="config-summary-table">
      <tr>
        <th>Base Attributes Total</th>
        <td>
          {{ 
            $configuration->base_agility + 
            $configuration->base_mental + 
            $configuration->base_will + 
            $configuration->base_strength + 
            $configuration->base_armor 
          }}
        </td>
      </tr>
      <tr>
        <th>Total Available Points</th>
        <td>{{ $configuration->total_points }}</td>
      </tr>
      <tr>
        <th>Max Attribute Points</th>
        <td>
          {{ 
            $configuration->total_points - 
            ($configuration->base_agility + 
            $configuration->base_mental + 
            $configuration->base_will + 
            $configuration->base_strength + 
            $configuration->base_armor)
          }}
        </td>
      </tr>
    </table>
  </div>

  <form action="{{ route('admin.hero-attributes.update') }}" method="POST" class="hero-attributes-form">
    @csrf
    @method('PUT')
    
    <x-form-card submit_label="Save Configuration">
      <div class="form-section">
        <div class="entities-grid">
          @php
            $attributes = [
              'base_agility' => 'Agility',
              'base_mental' => 'Mental',
              'base_will' => 'Will',
              'base_strength' => 'Strength',
              'base_armor' => 'Armor'
            ];
          @endphp

          @foreach($attributes as $key => $label)
            <x-form.field 
              :name="$key" 
              :label="$label . ' Base'" 
              type="number"
              :value="$configuration->$key" 
              :required="true" 
              min="0" 
            />
          @endforeach
        </div>

        <x-form.field 
          name="total_points" 
          label="Total Points" 
          type="number"
          :value="$configuration->total_points" 
          :required="true" 
          min="1" 
          help="The maximum number of additional points that can be distributed in hero creation."
          class="total-points-group"
        />

        @error('base_points')
          <div class="alert alert-danger">
            {{ $message }}
          </div>
        @enderror
      </div>
    </x-form-card>
  </form>
@endsection