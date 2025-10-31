@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('/vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/datatables-plugins/responsive/css/responsive.bootstrap4.min.css') }}">   
@endpush

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

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <x-adminlte-card theme="light">
                <table id="tableUserList" class="table table-bordered table-hover dataTable dtr-inline"></table>
            </x-adminlte-card>
        </div>
    </div>

    <!-- Modal Add User -->
    <form id="formAddUser" method="POST" action="{{ route('admin.user.list.simpan') }}" >
        @csrf
        <x-adminlte-modal id="modalAddUser" title="Tambah User" theme="blue" size='lg' v-centered disable-x="false">

            <div class="row pl-3 pr-3 pt-3">
                <!-- akun -->
                <div class="col-md-6">
                    <x-adminlte-input name="name" placeholder="Nama Lengkap">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
            
                    <x-adminlte-input name="email" type="email" placeholder="Alamat Email">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
            
                    <x-adminlte-input name="password" type="password" placeholder="Password">
                        <x-slot name="prependSlot">
                            <div class="input-group-text toggle-password">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
            
                    <x-adminlte-input name="password_confirmation" type="password" placeholder="Konfirmasi Password">
                        <x-slot name="prependSlot">
                            <div class="input-group-text toggle-password">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-select name="aktivasi">
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
                    <x-adminlte-input name="perusahaan" placeholder="Perusahaan" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-building-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="whatsapp" placeholder="Whatsapp">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="telegram" placeholder="Telegram">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-telegram"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="alamat" placeholder="Alamat" class="upper">
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
                    <x-adminlte-input name="name" placeholder="Nama Lengkap" id="edit_name">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="email" type="email" placeholder="Alamat Email" id="edit_email">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="password" type="password" placeholder="Password (biarkan kosong jika tidak diganti)" id="edit_password">
                        <x-slot name="prependSlot">
                            <div class="input-group-text toggle-password">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="password_confirmation" type="password" placeholder="Konfirmasi Password" id="edit_password_confirmation">
                        <x-slot name="prependSlot">
                            <div class="input-group-text toggle-password">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-select name="aktivasi" id="edit_aktivasi">
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
                    <x-adminlte-input name="perusahaan" placeholder="Perusahaan" id="edit_perusahaan" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-building-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="whatsapp" placeholder="Whatsapp" id="edit_whatsapp">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="telegram" placeholder="Telegram" id="edit_telegram">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-telegram"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="alamat" placeholder="Alamat" id="edit_alamat" class="upper">
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

@push('js')
    <script>
        // Definisi route endpoint yang dibutuhkan
        window.routes = {
            dataTable: "{{ route('admin.user.list.tabel') }}",
            getUser: "{{ route('admin.user.list.getId', ['id' => ':id']) }}",
            updateUser: "{{ route('admin.user.list.update', ['id' => ':id']) }}",
            statisticUser: "{{ route('admin.statistik.user.list') }}",
        };
    </script>
    <script src="{{ asset('/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/vendor/jquery-validation/localization/messages_id.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables-plugins/responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables-plugins/buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables-plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('page/custom_format.min.js') }}"></script>
    <script src="{{ asset('page/custom_form.min.js') }}"></script>
    <script src="{{ asset('page/custom_table.min.js') }}"></script>
    <script src="{{ asset('page/user-list.min.js') }}"></script>
@endpush

