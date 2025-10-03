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
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title ?? 'Dashboard' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>

                        @php
                            $segments = request()->segments();
                            if(empty($segments)){
                                $segments = [ Auth::user()->getRoleNames()->first()];
                            }
                        @endphp

                        @foreach ($segments as $index => $segment)
                            @php

                                // ubah "user-list" jadi "User List", "create-new" jadi "Create New", dll
                                $label = collect(explode('-', $segment))
                                            ->map(fn($word) => ucfirst($word))
                                            ->join(' ');
                            @endphp

                            <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>        
                            
                        @endforeach

                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
@stop

@section('footer')
    <div class="text-center text-md-left">
        Copyright © 2014 - 2025 | All rights reserved. <br>
        <a href="/">Nama Situs - Tagline singkat situs</a>

    </div>

    <div class="text-center text-md-right">
        Versi 1.0.50 <br>
        Render: <span id="microtime">3.52</span> detik. <br>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop