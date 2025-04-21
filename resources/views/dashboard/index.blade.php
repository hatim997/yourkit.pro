@extends('layouts.master')

@section('title', 'Dashboard')

@section('css')
@endsection

@section('breadcrumb-items')
    {{-- <li class="breadcrumb-item active">{{ __('Dashboard') }}</li> --}}
@endsection

@section('content')
    <div class="row g-6">
        <!-- View sales -->
        <div class="col-xl-4">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-7">
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0">Welcome back, {{ Auth::user()->name }}! 🎉</h5>
                            <p class="mb-2">Take a quick look at what's new in your <br> dashboard today</p>
                            <p class="mb-2">Stay updated and manage your tasks <br> with ease and clarity</p>
                            <a href="{{ route('profile.index') }}" class="btn btn-primary">View Profile</a>
                        </div>
                    </div>
                    <div class="col-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/card-advance-sale.png') }}" height="140"
                                alt="view sales" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- View sales -->

        <!-- Statistics -->
        <div class="col-xl-8 col-md-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Orders Statistics</h5>
                </div>
                <div class="card-body d-flex align-items-end">
                    <div class="w-100">
                        <div class="row gy-3">
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-primary me-4 p-2">
                                        <i class="icon-base ti ti-list-details icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $totalOrders }}</h5>
                                        <small>Total Orders</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-info me-4 p-2">
                                        <i class="icon-base ti ti-search icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $statusCounts['underReview'] }}</h5>
                                        <small>Under Review</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-primary me-4 p-2">
                                        <i class="icon-base ti ti-check icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $statusCounts['designApproved'] }}</h5>
                                        <small>Design Approved</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-warning me-4 p-2">
                                        <i class="icon-base ti ti-hourglass icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $statusCounts['waitingGarments'] }}</h5>
                                        <small>Waiting for Garments</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-success me-4 p-2">
                                        <i class="icon-base ti ti-palette icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $statusCounts['sentToDesigner'] }}</h5>
                                        <small>Sent to Graphic Designer</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-danger me-4 p-2">
                                        <i class="icon-base ti ti-tools icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $statusCounts['inProduction'] }}</h5>
                                        <small>In production</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Statistics -->

        <div class="col-xxl-12 col-12">
            <div class="row g-6">
                <!-- Profit last month -->
                <div class="col-xl-6 col-sm-6">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h5 class="card-title mb-1">Profit</h5>
                            <p class="card-subtitle">Last Month</p>
                        </div>
                        <div class="card-body">
                            <div class="mb-3" id="profitLastMonth"></div>
                            <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                                <h4 class="mb-0">{{ \App\Helpers\Helper::formatCurrency($lastMonthProfit) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Profit last month -->

                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- <script src="{{asset('assets/js/app-ecommerce-dashboard.js')}}"></script> --}}
    <script>
        const profitData = @json($profits);
        const profitLabels = @json($dates);

        // Profit last month Line Chart
        // --------------------------------------------------------------------
        let cardColor, labelColor, legendColor, headingColor, borderColor;

        if (isDarkStyle) {
            cardColor = config.colors_dark.cardColor;
            labelColor = config.colors_dark.textMuted;
            legendColor = config.colors_dark.bodyColor;
            headingColor = config.colors_dark.headingColor;
            borderColor = config.colors_dark.borderColor;
        } else {
            cardColor = config.colors.cardColor;
            labelColor = config.colors.textMuted;
            legendColor = config.colors.bodyColor;
            headingColor = config.colors.headingColor;
            borderColor = config.colors.borderColor;
        }


        const profitLastMonthEl = document.querySelector('#profitLastMonth'),
            profitLastMonthConfig = {
                chart: {
                    height: 110,
                    type: 'line',
                    parentHeightOffset: 0,
                    toolbar: {
                        show: false
                    }
                },
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 6,
                    xaxis: {
                        lines: {
                            show: true,
                            colors: '#000'
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    },
                    padding: {
                        top: -18,
                        left: -4,
                        right: 7,
                        bottom: -10
                    }
                },
                colors: [config.colors.info],
                stroke: {
                    width: 2
                },
                series: [{
                    name: 'Profit',
                    data: profitData
                }],
                xaxis: {
                    categories: profitLabels,
                    labels: {
                        show: true,
                        rotate: -45,
                        style: {
                            fontSize: '10px',
                            colors: labelColor
                        }
                    },
                    axisTicks: {
                        show: true
                    },
                    axisBorder: {
                        show: true
                    }
                },
                yaxis: {
                    labels: {
                        show: true,
                        style: {
                            fontSize: '10px',
                            colors: labelColor
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    y: {
                        formatter: function(val) {
                            return "$" + parseFloat(val).toFixed(2);
                        }
                    },
                    x: {
                        format: 'dd MMM'
                    }
                },
                markers: {
                    size: 3.5,
                    fillColor: config.colors.info,
                    strokeColors: 'transparent',
                    strokeWidth: 3.2,
                    hover: {
                        size: 5.5
                    }
                },
                responsive: [{
                        breakpoint: 1442,
                        options: {
                            chart: {
                                height: 100
                            }
                        }
                    },
                    {
                        breakpoint: 1025,
                        options: {
                            chart: {
                                height: 86
                            }
                        }
                    },
                    {
                        breakpoint: 769,
                        options: {
                            chart: {
                                height: 93
                            }
                        }
                    }
                ]
            };

        if (typeof profitLastMonthEl !== undefined && profitLastMonthEl !== null) {
            const profitLastMonth = new ApexCharts(profitLastMonthEl, profitLastMonthConfig);
            profitLastMonth.render();
        }


    </script>

@endsection
