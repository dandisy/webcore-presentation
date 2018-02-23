<?php

namespace Webcore\Presentation\DataTables;

use Webcore\Presentation\Models\Presentation;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PresentationDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable
            ->addColumn('action', 'presentation::presentations.datatables_actions')
            ->editColumn('page_id', '{{ $page[\'name\'] }}')
            ->editColumn('component_id', '{{ $component[\'name\'] }}');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Presentation $model)
    {
        return $model
            ->with('page')
            ->with('component')
            ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px'])
            ->parameters([
                'dom'     => 'Bfrtip',
                'order'   => [[0, 'desc']],
                'buttons' => [
                    'create',
                    'export',
                    'print',
                    //'reset',
                    //'reload',
                ],
                // 'initComplete' => "function() {
                //     this.api().columns().every(function() {
                //         var column = this;
                //         var input = document.createElement(\"input\");
                //         $(input).appendTo($(column.footer()).empty())
                //         .on('change', function () {
                //             column.search($(this).val(), false, false, true).draw();
                //         });
                //     });
                // }",
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'page_id' => ['name' => 'page.name', 'data' => 'page.name'],
            //'media',
            'component_id' => ['name' => 'component.name', 'data' => 'component.name'],
            'position',
            //'order',
            'created_at',
            //'updated_at'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'presentationsdatatable_' . time();
    }
}