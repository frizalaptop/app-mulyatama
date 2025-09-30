@extends('layouts.app')

@section('content_header')
    <h1>User List</h1>
@stop

@section('content')
    @php
        $heads = ['ID', 'Nama', 'Email', 'Status', 'Aksi'];
        $config = [
            'ajax' => route('user.datatable'),
            'paging' => true,
            'searching' => true,
            'ordering' => true,
            'info' => true,
            'autoWidth' => false,
            'responsive' => true,
            'processing' => true,
            'serverSide' => false,
            'order' => [[1, 'asc']],
            'columns' => [
                ['data' => 'id'],
                ['data' => 'name'],
                ['data' => 'email'],
                ['data' => 'status'],
                ['data' => 'aksi', 'orderable' => false, 'searchable' => false],
            ],
            'dom' => <<<DOM
                <"row mb-2"<"col-sm-2"B><"col-sm-2"l><"col-sm-8"f>>
                <"row"<"col-sm-12"tr>>
                <"row mt-2"<"col-sm-5"i><"col-sm-7"p>>
            DOM,
            'buttons' => [
                [
                    'text' => 'Add',
                    'className' => 'btn btn-default btn-sm dt-button',
                    'attr' => [
                        'data-target' => '#modalAdd',
                        'data-toggle' => 'modal',
                    ],
                ],
                [
                    'text' => 'Excel',
                    'className' => 'btnExcel btn btn-default btn-sm dt-button',
                ],
            ],
        ];
    @endphp

    <!-- Modal Add User -->
    <form method="POST" action="{{ route('user.add') }}" >
        @csrf
        <x-adminlte-modal id="modalAdd" title="Tambah User" theme="blue" size='lg'>

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
                    <x-adminlte-button class="btn-flat" type="submit" label="Simpan" theme="success" icon="fas fa-lg fa-save"/>
                    <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal"/>
                </div>
            </x-slot>

        </x-adminlte-modal>
    </form>

    

    <!-- Modal Edit User -->
    

    <x-adminlte-card theme="light">

        <!-- Table -->
        <x-adminlte-datatable id="tableUserList" :heads="$heads" :config="$config">
        </x-adminlte-datatable>

    </x-adminlte-card>
@stop

@section('css')
@stop