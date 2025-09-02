@extends('layouts.admin')

@section('page-title')
    {{ __('Ticket Reports') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Ticket Reports') }}</li>
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('public/assets/css/plugins/flatpickr.min.css') }}">
    <style>
        #assignmentChart .apexcharts-canvas {
            margin: 0 auto;
        }
    </style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h5>{{ __('Tickets') }}</h5>
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
                <div id="chartBar"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-6 mb-4">
        <div class="card h-100 mb-0">
            <div class="card-header">
                <h5>{{ __('Tickets Platform') }}</h5>
            </div>
            <div class="card-body">
                <div id="platformChart"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-6 mb-4">
        <div class="card h-100 mb-0">
            <div class="card-header">
                <h5>{{ __('Ticket Assignment Status') }}</h5>
            </div>
            <div class="card-body">
                <div id="assignmentChart"></div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
    <script src="{{ asset('public/assets/js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        let chart;
        let platformChart;
        let assignmentChart;

        // Ticket Chart
        function fetchChartData(type = 'year_month', start = null, end = null) {
            $.ajax({
                url: "{{ route('reports.ticket.chartData') }}",
                method: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    type: type,
                    start_date: start,
                    end_date: end
                },
                success: function(response) {
                    renderChart(response.labels, response.data);
                }
            });
        }

        function renderChart(labels, data) {
            const options = {
                series: [{
                    name: '{{ __("Tickets") }}',
                    data: data
                }],
                chart: {
                    height: 300,
                    type: 'area',
                    toolbar: { show: false }    // true for menu options
                },
                xaxis: {
                    categories: labels,
                    title: { text: '{{ __("Timeline") }}' }
                },
                colors: ['#4a3c7f'],
                dataLabels: { enabled: false },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                grid: { strokeDashArray: 4 },
                markers: {
                    size: 4,
                    colors: ['#4a3c7f'],
                    strokeWidth: 2,
                    hover: { size: 7 }
                },
                yaxis: {
                    title: { text: '{{ __("Tickets") }}' },
                    tickAmount: 3
                }
            };

            if (chart) chart.destroy();
            chart = new ApexCharts(document.querySelector("#chartBar"), options);
            chart.render();
        }
        
        // Platform Chart
        function fetchPlatformData() {
            $.ajax({
                url: "{{ route('reports.ticket.platformData') }}",
                method: "GET",
                success: function(response) {
                    renderPlatformChart(response.labels, response.data);
                }
            });
        }

        function renderPlatformChart(labels, data) {
            const colorPalette = [
                '#e25200',
                '#152f4e',
                '#efa80f',
                '#549e29',
                '#028ae5',
                '#fd0013',
                '#6f359d',
                '#b6de2e',
            ];

            const platformColors = labels.map((label, index) => colorPalette[index % colorPalette.length]);

            const chartData = labels.map((label, index) => ({
                x: label,
                y: data[index],
                fillColor: platformColors[index],
            }));

            const options = {
                series: [{
                    name: 'Tickets',
                    data: chartData
                }],
                chart: {
                    type: 'bar',
                    height: 450,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        distributed: true,
                        horizontal: false,
                        columnWidth: '60%',
                        endingShape: 'rounded'
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: false
                },
                xaxis: {
                    type: 'category',
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '13px'
                        }
                    },
                    title: {
                        text: '{{ __("Platform") }}'
                    }
                },
                yaxis: {
                    title: {
                        text: '{{ __("Tickets") }}'
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + ' tickets';
                        }
                    }
                }
            };

            if (platformChart) platformChart.destroy();
            platformChart = new ApexCharts(document.querySelector("#platformChart"), options);
            platformChart.render();
        }

        // Assignment Chart
        function fetchAssignmentData() {
            $.ajax({
                url: "{{ route('reports.ticket.assignData') }}",
                method: "GET",
                success: function(response) {
                    renderAssignmentChart(response.labels, response.data);
                }
            });
        }

        function renderAssignmentChart(labels, data) {
            const options = {
                series: data,
                chart: {
                    type: 'donut',
                    height: 400
                },
                labels: labels,
                colors: ['#00E396', '#FEB019'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '22px',
                                    fontFamily: 'Helvetica, Arial, sans-serif',
                                    fontWeight: 600,
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '16px',
                                    fontFamily: 'Helvetica, Arial, sans-serif',
                                    fontWeight: 400,
                                    offsetY: 16,
                                    formatter: function (val) {
                                        return val + ' tickets';
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '16px',
                                    fontWeight: 600,
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' tickets';
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            if (assignmentChart) assignmentChart.destroy();
            assignmentChart = new ApexCharts(document.querySelector("#assignmentChart"), options);
            assignmentChart.render();
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
                        fetchChartData('custom', start, end);
                    }
                }
            });

            fetchChartData();
            fetchPlatformData();
            fetchAssignmentData();

            $('#chartRange').on('change', function () {
                selectedType = $(this).val();
                if (selectedType === 'custom') {
                    $('#customDateRangeWrapper').show();
                } else {
                    $('#customDateRangeWrapper').hide();
                    fetchChartData(selectedType);
                }
            });
        });
    </script>
@endpush
