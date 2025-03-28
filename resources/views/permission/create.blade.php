@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Permission</h1>
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Permission Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Permission</button>
        </form>
    </div>
@endsection
