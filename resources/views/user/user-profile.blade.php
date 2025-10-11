@extends('layouts.app')



@section('content')
<div class="container-fluid">

    <div class="row justify-content-center">
        <!-- Card Info -->
        <!-- <div class="col-md-4">
            <x-adminlte-card theme="blue" theme-mode="outline">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><b>Nama:</b> {{ auth()->user()->name }}</li>
                    <li class="list-group-item"><b>Email:</b> {{ auth()->user()->email }}</li>
                    <li class="list-group-item"><b>Dibuat:</b> {{ auth()->user()->created_at->format('d/m/Y H:i') }}</li>
                </ul>
            </x-adminlte-card>
        </div> -->
        <div class="col-md-4 col-lg-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <form action="https://portal.mulyatamaabadi.co.id/profil/update-foto" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_test_name" value="fd01dc326e1b8b654e539433368adabd">                            <input type="hidden" id="id_user" name="id_user" value="7">
                        <input type="hidden" id="id_profil" name="id_profil" value="7">
    
                        <div class="unggah" data-max-width="215">
                            <div class="loadingSpinner">⏳ <span class="ml-2">Memproses gambar...</span></div>
    
                            <div class="text-center">
                                <img id="preview_foto_profil" class="img-fluid p-2 border border-primary rounded" src="https://portal.mulyatamaabadi.co.id/upload/profil/crb-profil.png" style="max-width: 215px;">
                            </div>
    
                            <h3 class="profile-username text-center">{{ auth()->user()->name }}</h3>
                            <p class="text-center mt-n1">{{ auth()->user()->email }}</p>
    
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item"><b>Tanggal Daftar</b> <a class="float-right">{{ auth()->user()->created_at }}</a></li>
                                <li class="list-group-item"><b>Terakhir Login</b> <a class="float-right">{{ auth()->user()->last_login_at }}</a></li>
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
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@stop

