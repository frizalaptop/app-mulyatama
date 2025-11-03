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

@stop

@push('js')
    <script>
        // Definisi route endpoint yang dibutuhkan
        window.routes = {
            dataTable: "{{ route('klien.billboard.list.tabel') }}",
        };
    </script>
    <script src="{{ asset('/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables-plugins/responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables-plugins/buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables-plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('page/custom_format.min.js') }}"></script>
    <script src="{{ asset('page/custom_table.min.js') }}"></script>
    <script src="{{ asset('page/klien/billboard-list.min.js') }}"></script>
@endpush