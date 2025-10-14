@extends('adminlte::page')

@section('usermenu_body')
    <a href="{{ route('profil.index') }}" class="dropdown-item">
        <i class="bi bi-person mr-3"></i></i> Profil
    </a>
    <div class="dropdown-divider"></div>

    <a href="#" class="dropdown-item"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right mr-3"></i> Keluar
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
@endsection

@section('content_header')
    <div class="app-content-header">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="mb-0">{{ $title ?? 'Dashboard' }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>

                    @php
                        $segments = request()->segments();
                        if (empty($segments)) {
                            $segments = [ Auth::user()->getRoleNames()->first() ];
                        }
                    @endphp

                    @foreach ($segments as $index => $segment)
                        @php
                            // ubah "user-list" jadi "User List", "create-new" jadi "Create New", dll
                            $label = collect(explode('-', $segment))
                                        ->map(fn($word) => ucfirst($word))
                                        ->join(' ');

                            // gabungkan segmen untuk bikin URL parsial
                            $url = url(implode('/', array_slice($segments, 0, $index + 1)));
                        @endphp

                        @if ($index + 1 < count($segments))
                            <li class="breadcrumb-item">
                                <a href="{{ $url }}">{{ $label }}</a>
                            </li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                        @endif
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    @yield('content')
@stop

@section('footer')
    <div class="text-center text-md-left">
        Copyright © 2014 - 2025 | All rights reserved. <br>
        <a href="/">Nama Situs - Tagline singkat situs</a>

    </div>

    <div class="text-center text-md-right">
        Versi 1.0.50 <br>
        Render: <span id="microtime"></span> detik. <br>
    </div>
@stop

@section('adminlte_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('/vendor/sweetalert2/sweetalert2.min.css') }}">             
    @stack('css')
    <link rel="stylesheet" href="{{ asset('page/custom.min.css') }}">
@stop

@section('adminlte_js')
    <script src="{{ asset('/vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <script>
        // Pasang csrf token untuk semua permintaan ajax
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
    </script>
    
    <script src="{{ asset('page/beranda.min.js') }}"></script>

    <script>
        $(function () {
            const renderTime=performance.now();
            document.getElementById("microtime").textContent=(renderTime/1e3).toFixed(2)
        })
    </script>

    @stack('js')
@stop