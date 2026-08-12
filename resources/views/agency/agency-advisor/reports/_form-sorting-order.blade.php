<?php
$sortingOrders = \App\Helpers\ReportManager::getSortingOrdersByReportType($type);
?>
<h4 class="row page-title uppercase">Sort Criteria</h4>

    @foreach($sortingOrders as $key => $value)

        <div class="form-group row">
            <div class="col-md-12">
                <div class="row">
                    @php
                        $selectedSortBy = request('sort_by', null);
                        $selectedSortOrder = request($key, $configReport->sorting_order->$key ?? '');
                        $isChecked = ($selectedSortBy !== null) ? ($selectedSortBy === $key) : (!empty($configReport->sorting_order->$key));
                    @endphp
                    <div class="col-md-2">
                        <div class="form-check form-check-inline mt-1">
                            <input type="radio" name="sort_by" value="{{ $key }}" class="form-check-input sort-by-radio" id="id_sort_by_{{ $key }}"{{ $isChecked ? ' checked' : '' }}>
                            <label class="form-check-label" for="id_sort_by_{{ $key }}">{{ $value }}</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="{{ $key }}" class="form-control sort-by-dropdown">
                            <option value="">Select</option>
                            <option value="asc"{{ $selectedSortOrder === 'asc' ? ' selected' : '' }}>Ascending</option>
                            <option value="desc"{{ $selectedSortOrder === 'desc' ? ' selected' : '' }}>Descending</option>
                        </select>
                        {{--, 'disabled' => !$configReport->sorting_order->$key--}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
    $(document).ready(function() {
        // Disable all dropdowns by default
        $('.sort-by-dropdown').prop('disabled', true);

        // Enable the corresponding dropdown when a radio button is selected
        $('.sort-by-radio').change(function() {
            // Disable all dropdowns
            $('.sort-by-dropdown').prop('disabled', true);

            // Get the selected radio button and enable its corresponding dropdown
            var selectedRadio = $('input[name="sort_by"]:checked');
            if (selectedRadio.length > 0) {
                var selectedDropdown = selectedRadio.closest('.form-group').find('.sort-by-dropdown');
                selectedDropdown.prop('disabled', false);
            }
        });

        // Enable the dropdowns corresponding to any pre-selected radio buttons
        $('.sort-by-radio:checked').each(function() {
            var selectedDropdown = $(this).closest('.form-group').find('.sort-by-dropdown');
            selectedDropdown.prop('disabled', false);
        });
    });

</script>


