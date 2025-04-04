<!-- resources/views/components/employee-select.blade.php -->
@include('components.select2')

<div class="form-group">
    <label for="employee_id">Employee</label>
    <select class="form-control select2" id="employee_id" name="employee_id">
        @foreach ($employees as $emp)
            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                {{ $emp->name }}
            </option>
        @endforeach
    </select>
</div>
