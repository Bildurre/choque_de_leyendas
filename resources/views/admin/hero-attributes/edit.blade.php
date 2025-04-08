@extends('admin.layouts.page', [
  'title' => 'Hero Attribute Configuration',
  'headerTitle' => 'Hero Attribute Configuration',
  'containerTitle' => 'Hero Attribute Configuration',
  'subtitle' => 'Configure base attributes and total points for hero creation'
])

@section('page-content')
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

  <x-forms.hero-attributes-form :configuration="$configuration" />
@endsection