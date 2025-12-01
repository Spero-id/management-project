@extends('layouts.app')

@push('styles')
    <style>
        /* Custom styles for the new table design */
        .card-table {
            margin-bottom: 0;
        }

        .table-selectable .table-selectable-check:checked+.table-selectable-check-indicator {
            background-color: var(--tblr-primary);
            border-color: var(--tblr-primary);
        }

        .icon-sm {
            width: 1rem;
            height: 1rem;
        }

        .badge {
            font-size: 0.65em;
        }

        .dropdown-toggle::after {
            margin-left: 0.5em;
        }
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Overview</div>
            <h2 class="page-title">Users</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">

            <div class="btn-list">


                <a href="{{ route('user.export') }}" class="btn btn-success btn-5 d-none d-sm-inline-block">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-file-excel">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
                        <path d="M10 12l4 5" />
                        <path d="M10 17l4 -5" />
                    </svg>
                    Export
                </a>


                <a href="{{ route('user.create') }}" class="btn btn-primary btn-5 d-none d-sm-inline-block">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Create User
                </a>

                <a href="#" class="btn btn-primary btn-6 d-sm-none btn-icon" data-bs-toggle="modal"
                    data-bs-target="#modal-report" aria-label="Create new report">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                </a>
            </div>
            <!-- BEGIN MODAL -->
            <!-- Delete Confirmation Modal -->
            <div class="modal modal-blur fade" id="modal-delete-user" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-status bg-danger"></div>
                        <div class="modal-body text-center py-4">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/alert-triangle -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 9v2m0 4v.01" />
                                <path
                                    d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                            </svg>
                            <h3>Apakah Anda yakin?</h3>
                            <div class="text-secondary">
                                Anda akan menghapus user <strong id="deleteUserName"></strong>.
                                Tindakan ini tidak dapat dibatalkan.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn w-100" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                    </div>
                                    <div class="col">
                                        <form id="deleteUserForm" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                Ya, hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MODAL -->
        </div>
    </div>
@endsection

@section('content')
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert  alert-important alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <!-- Download SVG icon from http://tabler.io/icons/icon/check -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon alert-icon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div>
                    {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert  alert-important alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <!-- Download SVG icon from http://tabler.io/icons/icon/alert-triangle -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon alert-icon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 9v2m0 4v.01" />
                        <path
                            d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                    </svg>
                </div>
                <div>
                    {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users</h3>
                </div>
                <div class="card-body">
                    <x-datatable id="users-table" title="Users" url="{{ route('user.datatable') }}" page-length="10"
                        :columns="[
                            [
                                'label' => 'No',
                                'data' => 'DT_RowIndex',
                                'name' => 'id',
                                'orderable' => true,
                                'searchable' => false,
                                'class' => 'w-1',
                            ],
                            ['label' => 'UNIQ ID', 'data' => 'unique_id', 'name' => 'unique_id'],
                            ['label' => 'Nama', 'data' => 'name', 'name' => 'name'],
                            ['label' => 'JOIN DATE', 'data' => 'join_date', 'name' => 'join_date'],
                            ['label' => 'KODE DIVISI', 'data' => 'division_code', 'name' => 'division_code'],
                            ['label' => 'DIVISI', 'data' => 'division_name', 'name' => 'division_name'],
                            [
                                'label' => '',
                                'data' => 'actions',
                                'name' => 'actions',
                                'orderable' => false,
                                'searchable' => false,
                            ],
                        ]">
                    </x-datatable>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle delete user modal - Use event delegation for dynamically loaded content
            $(document).on('click', '.delete-user-btn', function() {
                var userId = $(this).data('user-id');
                var userName = $(this).data('user-name');

                // Update modal content
                $('#deleteUserName').text(userName);

                // Update form action URL - build the URL properly
                var baseUrl = '{{ url('/user') }}';
                $('#deleteUserForm').attr('action', baseUrl + '/' + userId);
            });
        });
    </script>
@endpush
