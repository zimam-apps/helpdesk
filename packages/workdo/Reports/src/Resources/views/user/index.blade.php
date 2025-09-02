@extends('layouts.admin')

@section('page-title')
    {{ __('User Reports') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('User Reports') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Most Active Users') }}</h5>
            </div>
            <div class="card-body">
                <div id="userActivityChart" style="width: 100%; min-height: 400px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        let chart;

        function fetchUserActivityChartData() {
            $.ajax({
                url: "{{ route('reports.user.activityChart') }}",
                method: "GET",
                success: function(response) {
                    renderUserActivityChart(response.labels, response.data);
                }
            });
        }

        function renderUserActivityChart(labels, data) {
           const options = {
                series: [{
                    name: '{{ __("Tickets") }}',
                    data: data
                }],
                chart: {
                    type: 'bar',
                    toolbar: { show: false },
                    height: data.length * 40 // reduced height per item to tighten gaps
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        barHeight: 80, // increased bar height percentage = less gap
                        borderRadius: 6,
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
                        colors: ['#fff']
                    }
                },
                stroke: {
                    show: true,
                    width: 1,
                    colors: ['#fff']
                },
                xaxis: {
                    categories: labels,
                    title: { 
                        text: '{{ __("Tickets") }}',
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold'
                        }
                    },
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: { 
                        text: '{{ __("Users") }}',
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold'
                        }
                    },
                    labels: {
                        style: {
                            fontSize: '12px'
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
                colors: ['#0CAF60'],
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + ' {{ __("tickets") }}';
                        }
                    }
                },
                legend: {
                    show: false
                },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        plotOptions: {
                            bar: {
                                barHeight: 60,
                                borderRadius: 4
                            }
                        },
                        dataLabels: {
                            style: {
                                fontSize: '10px'
                            }
                        },
                        xaxis: {
                            labels: {
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        }
                    }
                }]
            };





            if (chart) chart.destroy();
            chart = new ApexCharts(document.querySelector("#userActivityChart"), options);
            chart.render();
        }

        $(document).ready(function () {
            fetchUserActivityChartData();
        });
    </script>
@endpush 