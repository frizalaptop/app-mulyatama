@extends('layouts.app')

@section('content_header')
    <h1>User List</h1>
@stop

@section('content')

    <!-- Modal Add User -->
    <form id="formAddUser" method="POST" action="{{ route('user.add') }}" >
        @csrf
        <x-adminlte-modal id="modalAddUser" title="Tambah User" theme="blue" size='lg' v-centered disable-x="false">

            <div class="row">
                <!-- akun -->
                <div class="col-md-6">
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
                </div>

                <!-- profil -->
                <div class="col-md-6">
                    <x-adminlte-input name="company" placeholder="Perusahaan" label-class="text-lightblue" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-building text-lightblue"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="wa" placeholder="Whatsapp" label-class="text-lightblue">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fab fa-whatsapp text-lightblue"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="telegram" placeholder="Telegram" label-class="text-lightblue">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fab fa-telegram-plane text-lightblue"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="address" placeholder="Alamat" label-class="text-lightblue" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-home text-lightblue"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <x-slot name="footerSlot">
                <div class="d-flex justify-content-end w-100">
                    <button type="submit" class="btn btn-primary btn-flat">
                        <i class="fas fa-lg fa-save"></i> Simpan
                    </button>
                    <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal"/>
                </div>
            </x-slot>

        </x-adminlte-modal>
    </form>

    

    <!-- Modal Edit User -->
    <form id="formEditUser" method="POST" data-cek="true">
        @csrf
        @method('PUT')
        <x-adminlte-modal id="modalEditUser" title="Edit User" theme="blue" size='lg' v-centered disable-x="false">

            <div class="row">
                <!-- akun -->
                <div class="col-md-6">
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

                    <x-adminlte-input name="password" type="password" placeholder="Password (biarkan kosong jika tidak diganti)" label-class="text-lightblue" id="edit_password">
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
                                    <i class="fas fa-user-cog text-lightblue"></i>
                                </div>
                            </x-slot>
                            <x-adminlte-options :options="['Admin'=> 'Admin', 'User'=> 'User']"/>
                        </x-adminlte-select>
                    </div>
                </div>

                <!-- profil -->
                <div class="col-md-6">
                    <x-adminlte-input name="company" placeholder="Perusahaan" label-class="text-lightblue" id="edit_company" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-building text-lightblue"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="wa" placeholder="Whatsapp" label-class="text-lightblue" id="edit_wa">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fab fa-whatsapp text-lightblue"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="telegram" placeholder="Telegram" label-class="text-lightblue" id="edit_telegram">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fab fa-telegram-plane text-lightblue"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="address" placeholder="Alamat" label-class="text-lightblue" id="edit_address" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-home text-lightblue"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <x-slot name="footerSlot">
                <div class="d-flex justify-content-end w-100">
                    <button type="submit" class="btn btn-primary btn-flat">
                        <i class="fas fa-lg fa-save"></i>Update
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