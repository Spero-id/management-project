@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Convert Prospect to Project</h4>
                    <p class="mb-0 text-muted">Convert prospect "{{ $prospect->customer_name }}" to a project</p>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('prospects.convert', $prospect->id) }}">
                        @csrf

                        <!-- Prospect Information (Read-only) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Customer Name</label>
                                <input type="text" class="form-control" value="{{ $prospect->customer_name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Company</label>
                                <input type="text" class="form-control" value="{{ $prospect->company }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ $prospect->email }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" value="{{ $prospect->no_handphone }}" readonly>
                            </div>
                        </div>

                        <hr>

                        <!-- Project Details -->
                        <div class="mb-3">
                            <label for="project_name" class="form-label">Project Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('project_name') is-invalid @enderror" 
                                   id="project_name" 
                                   name="project_name" 
                                   value="{{ old('project_name', $prospect->customer_name . ' - Project') }}" 
                                   required>
                            @error('project_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="project_manager_id" class="form-label">Project Manager <span class="text-danger">*</span></label>
                            <select class="form-select @error('project_manager_id') is-invalid @enderror" 
                                    id="project_manager_id" 
                                    name="project_manager_id" 
                                    required>
                                <option value="">Select Project Manager</option>
                                @foreach($projectManagers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('project_manager_id') == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }} ({{ $manager->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('project_manager_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Project Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Enter project description...">{{ old('description', $prospect->note) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="delete_prospect" 
                                       name="delete_prospect" 
                                       value="1" 
                                       {{ old('delete_prospect') ? 'checked' : '' }}>
                                <label class="form-check-label" for="delete_prospect">
                                    Delete prospect after conversion
                                    <small class="text-muted d-block">If checked, the original prospect will be deleted after successful conversion.</small>
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('prospects.show', $prospect->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Prospect
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-exchange-alt"></i> Convert to Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate project name based on customer name and company
    const customerName = '{{ $prospect->customer_name }}';
    const company = '{{ $prospect->company }}';
    const projectNameInput = document.getElementById('project_name');
    
    if (projectNameInput.value === customerName + ' - Project') {
        projectNameInput.value = company + ' - ' + customerName + ' Project';
    }
});
</script>
@endsection