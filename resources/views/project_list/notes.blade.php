@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 rounded-4 p-4" style="max-width: 800px; margin:auto; background: #f9fafc;">
        
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-1" style="font-size: 1.4rem;">
                    <i class="bi bi-journal-text me-2"></i> Project Notes
                </h3>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Tulis dan simpan catatan untuk project ini</p>
            </div>
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm px-3 py-2" style="font-size: 0.95rem;">
                ← Back
            </a>
        </div>

        {{-- Form Catatan --}}
        <div class="bg-white rounded-4 p-4 shadow-sm border-start border-4 border-primary">
            <h5 class="fw-bold text-primary mb-3" style="font-size: 1.1rem;">Catatan Project</h5>

            <textarea id="noteArea" class="form-control mb-3" rows="7" 
                style="font-size: 0.95rem; line-height: 1.5; padding: 0.9rem; border-radius: 10px;"
                placeholder="Tulis catatan di sini..."></textarea>

            <div class="d-flex gap-2">
                <button id="saveNote" class="btn btn-primary fw-semibold px-4 py-2" style="font-size: 0.95rem;">
                    <i class="bi bi-save me-2"></i> Save
                </button>
                <button id="editNote" class="btn btn-warning fw-semibold px-4 py-2 text-dark" style="font-size: 0.95rem;" disabled>
                    <i class="bi bi-pencil-square me-2"></i> Edit
                </button>
                <button id="deleteNote" class="btn btn-danger fw-semibold px-4 py-2" style="font-size: 0.95rem;" disabled>
                    <i class="bi bi-trash me-2"></i> Delete
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const noteArea = document.getElementById('noteArea');
    const saveBtn = document.getElementById('saveNote');
    const editBtn = document.getElementById('editNote');
    const deleteBtn = document.getElementById('deleteNote');

    let note = localStorage.getItem('single_project_note') || '';

    if (note) {
        noteArea.value = note;
        editBtn.disabled = false;
        deleteBtn.disabled = false;
    }

    saveBtn.addEventListener('click', function () {
        const text = noteArea.value.trim();
        if (text === '') {
            alert('Catatan tidak boleh kosong!');
            return;
        }
        localStorage.setItem('single_project_note', text);
        alert('Catatan berhasil disimpan.');
        editBtn.disabled = false;
        deleteBtn.disabled = false;
    });

    editBtn.addEventListener('click', function () {
        noteArea.focus();
        alert('Kamu bisa ubah catatan sekarang, lalu klik Save untuk menyimpan.');
    });

    deleteBtn.addEventListener('click', function () {
        if (confirm('Hapus catatan ini?')) {
            localStorage.removeItem('single_project_note');
            noteArea.value = '';
            editBtn.disabled = true;
            deleteBtn.disabled = true;
            alert('Catatan berhasil dihapus.');
        }
    });
});
</script>
@endsection
