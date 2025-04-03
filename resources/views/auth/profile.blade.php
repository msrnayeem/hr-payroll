@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'My Profile';
        $breadcrumbs = [['title' => 'Dashboard', 'url' => route('dashboard')], ['title' => 'Profile', 'url' => '#']];
    @endphp

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">My Profile</h3>
                <div class="card-tools">
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary">
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <!-- Profile Image Section -->
                        <div class="card card-primary card-outline shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="position-relative d-inline-block mb-3">
                                    <img src="{{ auth()->user()->profile_image ? Storage::url(auth()->user()->profile_image) : 'https://via.placeholder.com/150' }}"
                                        class="rounded-circle img-fluid border border-primary border-3"
                                        style="width: 150px; height: 150px; object-fit: cover;" alt="Profile Picture">
                                    <span
                                        class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2 border border-white"
                                        style="width: 20px; height: 20px;"></span>
                                </div>
                                <h4 class="mb-2 fw-bold">{{ auth()->user()->name }}</h4>
                                <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                                <small class="text-muted d-block mt-1">
                                    Member since {{ auth()->user()->created_at->format('M Y') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <!-- Profile Details -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Personal Information</h5>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">User ID</dt>
                                    <dd class="col-sm-8">{{ auth()->user()->id }}</dd>

                                    <dt class="col-sm-4">Full Name</dt>
                                    <dd class="col-sm-8">{{ auth()->user()->name }}</dd>

                                    <dt class="col-sm-4">Email</dt>
                                    <dd class="col-sm-8">{{ auth()->user()->email }}</dd>

                                    <dt class="col-sm-4">Phone</dt>
                                    <dd class="col-sm-8">{{ auth()->user()->phone }}</dd>

                                    <dt class="col-sm-4">Status</dt>
                                    <dd class="col-sm-8">{{ auth()->user()->status }}</dd>

                                    <dt class="col-sm-4">Account Created</dt>
                                    <dd class="col-sm-8">
                                        {{ auth()->user()->created_at->format('M d, Y H:i') }}
                                    </dd>

                                    <dt class="col-sm-4">Last Updated</dt>
                                    <dd class="col-sm-8">
                                        {{ auth()->user()->updated_at->format('M d, Y H:i') }}
                                    </dd>
                                </dl>

                                <div class="mt-4">
                                    <a href="{{ route('profile.edit') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
