@extends('layouts.admin')

@section('title', $title ?? 'Admin Panel')

@section('header-title', $headerTitle ?? 'Admin Panel')

@section('content')
<x-admin.admin-container
  :title="$containerTitle ?? ($title ?? 'Admin Panel')"
  :subtitle="$subtitle ?? ''"
  :back_route="$backRoute ?? null"
  :back_label="$backLabel ?? 'Volver'"
  :create_route="$createRoute ?? null"
  :create_label="$createLabel ?? '+ Nuevo'"
>
  @yield('page-content')
</x-admin.admin-container>
@endsection