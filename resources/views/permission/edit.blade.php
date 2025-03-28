@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Permissions';
        $breadcrumbs = [['title' => 'Permissions', 'url' => route('permissions.index')]];
        $breadcrumbs[] = ['title' => 'Edit Permissions', 'url' => route('permissions.edit', $permission->id)];
    @endphp
    <div class="container">
        <h1>Update Permission</h1>
        <form action="{{ route('permissions.update', $permission) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Permission Name</label>
                <input type="text" name="name" id="name" class="form-control" required
                    value="{{ old('name', $permission->name) }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
