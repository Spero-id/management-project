@extends('layouts.app')

@push('styles')
    <style>
        
    body.modal-open {
        padding-right: 0 !important;
        overflow-y: auto !important;
    }

    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Overview</div>
            <h2 class="page-title">Sistem Manajemen E-Commerce Toko Baju Online</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('prospect.edit', 1) }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                    Edit Project
                </a>
                <a href="{{ route('prospect.index') }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                    </svg>
                    Back to Prospect
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Project Information</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Project Name -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Project Name</label>
                                <div class="fw-bold">Sistem Manajemen E-Commerce Toko Baju Online</div>
                            </div>
                        </div>

                        <!-- Client Name -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Client Name</label>
                                <div class="fw-bold">PT. Fashion Nusantara Indonesia</div>
                            </div>
                        </div>

                        <!-- Project Manager -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Project Manager</label>
                                <div class="fw-bold">Andi Pratama</div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <div>
                                    <span class="badge bg-primary text-white">Active</span>
                                </div>
                            </div>
                        </div>

                        <!-- Priority -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Priority</label>
                                <div>
                                    <span class="badge bg-warning text-white">High</span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Progress</label>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-fill me-2" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" style="width: 85%"></div>
                                    </div>
                                    <span class="fw-bold">85%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Start Date</label>
                                <div class="fw-bold">
                                    Sep 15, 2025
                                </div>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">End Date</label>
                                <div class="fw-bold">
                                    Dec 30, 2025
                                </div>
                            </div>
                        </div>

                        <!-- Budget -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Budget</label>
                                <div class="fw-bold text-success">
                                    Rp 750,000,000
                                </div>
                            </div>
                        </div>

                        <!-- Amount Spent -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Amount Spent</label>
                                <div class="fw-bold text-danger">
                                    Rp 637,500,000
                                </div>
                            </div>
                        </div>

                        <!-- Remaining Budget -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Remaining Budget</label>
                                <div class="fw-bold text-success">
                                    Rp 112,500,000
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Description</label>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        Pengembangan sistem e-commerce lengkap untuk toko baju online yang mencakup katalog
                                        produk, sistem pembayaran, manajemen inventory, dan dashboard admin.
                                        <br><br>
                                        Fitur utama meliputi:
                                        <br>• Katalog produk dengan filter kategori, ukuran, dan warna
                                        <br>• Sistem keranjang belanja dan wishlist
                                        <br>• Integrasi payment gateway (Midtrans, OVO, GoPay)
                                        <br>• Dashboard admin untuk manajemen produk dan pesanan
                                        <br>• Sistem notifikasi email dan SMS
                                        <br>• Responsive design untuk mobile dan desktop
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status History Section -->
    <div class="row row-cards mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        Status History
                    </h3>
                     <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#followUpModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="me-1">
                        <path d="M12 5v14m-7-7h14" />
                    </svg>
                    Follow Up
                </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Previous Status</th>
                                    <th>New Status</th>
                                    <th>Changed By</th>
                                    <th>Progress</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="text-muted">Sep 28, 2025</div>
                                        <div class="text-secondary">4:15 PM</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-white">On-Hold</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary text-white">Active</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2"
                                                style="background-image: url(https://ui-avatars.com/api/?name=Andi+Pratama&background=206bc4&color=fff)">
                                            </div>
                                            <div>
                                                <div class="fw-bold">Andi Pratama</div>
                                                <div class="text-muted small">Project Manager</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-fill me-2" style="height: 6px; width: 60px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: 85%"></div>
                                            </div>
                                            <span class="text-muted small">85%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-wrap">
                                            Masalah sumber daya telah diselesaikan. Tim kembali beroperasi dengan kapasitas
                                            penuh untuk finalisasi proyek.
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="text-muted">Sep 25, 2025</div>
                                        <div class="text-secondary">1:20 PM</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary text-white">Active</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-white">On-Hold</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2"
                                                style="background-image: url(https://ui-avatars.com/api/?name=Sari+Indrawati&background=dc3545&color=fff)">
                                            </div>
                                            <div>
                                                <div class="fw-bold">Sari Indrawati</div>
                                                <div class="text-muted small">Technical Lead</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-fill me-2" style="height: 6px; width: 60px;">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    style="width: 75%"></div>
                                            </div>
                                            <span class="text-muted small">75%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-wrap">
                                            Proyek dihentikan sementara menunggu persetujuan dari klien untuk perubahan
                                            requirements dan testing tambahan.
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="text-muted">Sep 22, 2025</div>
                                        <div class="text-secondary">11:30 AM</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary text-white">Planning</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary text-white">Active</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2"
                                                style="background-image: url(https://ui-avatars.com/api/?name=Budi+Santoso&background=28a745&color=fff)">
                                            </div>
                                            <div>
                                                <div class="fw-bold">Budi Santoso</div>
                                                <div class="text-muted small">Lead Developer</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-fill me-2" style="height: 6px; width: 60px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: 65%"></div>
                                            </div>
                                            <span class="text-muted small">65%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-wrap">
                                            Fase development dimulai. Semua dokumen perencanaan telah disetujui oleh
                                            stakeholder dan client.
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="text-muted">Sep 18, 2025</div>
                                        <div class="text-secondary">2:45 PM</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">New</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary text-white">Planning</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2"
                                                style="background-image: url(https://ui-avatars.com/api/?name=Andi+Pratama&background=206bc4&color=fff)">
                                            </div>
                                            <div>
                                                <div class="fw-bold">Andi Pratama</div>
                                                <div class="text-muted small">Project Manager</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-fill me-2" style="height: 6px; width: 60px;">
                                                <div class="progress-bar bg-secondary" role="progressbar"
                                                    style="width: 25%"></div>
                                            </div>
                                            <span class="text-muted small">25%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-wrap">
                                            Setup awal proyek selesai. Mulai memasuki fase perencanaan detail dan alokasi
                                            resource tim.
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="text-muted">Sep 15, 2025</div>
                                        <div class="text-secondary">10:00 AM</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">-</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">New</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2"
                                                style="background-image: url(https://ui-avatars.com/api/?name=System&background=6c757d&color=fff)">
                                            </div>
                                            <div>
                                                <div class="fw-bold">System</div>
                                                <div class="text-muted small">Automated</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-fill me-2" style="height: 6px; width: 60px;">
                                                <div class="progress-bar bg-light" role="progressbar" style="width: 5%">
                                                </div>
                                            </div>
                                            <span class="text-muted small">5%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-wrap">
                                            Proyek berhasil dibuat dan ditugaskan kepada tim. Proses setup awal dan kick-off
                                            meeting dijadwalkan.
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-muted">Showing <span>5</span> of <span>5</span> status changes</p>
                    <ul class="pagination m-0 ms-auto">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="m0 0h24v24H0z" fill="none" />
                                    <polyline points="15,6 9,12 15,18" />
                                </svg>
                                prev
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#">
                                next
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="m0 0h24v24H0z" fill="none" />
                                    <polyline points="9,6 15,12 9,18" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

            <!-- Follow Up Modal -->
        <div class="modal fade" id="followUpModal" tabindex="-1" aria-labelledby="followUpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="followUpModalLabel">Tambah Follow Up</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('prospect.show') }}" method="POST">
                @csrf
                <div class="modal-body">
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea id="hugerte-mytextarea" name="notes" placeholder="Tuliskan catatan follow up..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="progress" class="form-label">Progress (%)</label>
                    <input type="number" name="progress" id="progress" class="form-control" min="0" max="100" placeholder="Masukkan progress (misal 90)">
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
            </div>
        </div>
        </div>

        
