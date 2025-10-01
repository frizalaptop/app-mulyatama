@extends('layouts.app')

@section('content_header')
    <h1>User List</h1>
@stop

@section('content')

    <!-- Modal Add User -->
    <form id="formAddUser" method="POST" action="{{ route('user.add') }}" >
        @csrf
        <x-adminlte-modal id="modalAddUser" title="Tambah User" theme="blue" size='lg' v-centered disable-x="false">

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
                    <button type="submit" class="btn btn-success btn-flat">
                        <i class="fas fa-lg fa-save"></i> Simpan
                    </button>
                    <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal"/>
                </div>
            </x-slot>

        </x-adminlte-modal>
    </form>

    

    <!-- Modal Edit User -->
    <form id="formEditUser" method="POST">
        @csrf
        @method('PUT')
        <x-adminlte-modal id="modalEditUser" title="Edit User" theme="blue" size='lg' v-centered disable-x="false">

            <x-adminlte-input name="name" placeholder="Nama Lengkap" label-class="text-lightblue" id="edit_name">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-user text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input name="email" type="email" placeholder="Alamat Email" label-class="text-lightblue" id="edit_email">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-envelope text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input name="password" type="password" placeholder="Password" label-class="text-lightblue" id="edit_password">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-lock text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input name="password_confirmation" type="password" placeholder="Konfirmasi Password" label-class="text-lightblue" id="edit_password_confirmation">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-check text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <div class="d-flex justify-content-between">

                <x-adminlte-select name="aktifasi" label="Status" id="edit_aktifasi">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-user-check text-lightblue"></i>
                        </div>
                    </x-slot>
                    <x-adminlte-options :options="['Aktif'=> 'Aktif', 'Nonaktif' => 'Nonaktif']"/>
                </x-adminlte-select>

                <x-adminlte-select name="akses" label="Hak Akses" id="edit_akses">
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
                    <button type="submit" class="btn btn-success btn-flat">
                        <i class="fas fa-lg fa-save"></i> Simpan
                    </button>
                    <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal"/>
                </div>
            </x-slot>

        </x-adminlte-modal>
    </form>

    

    <x-adminlte-card theme="light">
        <!-- Table -->
        <table id="tableUserList" class="table table-bordered table-hover dataTable dtr-inline"></table>
    </x-adminlte-card>
@stop

@section('css')
@stop

@section('js')
<script>
    window.routes = {
        getUser: "{{ route('user.get', ['id' => ':id']) }}",
        dataTable: "{{ route('user.datatable') }}",
    };
</script>
@stop