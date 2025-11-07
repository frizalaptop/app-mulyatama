<?php

namespace App\Http\Controllers\Helpers;

class ControllerHelpers
{
    public function tabelHelper(
        $request,
        $query,
        array $orderableColumns,
        array $searchableColumns = [],
        ?callable $customColumnFilter = null
    ) {
        // Ambil parameter DataTables
        $draw   = $request->get('draw');
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->input('search.value');
        $order  = $request->input('order')[0] ?? ['column' => 1, 'dir' => 'asc'];
        $customFilter = $request->input('columns', []);

        // Atur urutan
        $orderColumn = $orderableColumns[$order['column']] ?? $orderableColumns[0];
        $orderDir = $order['dir'] ?? 'asc';

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