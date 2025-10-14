<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.2.0
* @link https://tabler.io
* Copyright 2018-2025 The Tabler Authors
* Copyright 2018-2025 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Dashboard - Tabler - Premium and Open Source dashboard template with responsive and high quality UI.</title>
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/libs/jsvectormap/dist/jsvectormap.css?1744816593') }}" rel="stylesheet" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ asset('/assets/dist/css/tabler.css?1744816593') }}" rel="stylesheet" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PLUGINS STYLES -->
    <link href="{{ asset('/assets/dist/css/tabler-flags.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/dist/css/tabler-socials.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/dist/css/tabler-payments.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/dist/css/tabler-vendors.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/dist/css/tabler-marketing.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/dist/css/tabler-themes.css?1744816593') }}" rel="stylesheet" />
    <!-- END PLUGINS STYLES -->
    <!-- BEGIN DEMO STYLES -->
    <link href="{{ asset('/assets/preview/css/demo.css?1744816593') }}" rel="stylesheet" />
    <!-- END DEMO STYLES -->
    <!-- BEGIN CUSTOM FONT -->
    <style>
        @import url("https://rsms.me/inter/inter.css");
    </style>
    <!-- END CUSTOM FONT -->

    @stack('styles')
</head>

<body>
    <!-- BEGIN GLOBAL THEME SCRIPT -->
    <script src="{{ asset('/assets/dist/js/tabler-theme.min.js?1744816593') }}"></script>
    <!-- END GLOBAL THEME SCRIPT -->
    <div class="page">
        <!--  BEGIN SIDEBAR  -->
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-transparent">
            <div class="container-fluid">
                <!-- BEGIN NAVBAR TOGGLER -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
                    aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- END NAVBAR TOGGLER -->
                <!-- BEGIN NAVBAR LOGO -->
                <div class="navbar-brand navbar-brand-autodark">
                    <a href=".">
                        <img src="{{ asset('/assets/images/SIS-logo NB.png') }}"
                            alt="SIS Logo"
                            class="navbar-brand-image"
                            style="width: 160px; height: auto; max-height: 100px; margin: 10px 0;">
                    </a>
                </div>
                <!-- END NAVBAR LOGO -->
                <div class="navbar-nav flex-row d-lg-none">
                    <div class="nav-item d-none d-lg-flex me-3">
                        <div class="btn-list">
                            <a href="https://github.com/tabler/tabler" class="btn btn-5" target="_blank"
                                rel="noreferrer">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/brand-github -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon icon-2">
                                    <path
                                        d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5" />
                                </svg>
                                Source code
                            </a>
                            <a href="https://github.com/sponsors/codecalm" class="btn btn-6" target="_blank"
                                rel="noreferrer">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/heart -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon text-pink icon-2">
                                    <path
                                        d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                </svg>
                                Sponsor
                            </a>
                        </div>
                    </div>
                    <div class="d-none d-lg-flex">
                        <div class="nav-item">
                            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode"
                                data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/moon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path
                                        d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                                </svg>
                            </a>
                            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode"
                                data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/sun -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path
                                        d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                                </svg>
                            </a>
                        </div>
                        <div class="nav-item dropdown d-none d-md-flex">
                            <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                                aria-label="Show notifications" data-bs-auto-close="outside" aria-expanded="false">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/bell -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path
                                        d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                    <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                </svg>
                                <span class="badge bg-red"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                                <div class="card">
                                    <div class="card-header d-flex">
                                        <h3 class="card-title">Notifications</h3>
                                        <div class="btn-close ms-auto" data-bs-dismiss="dropdown"></div>
                                    </div>
                                    <div class="list-group list-group-flush list-group-hoverable">
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span
                                                        class="status-dot status-dot-animated bg-red d-block"></span>
                                                </div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 1</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">Change
                                                        deprecated html tags to text decoration classes (#29604)</div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <!-- Download SVG icon from http://tabler.io/icons/icon/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon text-muted icon-2">
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span class="status-dot d-block"></span></div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 2</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">
                                                        justify-content:between ⇒ justify-content:space-between (#29734)
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions show">
                                                        <!-- Download SVG icon from http://tabler.io/icons/icon/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon text-yellow icon-2">
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span class="status-dot d-block"></span></div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 3</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">Update
                                                        change-version.js (#29736)</div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <!-- Download SVG icon from http://tabler.io/icons/icon/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon text-muted icon-2">
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span
                                                        class="status-dot status-dot-animated bg-green d-block"></span>
                                                </div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 4</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">Regenerate
                                                        package-lock.json (#29730)</div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <!-- Download SVG icon from http://tabler.io/icons/icon/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon text-muted icon-2">
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <a href="#" class="btn btn-2 w-100"> Archive all </a>
                                            </div>
                                            <div class="col">
                                                <a href="#" class="btn btn-2 w-100"> Mark all as read </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nav-item dropdown d-none d-md-flex me-3">
                            <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                                aria-label="Show app menu" data-bs-auto-close="outside" aria-expanded="false">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/apps -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path
                                        d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                    <path
                                        d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                    <path
                                        d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                    <path d="M14 7l6 0" />
                                    <path d="M17 4l0 6" />
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">My Apps</div>
                                        <div class="card-actions btn-actions">
                                            <a href="#" class="btn-action">
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/settings -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-1">
                                                    <path
                                                        d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body scroll-y p-2" style="max-height: 50vh">
                                        <div class="row g-0">
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/amazon.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Amazon</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/android.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Android</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/app-store.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Apple App Store</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/apple-podcast.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Apple Podcast</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/apple.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Apple</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/behance.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Behance</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/discord.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Discord</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/dribbble.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Dribbble</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/dropbox.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Dropbox</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/ever-green.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Ever Green</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/facebook.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Facebook</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/figma.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Figma</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/github.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">GitHub</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/gitlab.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">GitLab</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-ads.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Ads</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-adsense.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google AdSense</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-analytics.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Analytics</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-cloud.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Cloud</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-drive.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Drive</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-fit.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Fit</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-home.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Home</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-maps.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Maps</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-meet.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Meet</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-photos.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Photos</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-play.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Play</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-shopping.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Shopping</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google-teams.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Teams</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/google.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/instagram.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Instagram</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/klarna.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Klarna</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/linkedin.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">LinkedIn</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/mailchimp.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Mailchimp</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/medium.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Medium</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/messenger.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Messenger</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/meta.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Meta</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/monday.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Monday</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/netflix.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Netflix</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/notion.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Notion</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/office-365.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Office 365</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/opera.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Opera</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/paypal.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">PayPal</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/petreon.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Patreon</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/pinterest.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Pinterest</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/play-store.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Play Store</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/quora.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Quora</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/reddit.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Reddit</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/shopify.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Shopify</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/skype.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Skype</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/slack.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Slack</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/snapchat.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Snapchat</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/soundcloud.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">SoundCloud</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/spotify.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Spotify</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/stripe.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Stripe</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/telegram.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Telegram</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/tiktok.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">TikTok</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/tinder.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Tinder</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/trello.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Trello</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/truth.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Truth</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/tumblr.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Tumblr</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/twitch.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Twitch</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/twitter.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Twitter</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/vimeo.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Vimeo</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/vk.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">VK</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/watppad.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Wattpad</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/webflow.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Webflow</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/whatsapp.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">WhatsApp</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/wordpress.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">WordPress</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/xing.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Xing</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/yelp.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Yelp</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/youtube.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">YouTube</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/zapier.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Zapier</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/zendesk.svg') }}"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Zendesk</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="{{ asset('/assets/static/brands/zoom.svg') }} "
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Zoom</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            <span class="avatar avatar-sm"
                                style="background-image: url({{ asset('/assets/static/avatars/000m.jpg') }})">
                            </span>
                            <div class="d-none d-xl-block ps-2">
                                <div>Paweł Kuna</div>
                                <div class="mt-1 small text-secondary">UI Designer</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="#" class="dropdown-item">Status</a>
                            <a href="./profile.html" class="dropdown-item">Profile</a>
                            <a href="#" class="dropdown-item">Feedback</a>
                            <div class="dropdown-divider"></div>
                            <a href="./settings.html" class="dropdown-item">Settings</a>
                            <a href="./sign-in.html" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <!-- BEGIN NAVBAR MENU -->
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <a class="nav-link" href="./">
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler.io/icons/icon/home -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                        <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                    </svg></span>
                                <span class="nav-link-title"> Dashboard </span>
                            </a>
                        </li>
                        {{-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                data-bs-auto-close="false" role="button" aria-expanded="false">
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler.io/icons/icon/package -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                        <path d="M12 12l8 -4.5" />
                                        <path d="M12 12l0 9" />
                                        <path d="M12 12l-8 -4.5" />
                                        <path d="M16 5.25l-8 4.5" />
                                    </svg></span>
                                <span class="nav-link-title"> Interface </span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">

                                        <a class="dropdown-item" href="./badges.html"> Badges </a>
                                        <a class="dropdown-item" href="./blank.html"> Blank page </a>
                                        <a class="dropdown-item" href="./buttons.html"> Buttons </a>

                                    </div>

                                </div>
                            </div>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="./form-elements.html">
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler.io/icons/icon/checkbox -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                        <path d="M9 11l3 3l8 -8" />
                                        <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                                    </svg></span>
                                <span class="nav-link-title"> Project </span>
                            </a>
                        </li>

                    </ul>
                    <!-- END NAVBAR MENU -->
                </div>
            </div>
        </aside>
        <!--  END SIDEBAR  -->
        <div class="page-wrapper">
            <!-- BEGIN PAGE HEADER -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    @yield('header')
                </div>
            </div>
            <!-- END PAGE HEADER -->
            <!-- BEGIN PAGE BODY -->
            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
            <!-- END PAGE BODY -->
            <!--  BEGIN FOOTER  -->
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><a href="https://tabler.io/docs" target="_blank"
                                        class="link-secondary" rel="noopener">Documentation</a></li>
                                <li class="list-inline-item"><a href="./license.html"
                                        class="link-secondary">License</a></li>
                                <li class="list-inline-item">
                                    <a href="https://github.com/tabler/tabler" target="_blank" class="link-secondary"
                                        rel="noopener">Source code</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="https://github.com/sponsors/codecalm" target="_blank"
                                        class="link-secondary" rel="noopener">
                                        <!-- Download SVG icon from http://tabler.io/icons/icon/heart -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon text-pink icon-inline icon-4">
                                            <path
                                                d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                        </svg>
                                        Sponsor
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; 2025
                                    <a href="." class="link-secondary">Tabler</a>. All rights reserved.
                                </li>
                                <li class="list-inline-item">
                                    <a href="./changelog.html" class="link-secondary" rel="noopener"> v1.2.0 </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
            <!--  END FOOTER  -->
        </div>
    </div>

    <!-- BEGIN PAGE LIBRARIES -->
    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js?1744816593') }}" defer></script>
    <script src="{{ asset('assets/libs/jsvectormap/dist/jsvectormap.min.js?1744816593') }}" defer></script>
    <script src="{{ asset('assets/libs/jsvectormap/dist/maps/world.js?1744816593') }}" defer></script>
    <script src="{{ asset('assets/libs/jsvectormap/dist/maps/world-merc.js?1744816593') }}" defer></script>
    <script src="{{ asset('/assets/dist/js/tabler.min.js?1744816593') }}" defer></script>
    <script src="{{ asset('/assets/preview/js/demo.min.js?1744816593') }}" defer></script>

    @stack('scripts')


</body>

</html>
