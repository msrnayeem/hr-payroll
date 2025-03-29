@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Permissions';
        $breadcrumbs = [['title' => 'Permissions', 'url' => route('permissions.index')]];
        $breadcrumbs[] = ['title' => 'All Permissions', 'url' => route('permissions.index')];
    @endphp

    <div class="container-fluid" style="background-color: #f4f6f9;">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h5 class="card-title">
                    Create Permission
                </h5>
                <div class="card-tools">
                    <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-outline-primary">
                        Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Permission Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
