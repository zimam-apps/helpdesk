@extends('layouts.admin')

@section('page-title')
    {{ __('Agent Reports') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Agent Reports') }}</li>
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('public/assets/css/plugins/flatpickr.min.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h5>{{ __('Agent Resolution Performance') }}</h5>
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
                <div id="agentPerformanceChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Agent Workload Distribution') }}</h5>
            </div>
            <div class="card-body">
                <div id="workloadChart"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Resolution Rate by Agent') }}</h5>
            </div>
            <div class="card-body">
                <div id="resolutionRateChart"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('public/assets/js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        let performanceChart;
        let workloadChart;
        let resolutionRateChart;

        // Agent Performance Chart
        function fetchPerformanceData(type = 'this_month', start = null, end = null) {
            $.ajax({
                url: "{{ route('reports.agent.resolutionData') }}",
                method: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    type: type,
                    start_date: start,
                    end_date: end
                },
                success: function(response) {
                    renderPerformanceChart(response.labels, response.series);
                }
            });
        }

        function renderPerformanceChart(labels, series) {
            const options = {
                series,
                chart: {
                    height: 350,
                    type: 'line',
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
                    title: { text: '{{ __("Tickets") }}' },
                    tickAmount: 3
                },
                tooltip: {
                    y: {
                        formatter: val => `${val} {{ __("tickets") }}`
                    }
                },
                legend: {
                    position: 'bottom'
                }
            };

            if (performanceChart) performanceChart.destroy();
            performanceChart = new ApexCharts(document.querySelector("#agentPerformanceChart"), options);
            performanceChart.render();
        }

        // Workload Distribution Chart
        function fetchWorkloadData() {
            $.ajax({
                url: "{{ route('reports.agent.workloadData') }}",
                method: "GET",
                success: function(response) {
                    renderWorkloadChart(response);
                }
            });
        }

        function renderWorkloadChart(data) {
            const options = {
                series: [{
                    name: '{{ __("New Ticket") }}',
                    data: data.map(item => item.new)
                }, {
                    name: '{{ __("In Progress") }}',
                    data: data.map(item => item.in_progress)
                }, {
                    name: '{{ __("On Hold") }}',
                    data: data.map(item => item.on_hold)
                }, {
                    name: '{{ __("Closed") }}',
                    data: data.map(item => item.closed)
                }, {
                    name: '{{ __("Resolved") }}',
                    data: data.map(item => item.resolved)
                }],
                chart: {
                    type: 'bar',
                    stacked: true,
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    }
                },
                colors: ['#747474', '#1794fb', '#d1b736', '#c6551c', '#0ee04d'],
                xaxis: {
                    categories: data.map(item => item.name),
                    title: {
                        text: '{{ __("Tickets") }}'
                    }
                },
                yaxis: {
                    title: {
                        text: '{{ __("Agents") }}'
                    }
                },
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + ' {{ __("tickets") }}';
                        }
                    }
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
            };

            if (workloadChart) workloadChart.destroy();
            workloadChart = new ApexCharts(document.querySelector("#workloadChart"), options);
            workloadChart.render();
        }

        // Resolution Rate Chart
        function fetchResolutionRateData() {
            $.ajax({
                url: "{{ route('reports.agent.performanceData') }}",
                method: "GET",
                success: function(response) {
                    renderResolutionRateChart(response);
                }
            });
        }

        function renderResolutionRateChart(data) {
            const options = {
                series: [{
                    name: '{{ __("Resolution Rate") }}',
                    data: data.map(item => item.resolution_rate)
                }],
                chart: {
                    type: 'bar',
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1) + '%';
                    }
                },
                xaxis: {
                    categories: data.map(item => item.name),
                    max: 100,
                    title: {
                        text: '{{ __("Resolution Rate (%)") }}'
                    }
                },
                yaxis: {
                    title: {
                        text: '{{ __("Agents") }}'
                    }
                },
                colors: ['#865afa'],
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value.toFixed(1) + '%';
                        }
                    }
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
            };

            if (resolutionRateChart) resolutionRateChart.destroy();
            resolutionRateChart = new ApexCharts(document.querySelector("#resolutionRateChart"), options);
            resolutionRateChart.render();
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
                        fetchPerformanceData('custom', start, end);
                    }
                }
            });

            fetchPerformanceData();
            fetchWorkloadData();
            fetchResolutionRateData();

            $('#chartRange').on('change', function () {
                selectedType = $(this).val();
                if (selectedType === 'custom') {
                    $('#customDateRangeWrapper').show();
                } else {
                    $('#customDateRangeWrapper').hide();
                    fetchPerformanceData(selectedType);
                }
            });
        });
    </script>
@endpush 