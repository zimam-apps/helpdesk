
@push('css-page')
    <link rel="stylesheet" href="{{ asset('public/assets/css/plugins/flatpickr.min.css') }}">
@endpush

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h5>{{ __('Rating Performance') }}</h5>
                </div>
                <div class="d-flex flex-wrap gap-3">
                <div id="customDateRangeWrapper" style="display: none;">
                    <input type="text" id="date-range-picker" class="form-control" placeholder="Select Date Range" readonly />
                </div>
                <div>
                    <select id="chartRange" class="form-select">
                        <option value="last_7_days">{{ __('Last 7 Days') }}</option>
                        <option value="this_month" selected>{{ __('This Month') }}</option>
                        <option value="last_month">{{ __('Last Month') }}</option>
                        <option value="this_year">{{ __('This Year') }}</option>
                        <option value="custom">{{ __('Custom Date Range') }}</option>
                    </select>
                </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="agentRatingChart"></div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('public/assets/js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        let ratingChart;

        function fetchAgentRatingData(type = 'this_month', start = null, end = null) {
            $.ajax({
                url: "{{ route('reports.agent.ratings') }}",
                method: "GET",
                data: {
                    type: type,
                    start_date: start,
                    end_date: end
                },
                success: function (response) {
                    renderRatingChart(response.labels, response.data);
                }
            });
        }

        function renderRatingChart(labels, data) {
            const options = {
                series: [{
                    name: "{{ __('Average Rating') }}",
                    data: data
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: labels,
                    title: { text: '{{ __("Timeline") }}' }
                },
                yaxis: {
                    title: { text: '{{ __("Average Rating") }}' },
                    min: 0,
                    max: 5,
                    tickAmount: 5
                },
                tooltip: {
                    y: {
                        formatter: val => val.toFixed(2)
                    }
                },
                colors: ['#40607C']
            };

            if (ratingChart) ratingChart.destroy();
            ratingChart = new ApexCharts(document.querySelector("#agentRatingChart"), options);
            ratingChart.render();
        }

        // Date Range Picker
        $(document).ready(function () {
            let selectedType = $('#chartRange').val();

            const flatpickrInstance = flatpickr("#date-range-picker", {
                mode: "range",
                dateFormat: "Y-m-d",
                onClose: function (selectedDates) {
                    if (selectedDates.length === 2 && selectedType === 'custom') {
                        const start = selectedDates[0].toISOString().split('T')[0];
                        const end = selectedDates[1].toISOString().split('T')[0];
                        fetchAgentRatingData('custom', start, end);
                    }
                }
            });

            fetchAgentRatingData();

            $('#chartRange').on('change', function () {
                selectedType = $(this).val();
                if (selectedType === 'custom') {
                    $('#customDateRangeWrapper').show();
                } else {
                    $('#customDateRangeWrapper').hide();
                    fetchAgentRatingData(selectedType);
                }
            });
        });
    </script>
@endpush 