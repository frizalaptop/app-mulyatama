@extends('layouts.app')

@section('content_header')
    <h1>User Profile</h1>
@stop

@section('content')
<div class="row justify-content-center">
    <!-- Card Info -->
    <div class="col-md-4">
        <x-adminlte-card theme="blue" theme-mode="outline">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><b>Nama:</b> {{ auth()->user()->name }}</li>
                <li class="list-group-item"><b>Email:</b> {{ auth()->user()->email }}</li>
                <li class="list-group-item"><b>Dibuat:</b> {{ auth()->user()->created_at->format('d/m/Y H:i') }}</li>
            </ul>
        </x-adminlte-card>
    </div>

    <!-- Card Form Edit -->
    <div class="col-md-6">
        <x-adminlte-card theme="light" theme-mode="outline">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <x-adminlte-input name="name" value="{{ old('name', auth()->user()->name) }}"
                                  label-class="text-lightblue" placeholder="Masukkan nama baru">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-user text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input name="email" type="email" 
                                  value="{{ old('email', auth()->user()->email) }}"
                                  label-class="text-lightblue" placeholder="Masukkan email baru">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-envelope text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input name="password" type="password" 
                                  label-class="text-lightblue" placeholder="Isi jika ingin mengganti password">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-lock text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input name="password_confirmation" type="password" 
                                  label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-check text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <div class="d-flex justify-content-end">
                    <x-adminlte-button type="submit" label="Simpan Perubahan" theme="success" icon="fas fa-save"/>
                </div>
            </form>
        </x-adminlte-card>
    </div>
</div>
@stop

