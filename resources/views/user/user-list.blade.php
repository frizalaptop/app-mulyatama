@extends('layouts.app')

@section('content_header')
    <h1>User List</h1>
@stop

@section('content')
    <!-- Modal -->
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

    <!-- Modal Button -->
    <div class="row mb-2">
        <x-adminlte-button label="Add" data-toggle="modal" data-target="#modalAdd" class="bg-teal"/>
    </div>

    <!-- Table -->
    <x-adminlte-datatable id="table1" :heads="$heads">
        @foreach($config['data'] as $row)
            <tr>
                @foreach($row as $cell)
                    <td>{!! $cell !!}</td>
                @endforeach
            </tr>
        @endforeach
    </x-adminlte-datatable>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
@stop