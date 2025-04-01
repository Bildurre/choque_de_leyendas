@extends('layouts.admin')

@section('title', 'Facciones')

@section('header-title', 'Facciones')

@section('content')
<div class="faction-management-container">
  <div class="card">
    <div class="card-header">
      <h2>Facciones</h2>
      <a href="{{ route('admin.factions.create') }}" class="btn btn-primary">
        Create New Faction
      </a>
    </div>
    <div class="card-body">
      <div class="placeholder-content">
        <p>Faction management tools will be implemented here.</p>
        <p>Current features:</p>
        <ul>
          <li>Create new factions</li>
          <li>Edit existing factions</li>
          <li>View faction details</li>
          <li>Manage faction icons and colors</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection