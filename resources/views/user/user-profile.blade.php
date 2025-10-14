@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-4 col-lg-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <form action="https://portal.mulyatamaabadi.co.id/profil/update-foto" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="id_profil" name="id_profil" value="7">
    
                        <div class="unggah" data-max-width="215">
                            <div class="loadingSpinner">⏳ <span class="ml-2">Memproses gambar...</span></div>
    
                            <div class="text-center">
                                <img id="preview_foto_profil" class="img-fluid p-2 border border-primary rounded" src="https://portal.mulyatamaabadi.co.id/upload/profil/crb-profil.png" style="max-width: 215px;">
                            </div>
    
                            <h3 class="profile-username text-center">{{ auth()->user()->name }}</h3>
                            <p class="text-center mt-n1">{{ auth()->user()->email }}</p>
    
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item"><b>Tanggal Daftar</b> <a class="float-right">{{ auth()->user()->created_at->format('d M Y → H:i:s') }}</a></li>
                                <li class="list-group-item"><b>Terakhir Login</b> <a class="float-right">{{ auth()->user()->last_login_at->format('d M Y → H:i:s')}}</a></li>
                                <li class="list-group-item text-center"><b>Upload foto profil</b> <span class="ml-2" data-toggle="tooltip" data-placement="top" title="" style="font-size: 15px;" data-original-title="Dimensi foto ideal 215 x 215 px. Format foto: .jpg .jpeg .png"><i class="bi bi-question-circle"></i></span></li>
                            </ul>
    
                            <input type="file" class="form-control-file mt-2" id="foto_profil" name="foto_profil" accept=".jpg,.jpeg,.png">
                            <div id="info_foto_profil" class="info-kecil mt-2"><small>maksimal ukuran foto 1 MB.</small></div>
    
                            <div class="mt-2"><button type="submit" class="btn btn-sm btn-secondary btn-block" fdprocessedid="slgj4n">Update foto profil</button></div>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    
        <!-- Card Form Edit -->
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#sesi" data-toggle="tab">Sesi</a></li>
                        <li class="nav-item"><a class="nav-link" href="#akun" data-toggle="tab">Akun</a></li>
                        <li class="nav-item"><a class="nav-link" href="#info" data-toggle="tab">Info</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">

                    <!-- Tab Sesi -->
                        <div class="tab-pane fade active show" id="sesi">
                            
                        </div>

                    <!-- Tab Akun -->
                        <div class="tab-pane fade show" id="akun">
                            <form method="POST" action="{{ route('profil.update.akun', ['userId'=> auth()->user()->id]) }}" id="akun-form" data-cek="true">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <x-adminlte-input name="name" value="{{ old('name', auth()->user()->name) }}"
                                                label-class="text-lightblue" placeholder="Masukkan nama baru">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="bi bi-person-circle"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                            </div>

                                            <div class="mb-3">
                                                <x-adminlte-input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" label-class="text-lightblue" placeholder="Masukkan email baru">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="bi bi-envelope-fill"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <x-adminlte-input name="password" type="password" label-class="text-lightblue" placeholder="Password" id="edit_password">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text toggle-password">
                                                            <i class="bi bi-eye-fill"></i>
                                                        </div>
                                                    </x-slot>
                                                    <x-slot name="appendSlot">
                                                        <span class="input-group-text" data-toggle="tooltip" title="Kosongkan password jika tidak ada perubahan.">
                                                            <i class="bi bi-question-circle"></i>
                                                        </span>
                                                    </x-slot>
                                                </x-adminlte-input>
                                            </div>

                                            <div class="mb-3">
                                                <x-adminlte-input name="password_confirmation" type="password" label-class="text-lightblue" placeholder="Ulangi Password" id="edit_password_confirmation">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text toggle-password">
                                                            <i class="bi bi-eye-fill"></i>
                                                        </div>
                                                    </x-slot>
                                                    <x-slot name="appendSlot">
                                                        <span class="input-group-text" data-toggle="tooltip" title="Kosongkan password jika tidak ada perubahan.">
                                                            <i class="bi bi-question-circle"></i>
                                                        </span>
                                                    </x-slot>
                                                </x-adminlte-input>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" id="akun-submit" class="btn btn-dark mr-1 float-right">Simpan</button>
                                            <button type="button" id="akun-loading" class="btn btn-dark float-right" disabled="" style="display: none;">
                                                <span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                        </div>

                                    </div>
                            </form>
                        </div>
                        
                    <!-- Tab info -->
                        <div class="tab-pane fade show" id="info">
                            <form method="POST" action="{{ route('profil.update.info', ['userId'=> auth()->user()->id]) }}" id="info-form" data-cek="true">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <x-adminlte-input name="perusahaan" placeholder="Perusahaan" label-class="text-lightblue" id="edit_perusahaan" class="upper" value="{{ old('perusahaan', auth()->user()->profile->perusahaan) }}">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="bi bi-building-fill"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                            </div>

                                            <div class="mb-3">
                                                <x-adminlte-input name="whatsapp" placeholder="Whatsapp" label-class="text-lightblue" id="edit_whatsapp" value="{{ old('perusahaan', auth()->user()->profile->whatsapp) }}">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="bi bi-whatsapp"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <x-adminlte-input name="telegram" placeholder="Telegram" label-class="text-lightblue" id="edit_telegram" value="{{ old('perusahaan', auth()->user()->profile->telegram) }}">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="bi bi-telegram"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                            </div>

                                            <div class="mb-3">
                                                <x-adminlte-input name="alamat" placeholder="Alamat" label-class="text-lightblue" id="edit_alamat" class="upper" value="{{ old('perusahaan', auth()->user()->profile->alamat) }}">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="bi bi-house-fill"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" id="info-submit" class="btn btn-dark mr-1 float-right">Simpan</button>
                                            <button type="button" id="info-loading" class="btn btn-dark float-right" disabled="" style="display: none;">
                                                <span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                        </div>

                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@stop

@push('js')
    <script src="{{ asset('/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/vendor/jquery-validation/localization/messages_id.min.js') }}"></script>
    <script src="{{ asset('page/custom_format.min.js') }}"></script>
    <script src="{{ asset('page/custom_form.min.js') }}"></script>
    <script src="{{ asset('page/profil.min.js') }}"></script>
@endpush