@push('scripts')
    <!-- WYSIWYG Editor Tabler (lokal) -->
    <script src="{{ asset('assets/libs/hugerte/hugerte.min.js') }}"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        let options = {
          selector: "#hugerte-mytextarea",
          height: 300,
          menubar: false,
          statusbar: false,
          license_key: "gpl",
          plugins: [
            "advlist",
            "autolink",
            "lists",
            "link",
            "image",
            "charmap",
            "preview",
            "anchor",
            "searchreplace",
            "visualblocks",
            "code",
            "fullscreen",
            "insertdatetime",
            "media",
            "table",
            "code",
            "help",
            "wordcount",
          ],
          toolbar:
            "undo redo | formatselect | " +
            "bold italic backcolor | alignleft aligncenter " +
            "alignright alignjustify | bullist numlist outdent indent | " +
            "removeformat",
          content_style:
            "body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }",
        };
        if (localStorage.getItem("tablerTheme") === "dark") {
          options.skin = "oxide-dark";
          options.content_css = "dark";
        }
        hugeRTE.init(options);
        
            // Reset modal form on close
           const followUpModal = document.getElementById('followUpModal');
            followUpModal.addEventListener('hidden.bs.modal', function () {

                // Reset input progress
                document.getElementById('progress').value = '';

                // Reset isi WYSIWYG editor
                const editor = hugeRTE.get('#hugerte-mytextarea');
                if (editor) {
                    editor.setContent('');
                }

                const form = followUpModal.querySelector('form');
                form.reset();
            });
      });


    </script>
@endpush

@endsection
