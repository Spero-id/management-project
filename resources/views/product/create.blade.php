@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Create</div>
            <h2 class="page-title">Users</h2>
        </div>
        <!-- Page title actions -->
        {{-- <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('user.create') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M12 5v14" />
                        <path d="M5 12h14" />
                    </svg>
                    Create New User
                </a>
            </div>
        </div> --}}
    </div>
@endsection

@section('content')
    <div class="row row-cards">
        <div class="col-12">

            <form action="{{ route('user.store') }}" method="POST" class="card" id="userForm">
                @csrf
                <div class="card-header">
                    <h3 class="card-title">User Information</h3>
                </div>
                <div class="card-body">
                    <!-- User Information -->
                    <div class="step-content" id="step-1">
                        <div class="row">
                            <!-- Unique ID -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Unique ID</label>
                                <input type="text" name="unique_id"
                                    class="form-control @error('unique_id') is-invalid @enderror" value="{{ old('unique_id') }}"
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
                                    value="{{ old('no_karyawan') }}" placeholder="Enter employee number" required>
                                @error('no_karyawan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Name</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="Enter full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="Enter email address" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Join Month -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Join Month</label>
                                <select name="join_month" class="form-control @error('join_month') is-invalid @enderror" required>
                                    <option value="">Select month</option>
                                    <option value="January" {{ old('join_month') == 'January' ? 'selected' : '' }}>January</option>
                                    <option value="February" {{ old('join_month') == 'February' ? 'selected' : '' }}>February</option>
                                    <option value="March" {{ old('join_month') == 'March' ? 'selected' : '' }}>March</option>
                                    <option value="April" {{ old('join_month') == 'April' ? 'selected' : '' }}>April</option>
                                    <option value="May" {{ old('join_month') == 'May' ? 'selected' : '' }}>May</option>
                                    <option value="June" {{ old('join_month') == 'June' ? 'selected' : '' }}>June</option>
                                    <option value="July" {{ old('join_month') == 'July' ? 'selected' : '' }}>July</option>
                                    <option value="August" {{ old('join_month') == 'August' ? 'selected' : '' }}>August</option>
                                    <option value="September" {{ old('join_month') == 'September' ? 'selected' : '' }}>September</option>
                                    <option value="October" {{ old('join_month') == 'October' ? 'selected' : '' }}>October</option>
                                    <option value="November" {{ old('join_month') == 'November' ? 'selected' : '' }}>November</option>
                                    <option value="December" {{ old('join_month') == 'December' ? 'selected' : '' }}>December</option>
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
                                        <option value="{{ $year }}" {{ old('join_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
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
                                        <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
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
                                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
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
                                <label class="form-label required">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Confirm Password</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Confirm password" required>
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
                            <button type="submit" class="btn btn-primary">Create User</button>
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
