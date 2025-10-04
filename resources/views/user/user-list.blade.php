@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12 col-md-4 col-lg col-5">
            <div class="small-box bg-dark">
                <div class="overlay dark">
                    <span class="spinner-border wh-3rem" role="status"></span>
                </div>
                <div class="inner">
                    <h3 class="stat-value" data-key="user_total">0</h3>
                    <p>Total User</p>
                </div>
                <div class="icon"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg col-5">
            <div class="small-box bg-primary">
                <div class="overlay dark">
                    <span class="spinner-border wh-3rem" role="status"></span>
                </div>
                <div class="inner">
                    <h3 class="stat-value" data-key="user_aktif">0</h3>
                    <p>User Aktif</p>
                </div>
                <div class="icon"><i class="bi bi-person-fill-check"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg col-5">
            <div class="small-box bg-danger">
                <div class="overlay dark">
                    <span class="spinner-border wh-3rem" role="status"></span>
                </div>
                <div class="inner">
                    <h3 class="stat-value" data-key="user_nonaktif">0</h3>
                    <p>User Nonaktif</p>
                </div>
                <div class="icon"><i class="bi bi-person-fill-slash"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg col-5">
            <div class="small-box bg-success">
                <div class="overlay dark">
                    <span class="spinner-border wh-3rem" role="status"></span>
                </div>
                <div class="inner">
                    <h3 class="stat-value" data-key="user_admin">0</h3>
                    <p>User Admin</p>
                </div>
                <div class="icon"><i class="bi bi-person-fill"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg col-5">
            <div class="small-box bg-success">
                <div class="overlay dark">
                    <span class="spinner-border wh-3rem" role="status"></span>
                </div>
                <div class="inner">
                    <h3 class="stat-value" data-key="user_klien">0</h3>
                    <p>User Klien</p>
                </div>
                <div class="icon"><i class="bi bi-person-fill"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <x-adminlte-card theme="light">
                <!-- Table -->
                <table id="tableUserList" class="table table-bordered table-hover dataTable dtr-inline"></table>
            </x-adminlte-card>
        </div>
    </div>

    <!-- Modal Add User -->
    <form id="formAddUser" method="POST" action="{{ route('user.add') }}" >
        @csrf
        <x-adminlte-modal id="modalAddUser" title="Tambah User" theme="blue" size='lg' v-centered disable-x="false">

            <div class="row pl-3 pr-3 pt-3">
                <!-- akun -->
                <div class="col-md-6">
                    <x-adminlte-input name="name" placeholder="Nama Lengkap" label-class="text-lightblue">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
            
                    <x-adminlte-input name="email" type="email" placeholder="Alamat Email" label-class="text-lightblue">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
            
                    <x-adminlte-input name="password" type="password" placeholder="Password" label-class="text-lightblue">
                        <x-slot name="prependSlot">
                            <div class="input-group-text toggle-password">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
            
                    <x-adminlte-input name="password_confirmation" type="password" placeholder="Konfirmasi Password" label-class="text-lightblue">
                        <x-slot name="prependSlot">
                            <div class="input-group-text toggle-password">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-select name="aktifasi">
                                <x-slot name="label">
                                    Status <span class="text-danger">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="bi bi-person-fill-check"></i>
                                    </div>
                                </x-slot>
                                <x-adminlte-options :options="['Aktif'=> 'Aktif', 'Nonaktif' => 'Nonaktif']"/>
                            </x-adminlte-select>
                        </div>
                        <div class="col-md-6">
                            <x-adminlte-select name="role">
                                <x-slot name="label">
                                    Hak Akses <span class="text-danger">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="bi bi-person-fill-gear"></i>
                                    </div>
                                </x-slot>
                                <x-adminlte-options :options="['Klien' => 'Klien', 'Admin'=> 'Admin']"/>
                            </x-adminlte-select>
                        </div>

                    </div>
                </div>

                <!-- profil -->
                <div class="col-md-6">
                    <x-adminlte-input name="company" placeholder="Perusahaan" label-class="text-lightblue" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-building-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="wa" placeholder="Whatsapp" label-class="text-lightblue">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="telegram" placeholder="Telegram" label-class="text-lightblue">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-telegram"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="address" placeholder="Alamat" label-class="text-lightblue" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-house-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <x-slot name="footerSlot">
                <div class="d-flex justify-content-end w-100">
                    <button type="button" id="btn-loading" class="btn btn-primary" disabled="" style="display: none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                    <button type="submit" class="btn btn-primary m-1">
                        Simpan
                    </button>
                    <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal" class="m-1"/>
                </div>
            </x-slot>

        </x-adminlte-modal>
    </form>

    

    <!-- Modal Edit User -->
    <form id="formEditUser" method="POST" data-cek="true">
        @csrf
        @method('PUT')
        <x-adminlte-modal id="modalEditUser" title="Edit User" theme="blue" size='lg' v-centered disable-x="false">

            <div class="row pl-3 pr-3 pt-3">
                <!-- akun -->
                <div class="col-md-6">
                    <x-adminlte-input name="name" placeholder="Nama Lengkap" label-class="text-lightblue" id="edit_name">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="email" type="email" placeholder="Alamat Email" label-class="text-lightblue" id="edit_email">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="password" type="password" placeholder="Password (biarkan kosong jika tidak diganti)" label-class="text-lightblue" id="edit_password">
                        <x-slot name="prependSlot">
                            <div class="input-group-text toggle-password">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="password_confirmation" type="password" placeholder="Konfirmasi Password" label-class="text-lightblue" id="edit_password_confirmation">
                        <x-slot name="prependSlot">
                            <div class="input-group-text toggle-password">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-select name="aktifasi" id="edit_aktifasi">
                                <x-slot name="label">
                                    Status <span class="text-danger">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="bi bi-person-fill-check"></i>
                                    </div>
                                </x-slot>
                                <x-adminlte-options :options="['Aktif'=> 'Aktif', 'Nonaktif' => 'Nonaktif']"/>
                            </x-adminlte-select>
                        </div>
                        <div class="col-md-6">
                            <x-adminlte-select name="role" id="edit_role">
                                <x-slot name="label">
                                    Hak Akses <span class="text-danger">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="bi bi-person-fill-gear"></i>
                                    </div>
                                </x-slot>
                                <x-adminlte-options :options="['Admin'=> 'Admin', 'Klien'=> 'Klien']"/>
                            </x-adminlte-select>
                        </div>

                    </div>
                </div>

                <!-- profil -->
                <div class="col-md-6">
                    <x-adminlte-input name="company" placeholder="Perusahaan" label-class="text-lightblue" id="edit_company" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-building-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="wa" placeholder="Whatsapp" label-class="text-lightblue" id="edit_wa">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="telegram" placeholder="Telegram" label-class="text-lightblue" id="edit_telegram">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-telegram"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="address" placeholder="Alamat" label-class="text-lightblue" id="edit_address" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-house-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <x-slot name="footerSlot">
                <div class="d-flex justify-content-end w-100">
                    <button type="button" id="btn-loading" class="btn btn-primary" disabled="" style="display: none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                    <button type="submit" class="btn btn-primary m-1">
                        Update
                    </button>
                    <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal" class="m-1"/>
                </div>
            </x-slot>

        </x-adminlte-modal>
    </form>
@stop

@section('page_js')
    <script src="{{ asset('build/assets/user-list.min.js') }}"></script>
@stop