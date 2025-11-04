@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Edit</div>
            <h2 class="page-title">Users</h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-cards">
        <div class="col-12">

            <form action="{{ route('user.update', $user->id) }}" method="POST" class="card" id="userForm">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h3 class="card-title">Edit User Information</h3>
                </div>
                <div class="card-body">
                    <!-- User Information -->
                    <div class="step-content" id="step-1">
                        <div class="row">
                            <!-- Unique ID -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Unique ID</label>
                                <input type="text" name="unique_id"
                                    class="form-control @error('unique_id') is-invalid @enderror" 
                                    value="{{ old('unique_id', $user->unique_id) }}"
                                    placeholder="Enter unique ID" required>
                                @error('unique_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- No Karyawan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">No Karyawan</label>
                                <input type="text" name="no_karyawan"
                                    class="form-control @error('no_karyawan') is-invalid @enderror"
                                    value="{{ old('no_karyawan', $user->no_karyawan) }}" 
                                    placeholder="Enter employee number" required>
                                @error('no_karyawan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Name</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" 
                                    placeholder="Enter full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" 
                                    placeholder="Enter email address" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Join Month -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Join Month</label>
                                <select name="join_month" class="form-control @error('join_month') is-invalid @enderror" required>
                                    <option value="">Select month</option>
                                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                        <option value="{{ $month }}" {{ old('join_month', $user->join_month) == $month ? 'selected' : '' }}>{{ $month }}</option>
                                    @endforeach
                                </select>
                                @error('join_month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Join Year -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Join Year</label>
                                <select name="join_year" class="form-control @error('join_year') is-invalid @enderror" required>
                                    <option value="">Select year</option>
                                    @for($year = date('Y'); $year >= 2000; $year--)
                                        <option value="{{ $year }}" {{ old('join_year', $user->join_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                                @error('join_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Division -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Division</label>
                                <select name="division_id" class="form-control @error('division_id') is-invalid @enderror" required>
                                    <option value="">Select division</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('division_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Role</label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                                    <option value="">Select role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ', strtolower($role->name))) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Leave blank to keep current password">
                                <small class="form-hint">Leave blank if you don't want to change the password</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Confirm new password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>




                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('user.index') }}" class="btn btn-link">Cancel</a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form submission validation
            document.getElementById('userForm').addEventListener('submit', function(e) {
                let formValid = true;
                const requiredFields = document.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        formValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!formValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
