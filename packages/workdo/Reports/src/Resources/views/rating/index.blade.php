@extends('layouts.admin')

@section('page-title')
    {{ __('Rating Reports') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Rating Reports') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Agent Performance Ratings') }}</h5>
            </div>
            <div class="card-body">
                <div id="agentRatingChart"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Rating Distribution') }}</h5>
            </div>
            <div class="card-body">
                <div id="ratingDistributionChart"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        let agentRatingChart;
        let ratingDistributionChart;

        // Agent Rating Chart
        function fetchAgentRatingData() {
            $.ajax({
                url: "{{ route('reports.rating.agent') }}",
                method: "GET",
                success: function(response) {
                    renderAgentRatingChart(response);
                }
            });
        }

        function renderAgentRatingChart(data) {
            const options = {
                series: [{
                    name: '{{ __("Average Rating") }}',
                    data: data.map(item => item.average)
                }],
                chart: {
                    type: 'bar',
                    toolbar: { show: false },
                    height: data.length * 50
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        barHeight: 70,
                        borderRadius: 4,
                        dataLabels: {
                            position: 'top',
                        },
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetX: -6,
                    style: {
                        fontSize: '12px',
                        colors: ['#fff'],
                    },
                    formatter: function(val) {
                        return val.toFixed(1) + ' ★\u200A \u200A \u200A';
                    }
                },
                stroke: {
                    show: true,
                    width: 1,
                    colors: ['#fff']
                },
                xaxis: {
                    categories: data.map(item => item.name),
                    title: { text: '{{ __("Average Rating") }}' },
                    min: 0,
                    max: 5,
                },
                yaxis: {
                    title: { text: '{{ __("Agents") }}' }
                },
                colors: ['#32637b'],
                tooltip: {
                    y: {
                        formatter: function(value, { dataPointIndex }) {
                            const item = data[dataPointIndex];
                            return value.toFixed(1) + ' ★ (' + item.count + ' {{ __("ratings") }})';
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

            if (agentRatingChart) agentRatingChart.destroy();
            agentRatingChart = new ApexCharts(document.querySelector("#agentRatingChart"), options);
            agentRatingChart.render();
        }

        // Rating Distribution Chart
        function fetchRatingDistributionData() {
            $.ajax({
                url: "{{ route('reports.rating.distribution') }}",
                method: "GET",
                success: function(response) {
                    renderRatingDistributionChart(response.labels, response.data);
                }
            });
        }

        function renderRatingDistributionChart(labels, data) {
            const options = {
                series: data,
                chart: {
                    type: 'donut',
                    height: 400
                },
                labels: labels.map(rating => rating + ' ★'),
                colors: ['#EE3D48', '#F15E3A', '#FEDB4D', '#0A9CFF', '#21BF06'],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: '{{ __("Total Ratings") }}',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + ' {{ __("Tickets") }}';
                        }
                    }
                }
            };

            if (ratingDistributionChart) ratingDistributionChart.destroy();
            ratingDistributionChart = new ApexCharts(document.querySelector("#ratingDistributionChart"), options);
            ratingDistributionChart.render();
        }

        $(document).ready(function () {
            fetchAgentRatingData();
            fetchRatingDistributionData();
        });
    </script>
@endpush 