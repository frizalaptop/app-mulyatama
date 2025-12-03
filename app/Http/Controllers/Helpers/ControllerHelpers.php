<?php

namespace App\Http\Controllers\Helpers;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ControllerHelpers
{
    /**
     * Logika pengambilan data untuk method tabel pada setiap controller
     * @param \Illuminate\Http\Request $request http request instance
     * @param EloquentBuilder|QueryBuilder|\Illuminate\Database\Eloquent\Model $query query builder
     * @param array $searchableColumns kolom yang dapat dicari
     * @param callable $customColumnFilter (opsional) kolom yang dapat difilter
     * @return array{data: mixed, draw: int, recordsFiltered: mixed, recordsTotal: mixed}
     */
    public function tabelHelper(
        Request $request,
        EloquentBuilder|QueryBuilder|Model $query,
        array $searchableColumns = [],
        ?callable $customColumnFilter = null
    ) {
        // Ambil parameter DataTables
        $draw   = $request->get('draw');
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);
        
        // Atur pencarian & filter
        $search = $request->input('search.value');
        $customFilter = $request->input('columns', []);
        
        // Atur urutan
        $order  = $request->input('order')[0] ?? ['column' => 1, 'dir' => 'asc'];
        $orderColumn = $request->input("columns.{$order['column']}.data") ?? 'id';
        $orderDir = $order['dir'];

        // Pencarian global
        if (!empty($search) && !empty($searchableColumns)) {
            $query->where(function ($q) use ($searchableColumns, $search) {
                foreach ($searchableColumns as $col) {
                    $q->orWhere($col, 'like', "%{$search}%");
                }
            });
        }

        // Custom filter per kolom
        foreach ($customFilter as $col) {
            $colName = $col['data'] ?? null;
            $colSearch = $col['search']['value'] ?? null;

            if ($colName && $colSearch !== null && $colSearch !== '') {
                // select data lanjutan jika yang difilter adalah kolom tabel relasi
                if ($customColumnFilter && $customColumnFilter($query, $colName, $colSearch) === true) {
                    continue;
                }

                $query->where($colName, 'like', "%{$colSearch}%");
            }
        }

        // Hitung total sebelum filter
        $recordsTotal = $query->count();

        // Hitung total setelah filter
        $recordsFiltered = (clone $query)->count();

        // Ambil data sesuai paginasi
        $data = $query
            ->orderBy($orderColumn, $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();

        // Return struktur standar DataTables
        return [
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];
    }
}   