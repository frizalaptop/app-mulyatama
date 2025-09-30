@extends('layouts.app')

@section('content_header')
    <h1>User List</h1>
@stop

@section('content')
    <!-- Modal Add User -->
    <form method="POST" action="{{ route('user.add') }}" >
        @csrf
        <x-adminlte-modal id="modalAdd" title="Tambah User" theme="blue" size='lg'>

            <x-adminlte-input name="name" placeholder="Nama Lengkap" label-class="text-lightblue">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-user text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
    
            <x-adminlte-input name="email" type="email" placeholder="Alamat Email" label-class="text-lightblue">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-envelope text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
    
            <x-adminlte-input name="password" type="password" placeholder="Password" label-class="text-lightblue">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-lock text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
    
            <x-adminlte-input name="password_confirmation" type="password" placeholder="Konfirmasi Password" label-class="text-lightblue">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-check text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <div class="d-flex justify-content-between">

                <x-adminlte-select name="aktifasi" label="Status">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-user-check text-lightblue"></i>
                        </div>
                    </x-slot>
                    <x-adminlte-options :options="['Aktif'=> 'Aktif', 'Nonaktif' => 'Nonaktif']"/>
                </x-adminlte-select>

                <x-adminlte-select name="akses" label="Hak Akses">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-user-setting text-lightblue"></i>
                        </div>
                    </x-slot>
                    <x-adminlte-options :options="['Aktif', 'Nonaktif']"/>
                </x-adminlte-select>

            </div>

            <x-slot name="footerSlot">
                <div class="d-flex justify-content-end w-100">
                    <x-adminlte-button class="btn-flat" type="submit" label="Simpan" theme="success" icon="fas fa-lg fa-save"/>
                    <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal"/>
                </div>
            </x-slot>

        </x-adminlte-modal>
    </form>

    

    <!-- Modal Edit User -->
    @foreach($users as $user)
    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <x-adminlte-modal id="modalEdit-{{ $user->id }}" title="Edit User" theme="warning" size='lg'>

            <x-adminlte-input name="name" value="{{ $user->name }}" placeholder="Nama Lengkap" label-class="text-lightblue">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-user text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input name="email" type="email" value="{{ $user->email }}" placeholder="Alamat Email" label-class="text-lightblue">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-envelope text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input name="password" type="password" placeholder="Password (kosongkan jika tidak diubah)" label-class="text-lightblue">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-lock text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input name="password_confirmation" type="password" placeholder="Konfirmasi Password" label-class="text-lightblue">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-check text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <div class="d-flex justify-content-between">

                <x-adminlte-select name="aktifasi" label="Status">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-user-check text-lightblue"></i>
                        </div>
                    </x-slot>
                    <x-adminlte-options 
                        :options="['Aktif'=> 'Aktif', 'Nonaktif' => 'Nonaktif']" 
                        :selected="$user->active ? 'Aktif' : 'Nonaktif'"
                    />
                </x-adminlte-select>

                <x-adminlte-select name="akses" label="Hak Akses">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-user-setting text-lightblue"></i>
                        </div>
                    </x-slot>
                    <x-adminlte-options :options="['Aktif', 'Nonaktif']"/>
                </x-adminlte-select>

            </div>

            <x-slot name="footerSlot">
                <div class="d-flex justify-content-end w-100">
                    <x-adminlte-button class="btn-flat" type="submit" label="Update" theme="success" icon="fas fa-lg fa-save"/>
                    <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal"/>
                </div>
            </x-slot>
        </x-adminlte-modal>
    </form>
    @endforeach


    <x-adminlte-card theme="light">

        <!-- Table -->
        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config">
        </x-adminlte-datatable>

    </x-adminlte-card>
@stop

@section('css')
    <style>
        /* .modal .modal-title {
            text-align: center;
            width: 100%;
        } */
    </style>
@stop