@extends('layouts.admin')
@section('title', 'Hero Attribute Configuration')
@section('header-title', 'Hero Attribute Configuration')
@section('content')
<div class="hero-attributes-container">
  <div class="header-actions-bar">
    <div class="left-actions">
      <h1>Hero Attribute Configuration</h1>
      <p>Configure base attributes and total points for hero creation</p>
    </div>
  </div>
@if(session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif

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
          $configuration->total_points + 
          $configuration->base_agility + 
          $configuration->base_mental + 
          $configuration->base_will + 
          $configuration->base_strength + 
          $configuration->base_armor 
        }}
      </td>
    </tr>
  </table>
</div>
  <form action="{{ route('admin.hero-attributes.update') }}" method="POST" class="hero-attributes-form">
    @csrf
    @method('PUT')
<div class="form-card">
  <div class="form-section">
    <div class="attributes-grid">
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
        <div class="form-group">
          <label for="{{ $key }}" class="form-label">
            {{ $label }} Base <span class="required">*</span>
          </label>
          <input 
            type="number" 
            id="{{ $key }}" 
            name="{{ $key }}" 
            class="form-input @error($key) is-invalid @enderror" 
            value="{{ old($key, $configuration->$key) }}" 
            min="0" 
            required
          >
          @error($key)
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      @endforeach
    </div>

    <div class="form-group total-points-group">
      <label for="total_points" class="form-label">
        Total Points <span class="required">*</span>
      </label>
      <input 
        type="number" 
        id="total_points" 
        name="total_points" 
        class="form-input @error('total_points') is-invalid @enderror" 
        value="{{ old('total_points', $configuration->total_points) }}" 
        min="1" 
        required
      >
      @error('total_points')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-text">
        The maximum number of additional points that can be distributed in hero creation.
      </small>
    </div>

    @error('base_points')
      <div class="alert alert-danger">
        {{ $message }}
      </div>
    @enderror
  </div>
  
  <div class="form-actions">
    <button type="submit" class="btn btn-primary">Save Configuration</button>
  </div>
</div>
  </form>
  
</div>
@endsection