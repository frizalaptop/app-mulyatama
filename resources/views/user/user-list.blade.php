@extends('layouts.app')

@section('content_header')
    <h1>User List</h1>
@stop

@section('content')
    <!-- Modal Add User -->
    <form method="POST" action="{{ route('user.add') }}" >
        @csrf
        <x-adminlte-modal id="modalAdd" title="Tambah User" theme="blue" icon="fas fa-bolt" size='lg' disable-animations>

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

            <x-slot name="footerSlot">
                <div class="d-flex justify-content-end w-100">
                    <x-adminlte-button class="btn-flat" type="submit" label="Simpan" theme="success" icon="fas fa-lg fa-save"/>
                    <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal"/>
                </div>
            </x-slot>
            
        </x-adminlte-modal>
    </form>

    <!-- Modal Add User Button -->
    <div class="row mb-2">
        <x-adminlte-button label="Add" data-toggle="modal" data-target="#modalAdd" class="bg-teal"/>
    </div>

    <!-- Modal Edit User -->
    @foreach($users as $user)
    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <x-adminlte-modal id="modalEdit-{{ $user->id }}" title="Edit User" theme="warning" icon="fas fa-edit" size='lg'>

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

            <x-slot name="footerSlot">
                <div class="d-flex justify-content-end w-100">
                    <x-adminlte-button class="btn-flat" type="submit" label="Update" theme="success" icon="fas fa-lg fa-save"/>
                    <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal"/>
                </div>
            </x-slot>
        </x-adminlte-modal>
    </form>
    @endforeach


    <!-- Table -->
    <x-adminlte-datatable id="table1" :heads="$heads">
        @foreach($config['data'] as $row)
            <tr>
                @for ($i = 0; $i < count($row); $i++)
                    @if ($i === 8) {{-- kolom aksi --}}
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow"
                                    title="Edit" data-toggle="modal"
                                    data-target="#modalEdit-{{ $row[$i] }}">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>

                            <!-- Tombol Delete -->
                            <form action="" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete"
                                        onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>
                            </form>

                            <!-- Tombol Details -->
                            <button class="btn btn-xs btn-default text-teal mx-1 shadow"
                                    title="Details" data-toggle="modal"
                                    data-target="#modalDetail-{{ $row[$i] }}">
                                <i class="fa fa-lg fa-fw fa-eye"></i>
                            </button>
                        </td>
                    @else
                        <td>{!! $row[$i] !!}</td>
                    @endif
                @endfor
            </tr>
        @endforeach
    </x-adminlte-datatable>
@stop