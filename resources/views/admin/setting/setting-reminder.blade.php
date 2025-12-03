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
          <table id="tableReminderSetting" class="table table-bordered table-hover dataTable dtr-inline"></table>
        </x-adminlte-card>
      </div>
    </div>

    <!-- Modal Edit Reminder -->
    <form id="formEditReminder" method="POST" data-cek="true">
        @csrf
        @method('PUT')
        <x-adminlte-modal id="modalEditReminder" title="Edit Reminder" theme="blue" size='lg' v-centered disable-x="false">

            <div class="row pl-3 pr-3 pt-3">
                <div class="col-md-12">
                    <x-adminlte-input name="payload" placeholder="Tentukan Hari Reminder" id="edit_payload">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="bi bi-bell"></i>
                            </div>
                        </x-slot>

                        <x-slot name="bottomSlot">
                            <span class="text-muted">
                                Pisahkan beberapa hari dengan tanda koma (contoh: 30, 14, 7, 1).
                            </span>
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
            dataTable: "{{ route('admin.setting.reminder.tabel') }}",
            getReminder: "{{ route('admin.setting.reminder.getId', ['id' => ':id']) }}",
            opsiFilter: "{{ route('admin.setting.reminder.filter') }}",
            updateReminder: "{{ route('admin.setting.reminder.update', ['id' => ':id']) }}",
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
    <script src="{{ asset('page/admin/setting-reminder.min.js') }}"></script>
@endpush