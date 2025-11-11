@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <div class="page-pretitle">Create</div>
            <h2 class="page-title">New Prospect</h2>
        </div>
    </div>
@endsection

@section('content')
    {{-- Success & Error Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-important alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon alert-icon">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </div>
                <div class="ms-2">
                    {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon alert-icon">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                        </path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <div class="ms-2">
                    {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon alert-icon">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                        </path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <div class="ms-2">
                    <strong>Terdapat kesalahan pada form:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#customer" class="nav-link active" data-bs-toggle="tab"> Customer Information
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#quotation" class="nav-link" data-bs-toggle="tab">Equipment</a>
                </li>
                <li class="nav-item">
                    <a href="#installation" class="nav-link @if (!$quotation) disabled @endif"
                        data-bs-toggle="tab"
                        @if (!$quotation) onclick="return false;" style="cursor: not-allowed; opacity: 0.5;" title="Create a quotation first" @endif>
                        Installation
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active show" id="customer">
                    <x-prospect.form :route="route('prospect.update', $prospect->id)" :prospectStatuses="$prospectStatuses" :sales-user="$salesUser" :prospect="$prospect"
                        type="create" />
                </div>
                <div class="tab-pane" id="quotation">
                    <div>
                        <x-quotation.form :route="route('quotation.update', $quotation->id)" :prospect="$prospect" :quotation="$quotation" type="create" />
                    </div>
                </div>
                <div class="tab-pane" id="installation">
                    @if ($quotation)
                        <div>
                            @php
                                $installationRoute =
                                    $quotation->installationItems?->count() > 0
                                        ? route('installation.update', $quotation->id)
                                        : route('installation.store');
                                $installation =
                                    $quotation->installationItems?->count() > 0 ? $quotation->installationItems : null;
                            @endphp
                            <x-installation.form :route="$installationRoute" :quotation="$quotation" :installation="$installation" :installation-categories="$installationCategories"
                                :accommodationCategory="$accommodationCategory" :accommodationItems="$quotation->accommodationItems" type="create" />
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="ms-2">
                                    <strong>No quotation found</strong>
                                    <p class="mb-0">Please create a quotation first before adding installation details.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endsection
