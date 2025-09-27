@extends('adminlte::page')

@section('title', 'Dashboard')

@section('usermenu_body')
    <a href="{{ route('user.profile') }}" class="dropdown-item">
        <i class="fas fa-user mr-2"></i> profile
    </a>
@endsection

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
@stop

@section('css')
@stop

@section('js')
@stop