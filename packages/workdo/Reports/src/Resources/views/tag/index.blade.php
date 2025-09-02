@extends('layouts.admin')

@section('page-title')
    {{ __('Tag Reports') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Tag Reports') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Tag Distribution') }}</h5>
            </div>
            <div class="card-body">
                <div id="tagDistributionChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Tag Trends') }}</h5>
            </div>
            <div class="card-body">
                <div id="tagTrendChart"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        let distributionChart;
        let trendChart;

        // Tag Distribution Chart (Donut Chart)
        function fetchTagDistributionData() {
            $.ajax({
                url: "{{ route('reports.tag.distribution') }}",
                method: "GET",
                success: function(response) {
                    renderDistributionChart(response.labels, response.data, response.colors);
                }
            });
        }

        function renderDistributionChart(labels, data, colors) {
            const options = {
                series: data,
                chart: {
                    height: 400,
                    type: 'donut',
                    toolbar: { show: false }
                },
                labels: labels,
                colors: colors,
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
                                    offsetY: 16
                                },
                                total: {
                                    show: true,
                                    label: '{{ __("Total") }}',
                                    fontSize: '16px',
                                    fontWeight: 600,
                                    color: '#373d3f'
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    fontSize: '14px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 400,
                    markers: {
                        width: 12,
                        height: 12,
                        strokeWidth: 0,
                        strokeColor: '#fff',
                        radius: 12
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 5
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + ' {{ __("tickets") }}';
                        }
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            if (distributionChart) distributionChart.destroy();
            distributionChart = new ApexCharts(document.querySelector("#tagDistributionChart"), options);
            distributionChart.render();
        }

        // Tag Trends Chart (Line Chart)
        function fetchTagTrendData() {
            $.ajax({
                url: "{{ route('reports.tag.trends') }}",
                method: "GET",
                success: function(response) {
                    renderTrendChart(response.labels, response.data);
                }
            });
        }

        function renderTrendChart(labels, data) {
            const options = {
                series: data,
                chart: {
                    height: 400,
                    type: 'line',
                    toolbar: { show: false },
                    zoom: {
                        enabled: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: labels,
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: '{{ __("Tickets") }}'
                    },
                    labels: {
                        formatter: function(value) {
                            return Math.round(value);
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + ' {{ __("tickets") }}';
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    fontSize: '14px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 400
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            if (trendChart) trendChart.destroy();
            trendChart = new ApexCharts(document.querySelector("#tagTrendChart"), options);
            trendChart.render();
        }

        // Initialize charts
        $(document).ready(function () {
            fetchTagDistributionData();
            fetchTagTrendData();
        });
    </script>
@endpush 