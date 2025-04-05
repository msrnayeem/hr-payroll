@once
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#date').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear',
                        format: 'YYYY-MM-DD'
                    }
                });

                $('#date').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                        'YYYY-MM-DD'));
                });

                $('#date').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });

                // Prevent empty 'date' from being submitted
                $('.form').on('submit', function() {
                    if ($('#date').val().trim() === '') {
                        $('#date').removeAttr('name');
                    }
                    a
                });
            });
        </script>
    @endpush
@endonce

<div class="form-group">
    <label for="date">Date Range</label>
    <input type="text" id="date" name="date" class="form-control" value="{{ request('date') }}"
        placeholder="Select date range">
</div>
