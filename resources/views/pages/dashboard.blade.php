@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard'
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-badge text-warning"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Vacancies</p>
                                    <p class="card-title">{{$vacancies->total()}}
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-briefcase-24 text-success"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Applications</p>
                                    <p class="card-title">{{$applications->total()}}
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-check-2 text-danger"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Processed</p>
                                    <p class="card-title">{{$processed_apl->count()}}
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-time-alarm text-primary"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Open</p>
                                    <p class="card-title">{{$open_apl->count()}}
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Job Applications</h5>
                    </div>
                    <div class="card-body ">
                        <div class="table">
                            @if($applications->count() == 0)
                            No applications found
                            @else
                            <table class="table-striped col-md-12">
                                <thead class=" text-primary">
                                    <th>Applied For</th>
                                    <th>Status</th>
                                    <th>Applicant Name</th>
                                    <th>Published</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($applications as $ap)
                                    <tr>
                                    <td>{{$ap->vacancy->job_title}}</td>
                                    @if($ap->status == 1)
                                    <td>Open</td>
                                    @elseif($ap->status == 2)
                                    <td>Reviewed</td>
                                    @elseif($ap->status == 3)
                                    <td>Called for Interview</td>
                                    @else
                                    <td>Denied</td>
                                    @endif
                                    <td>{{$ap->metas[1]->meta_value}}</td>
                                    <td>{{$ap->created_at->diffForHumans()}}</td>
                                    <td><a href="{{ route('admin-view-application', $ap) }}"><button type="button" class="btn btn-primary">View</button></a></td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $applications->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Job Vacancies</h5>
                    </div>
                    <div class="card-body ">
                        <div class="table">
                            <table class="table-striped col-md-12">
                                <thead class=" text-primary">
                                    <th>Job Title</th>
                                    <th>Job Description</th>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Published</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($vacancies as $vc)
                                    <tr>
                                    <td>{{$vc->job_title}}</td>
                                    <td>{{mb_strimwidth($vc->job_desc, 0, 50, "...")}}</td>
                                    <td>{{$vc->location}}</td>
                                    <td>{{$vc->job_type}}</td>
                                    <td>{{$vc->created_at->diffForHumans()}}</td>
                                    <td><a href="{{ route('admin-view-vacancy', $vc) }}"><button type="button" class="btn btn-primary">View</button></a></td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $vacancies->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Email Statistics</h5>
                        <p class="card-category">Last Campaign Performance</p>
                    </div>
                    <div class="card-body ">
                        <canvas id="chartEmail"></canvas>
                    </div>
                    <div class="card-footer ">
                        <div class="legend">
                            <i class="fa fa-circle text-primary"></i> Opened
                            <i class="fa fa-circle text-warning"></i> Read
                            <i class="fa fa-circle text-danger"></i> Deleted
                            <i class="fa fa-circle text-gray"></i> Unopened
                        </div>
                        <hr>
                        <div class="stats">
                            <i class="fa fa-calendar"></i> Number of emails sent
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-title">NASDAQ: AAPL</h5>
                        <p class="card-category">Line Chart with Points</p>
                    </div>
                    <div class="card-body">
                        <canvas id="speedChart" width="400" height="100"></canvas>
                    </div>
                    <div class="card-footer">
                        <div class="chart-legend">
                            <i class="fa fa-circle text-info"></i> Tesla Model S
                            <i class="fa fa-circle text-warning"></i> BMW 5 Series
                        </div>
                        <hr />
                        <div class="card-stats">
                            <i class="fa fa-check"></i> Data information certified
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
            demo.initChartsPages();
        });
    </script>
@endpush
