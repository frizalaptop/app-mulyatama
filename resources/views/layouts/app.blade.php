@extends('adminlte::page')

@section('usermenu_body')
    <a href="{{ route('user.profile') }}" class="dropdown-item">
        <i class="fas fa-user mr-2"></i> profile
    </a>
@endsection

@section('content_header')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
@stop

@section('css')
@stop

@section('js')

@stop