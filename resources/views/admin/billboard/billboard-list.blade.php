@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('/vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/datatables-plugins/responsive/css/responsive.bootstrap4.min.css') }}">   
@endpush



@section('content')

    <!-- Table -->
	<div class="row">
		<div class="col-12">
			<x-adminlte-card theme="light">
				<table id="tableBillboardList" class="table table-bordered table-hover dataTable dtr-inline"></table>
			</x-adminlte-card>
		</div>
	</div>

    <!-- Modal Gambar Billboard -->
    <div class="modal fade" id="modalGambarBillboard" tabindex="-1" role="dialog" aria-labelledby="gambarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <img id="previewBillboardImage" src="" alt="Gambar Billboard" class="img-fluid rounded shadow-sm" style="max-height: 85vh; width: auto; object-fit: contain;">
        </div>
    </div>

    <!-- Modal Add Billboard -->
    <form id="formAddBillboard" method="POST" action="{{ route('admin.billboard.list.simpan') }}" >
        @csrf
        <x-adminlte-modal id="modalAddBillboard" title="Tambah Data Billboard" theme="blue" size='lg' v-centered disable-x="false">
            
            <div class="row pl-3 pr-3 pt-3">
                <div class="col">
                    <!-- Judul -->
                    <x-adminlte-input name="judul" placeholder="Judul Billboard" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-megaphone-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <div class="row pl-3 pr-3">
                <div class="col-md-6">

                    <!-- Area -->
                    <x-adminlte-input name="area" placeholder="Area" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Lokasi -->
                    <x-adminlte-input name="lokasi" placeholder="Lokasi" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-map-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Jenis Billboard -->
                    <x-adminlte-input name="jenis" placeholder="Jenis" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-columns-gap"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    
                    <!-- Unit -->
                    <x-adminlte-input name="unit" type="number" min="1" max="99" placeholder="Jumlah Unit" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-grid-3x3-gap-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Aktif -->
                            <x-adminlte-select name="aktif">
                                <x-slot name="label">
                                    Status <span class="text-danger">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="bi bi-toggle-on"></i>
                                    </div>
                                </x-slot>
                                <x-adminlte-options :options="['Aktif'=> 'Aktif', 'Nonaktif' => 'Nonaktif']"/>
                            </x-adminlte-select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Lebar -->
                    <x-adminlte-input name="lebar" type="number" min="1" max="99" placeholder="Lebar (meter)">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-arrows"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Panjang -->
                    <x-adminlte-input name="panjang" type="number" min="1" max="99" placeholder="Panjang (meter)">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-arrows-vertical"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    

                    <!-- Koordinat (Latitude) -->
                    <x-adminlte-input name="latitude" placeholder="Latitude">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-compass-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Koordinat (Longitude) -->
                    <x-adminlte-input name="longitude" placeholder="Longitude">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-compass"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Keterangan -->
                    <x-adminlte-textarea name="keterangan" rows="1">
                        <x-slot name="label">
                            Keterangan <span class="text-danger">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-card-text"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-textarea>
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

    <!-- Modal Edit Billboard -->
    <form id="formEditBillboard" method="POST" data-cek="true">
        @csrf
        @method('PUT')
        <x-adminlte-modal id="modalEditBillboard" title="Edit Billboard" theme="blue" size='lg' v-centered disable-x="false">

            <div class="row pl-3 pr-3 pt-3">
                <div class="col">
                    <!-- Judul -->
                    <x-adminlte-input name="judul" id="edit_judul" placeholder="Judul Billboard" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-megaphone-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <div class="row pl-3 pr-3">
                <div class="col-md-6">

                    <!-- Area -->
                    <x-adminlte-input name="area" id="edit_area" placeholder="Area" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Lokasi -->
                    <x-adminlte-input name="lokasi" id="edit_lokasi" placeholder="Lokasi" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-map-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Jenis Billboard -->
                    <x-adminlte-input name="jenis" id="edit_jenis" placeholder="Jenis" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-columns-gap"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    
                    <!-- Unit -->
                    <x-adminlte-input name="unit" id="edit_unit" placeholder="Jumlah Unit" type="number" min="1" max="99" class="upper">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-grid-3x3-gap-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Aktif -->
                            <x-adminlte-select name="aktif" id="edit_aktif">
                                <x-slot name="label">
                                    Status <span class="text-danger">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="bi bi-toggle-on"></i>
                                    </div>
                                </x-slot>
                                <x-adminlte-options :options="['Aktif'=> 'Aktif', 'Nonaktif' => 'Nonaktif']"/>
                            </x-adminlte-select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Lebar -->
                    <x-adminlte-input name="lebar" id="edit_lebar" placeholder="Lebar (meter)" type="number" min="1" max="99">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-arrows"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Panjang -->
                    <x-adminlte-input name="panjang" id="edit_panjang" placeholder="Panjang (meter)" type="number" min="1" max="99">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-arrows-vertical"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    

                    <!-- Koordinat (Latitude) -->
                    <x-adminlte-input name="latitude" id="edit_latitude" placeholder="Latitude">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-compass-fill"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Koordinat (Longitude) -->
                    <x-adminlte-input name="longitude" id="edit_longitude" placeholder="Longitude">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-compass"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Keterangan -->
                    <x-adminlte-textarea name="keterangan" id="edit_keterangan" rows="1">
                        <x-slot name="label">
                            Keterangan tambahan (opsional)
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-card-text"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-textarea>
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

    <!-- Modal Update Gambar Billboard -->
    <form id="formUpdateGambarBillboard" method="POST" enctype="multipart/form-data">
        @csrf
        <x-adminlte-modal id="modalUpdateGambarBillboard" title="Update Gambar Billboard" theme="blue" size='lg' v-centered disable-x="false">
            <div class="row pl-3 pr-3 pt-3">
                <div class="col">
                    <!-- Update Gambar Billboard -->
                    <x-adminlte-input name="gambar" type="file" label="Format: JPG, JPEG, PNG | Maksimal: 2 MB" igroup-size="md">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-image-fill"></i>
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

    <!-- Toast php Response message -->
    @if (session('success') || $errors->any())
    <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
        <div id="liveToast" class="toast {{ session('success') ? 'bg-success' : 'bg-danger' }}" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
            <div class="toast-body">
                {{ session('success') ?? $errors->first() }}
            </div>
        </div>
    </div>
    @endif
    

@stop

@push('js')
    <script>
        // Definisi route endpoint yang dibutuhkan
        window.routes = {
            dataTable: "{{ route('admin.billboard.list.tabel') }}",
            getBillboard: "{{ route('admin.billboard.list.getId', ['id' => ':id']) }}",
            updateBillboard: "{{ route('admin.billboard.list.update', ['id' => ':id']) }}",
            updateGambar: "{{ route('admin.billboard.list.update.gambar', ['id' => ':id']) }}"
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
    <script src="{{ asset('page/admin/billboard-list.min.js') }}"></script>
@endpush