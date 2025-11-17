@props([
    'id' => 'datatable-' . uniqid(),
    'title' => 'Table',
    'url' => '',
    'pageLength' => 8,
    'columns' => [],
])

@php


$datatableColumns = collect($columns)
    ->map(function ($column) {
        if (is_array($column)) {
            $dataKey = $column['data'] ?? $column['name'] ?? $column['label'] ?? null;

            $definition = [
                'data' => $dataKey,
                'name' => $column['name'] ?? $dataKey,
            ];

            foreach (['orderable', 'searchable', 'visible', 'width', 'className'] as $option) {
                if (array_key_exists($option, $column)) {
                    $definition[$option] = $column[$option];
                }
            }

            return $definition;
        }

        return [
            'data' => $column,
            'name' => $column,
        ];
    })
    ->values();

// Note: action column removed — buttons/features intentionally disabled
@endphp

<div>
    <div class="py-3">
        <div class="d-flex">
            <div class="text-secondary">
                Show
                <div class="mx-2 d-inline-block">
                    <input
                        type="text"
                        id="pageLength-{{ $id }}"
                        class="pageLength-input form-control form-control-sm"
                        value="{{ $pageLength }}"
                        size="3"
                        aria-label="Rows per page"
                        data-table-id="{{ $id }}">
                </div>
                entries
            </div>

            <div class="ms-auto ps-3 text-secondary" style="padding-right: 10px;">
                Search:
                <div class="ms-2 d-inline-block">
                    <input
                        type="text"
                        id="customSearch-{{ $id }}"
                        class="customSearch-input form-control form-control-sm"
                        aria-label="Search Table"
                        data-table-id="{{ $id }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <div id="loading-{{ $id }}" class="loading-overlay" style="display: none;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <table
                id="{{ $id }}"
                class="table table-selectable card-table table-vcenter text-nowrap datatable"
                data-url="{{ $url }}"
                data-page-length="{{ $pageLength }}"
                data-columns='@json($datatableColumns)'>
                <thead>
                    <tr>
                        @foreach ($columns as $column)
                            <th class="{{ $column['class'] ?? '' }}">{{ $column['label'] ?? $column }}</th>
                        @endforeach

                        {{-- action column removed --}}
                    </tr>
                </thead>

                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>

    <div class="px-3 py-2">
        <div class="row g-2 justify-content-center justify-content-sm-between">
            <div class="col-auto d-flex align-items-center">
                <p
                    id="tableInfo-{{ $id }}"
                    class="tableInfo m-0 text-secondary"
                    data-table-id="{{ $id }}">
                    Showing <strong>0 to 0</strong> of <strong>0 entries</strong>
                </p>
            </div>

            <div class="col-auto">
                <ul
                    id="tablePagination-{{ $id }}"
                    class="tablePagination pagination m-0 ms-auto"
                    data-table-id="{{ $id }}">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                <path d="M15 6l-6 6l6 6"></path>
                            </svg>
                        </a>
                    </li>

                    <li class="page-item active"><a class="page-link" href="#">1</a></li>

                    <li class="page-item">
                        <a class="page-link" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                <path d="M9 6l6 6l-6 6"></path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <style>
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 0.25rem;
        }

        .loading-overlay .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
            color: #0d6efd;
        }

        .table-responsive {
            position: relative;
            min-height: 200px;
        }

        .datatable {
            border: 1px solid #dee2e6;
        }

        .datatable thead th {
            border: 1px solid #dee2e6;
        }

        .datatable tbody tr td {
            text-align: left;
            border: 1px solid #dee2e6;
        }

        

        .datatable tbody tr.no-records-message {
            height: 200px;
        }

        .datatable tbody tr.no-records-message td {
            vertical-align: middle;
            color: #6c757d;
            font-size: 1rem;
            padding: 2rem 1rem;
        }
    </style>

    <script>
        $(document).ready(function () {
            const tables = {};

            $('.datatable').each(function () {
                const $table = $(this);
                const tableId = $table.attr('id');
                const apiUrl = $table.data('url');
                const pageLengthAttr = parseInt($table.attr('data-page-length'), 10);
                const pageLength = !isNaN(pageLengthAttr) && pageLengthAttr > 0 ? pageLengthAttr : 10;

                // Read column definitions from data attribute. jQuery.data() may return a string
                // depending on how the attribute was set, so try parsing the raw attribute as JSON
                // as a fallback to ensure we get an object/array.
                let columnData = $table.data('columns');
                if (!columnData) {
                    const raw = $table.attr('data-columns');
                    if (raw) {
                        try {
                            columnData = JSON.parse(raw);
                        } catch (e) {
                            columnData = [];
                        }
                    }
                }

                const columnDefinitions = (columnData || []).map(function (column) {
                    if (typeof column === 'string') {
                        // Check if this is an action column
                        if (column === 'action') {
                            return {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false,
                                render: function (data) {
                                    return data;
                                }
                            };
                        }
                        return { data: column };
                    }

                    if (column && typeof column === 'object') {
                        const resolved = { ...column };
                        if (!resolved.data && resolved.name) resolved.data = resolved.name;
                        
                        // Check if this is an action column
                        if (resolved.data === 'action' || resolved.name === 'action') {
                            resolved.orderable = resolved.orderable ?? false;
                            resolved.searchable = resolved.searchable ?? false;
                            resolved.render = function (data) {
                                return data;
                            };
                        }
                        
                        return resolved;
                    }

                    return { data: column };
                });

                if (!apiUrl) return;

                tables[tableId] = $table.DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: apiUrl,
                    columns: columnDefinitions,
                    pageLength: pageLength,
                    lengthChange: false,
                    info: false,
                    searching: true,
                    ordering: true,
                    responsive: true,
                    paging: true,
                    dom: 't',
                    language: {
                        emptyTable: 'No matching records found',
                        zeroRecords: 'No matching records found',
                    },
                    preDrawCallback: function () {
                        $(`#loading-${tableId}`).show();
                    },
                    drawCallback: function () {
                        $(`#loading-${tableId}`).hide();
                        updateTableInfo(tableId);
                        updatePagination(tableId);

                        // Center align empty message
                        const $tbody = $(`#${tableId} tbody`);
                        if ($tbody.find('tr').length === 1 && $tbody.find('tr td').attr('colspan')) {
                            $tbody.find('tr').addClass('no-records-message');
                            $tbody.find('tr td').css({ textAlign: 'center', verticalAlign: 'middle' });
                        }
                    },
                });
            });

            // Custom search functionality
            $('.customSearch-input').on('keyup', function () {
                const tableId = $(this).data('table-id');
                if (tables[tableId]) {
                    $(`#loading-${tableId}`).show();
                    tables[tableId].search(this.value).draw();
                }
            });

            // Page length functionality
            $('.pageLength-input').on('keyup change', function () {
                const tableId = $(this).data('table-id');
                const length = parseInt(this.value);
                if (!isNaN(length) && length > 0 && tables[tableId]) {
                    $(`#loading-${tableId}`).show();
                    tables[tableId].page.len(length).draw();
                }
            });

            // Update table info display
            function updateTableInfo(tableId) {
                const table = tables[tableId];
                if (!table) return;

                const pageInfo = table.page.info();
                const start = pageInfo.start + 1;
                const end = pageInfo.end;
                const total = pageInfo.recordsTotal;

                $(`#tableInfo-${tableId}`).html(`Showing <strong>${start} to ${end}</strong> of <strong>${total} entries</strong>`);
            }

            // Update pagination controls
            function updatePagination(tableId) {
                const table = tables[tableId];
                if (!table) return;

                const pageInfo = table.page.info();
                const totalPages = pageInfo.pages;
                const currentPage = pageInfo.page;

                const $pagination = $(`#tablePagination-${tableId}`);
                $pagination.empty();

                // Previous button
                const $prevButton = $('<li>').addClass('page-item');
                if (currentPage === 0) {
                    $prevButton.addClass('disabled');
                    $prevButton.append(
                        $('<a>')
                            .addClass('page-link')
                            .attr('href', '#')
                            .attr('tabindex', '-1')
                            .attr('aria-disabled', 'true')
                            .html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1"><path d="M15 6l-6 6l6 6"></path></svg>')
                    );
                } else {
                    $prevButton.append(
                        $('<a>')
                            .addClass('page-link')
                            .attr('href', '#')
                            .html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1"><path d="M15 6l-6 6l6 6"></path></svg>')
                            .on('click', function (e) {
                                e.preventDefault();
                                table.page('previous').draw('page');
                            })
                    );
                }
                $pagination.append($prevButton);

                // Page numbers
                for (let i = 0; i < totalPages; i++) {
                    const $pageItem = $('<li>').addClass('page-item');
                    if (i === currentPage) $pageItem.addClass('active');

                    $pageItem.append(
                        $('<a>')
                            .addClass('page-link')
                            .attr('href', '#')
                            .text(i + 1)
                            .on('click', function (e) {
                                e.preventDefault();
                                table.page(i).draw('page');
                            })
                    );

                    $pagination.append($pageItem);
                }

                // Next button
                const $nextButton = $('<li>').addClass('page-item');
                if (currentPage === totalPages - 1) {
                    $nextButton.addClass('disabled');
                    $nextButton.append(
                        $('<a>')
                            .addClass('page-link')
                            .attr('href', '#')
                            .attr('tabindex', '-1')
                            .attr('aria-disabled', 'true')
                            .html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1"><path d="M9 6l6 6l-6 6"></path></svg>')
                    );
                } else {
                    $nextButton.append(
                        $('<a>')
                            .addClass('page-link')
                            .attr('href', '#')
                            .html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1"><path d="M9 6l6 6l-6 6"></path></svg>')
                            .on('click', function (e) {
                                e.preventDefault();
                                table.page('next').draw('page');
                            })
                    );
                }
                $pagination.append($nextButton);
            }
        });
    </script>
@endPushOnce
