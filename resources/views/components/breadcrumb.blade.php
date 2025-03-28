<!-- components/breadcrumb.blade.php -->
@php
    // Default values if not provided
    $pageTitle = $pageTitle ?? 'Dashboard';
    $breadcrumbs = $breadcrumbs ?? [['title' => 'Dashboard', 'url' => route('dashboard')]];
@endphp

<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <!-- Page Title -->
            <div class="col-sm-6">
                <h3 class="mb-0">{{ $pageTitle }}</h3>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    @foreach ($breadcrumbs as $index => $breadcrumb)
                        @if ($loop->last)
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['title'] }}</li>
                        @else
                            <li class="breadcrumb-item"><a
                                    href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->
