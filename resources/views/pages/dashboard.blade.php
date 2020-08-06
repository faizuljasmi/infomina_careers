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
        {{-- JOB APPLICATIONS --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Job Applications</h5>
                        <p>Showing applications submitted from the career site</p>
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
                                    <th>Submitted</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($applications as $ap)
                                    <tr>
                                    <td>{{$ap->vacancy->job_title}}</td>
                                    @if($ap->status == "Submitted")
                                    <td>Open</td>
                                    @elseif($ap->status == "Processed")
                                    <td>Processed</td>
                                    @elseif($ap->status == "Called")
                                    <td>Called for Interview</td>
                                    @elseif($ap->status == "Accepted")
                                    <td>Accepted</td>
                                    @elseif($ap->status == "Denied")
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
        {{-- EFORM --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">e-Forms</h5>
                        <p>Showing e-Forms submitted</p>
                    </div>
                    <div class="card-body ">
                        <div class="table">
                            @if($eforms->count() == 0)
                            No records found
                            @else
                            <table class="table-striped col-md-12">
                                <thead class=" text-primary">
                                    <th>Submitted For</th>
                                    <th>Status</th>
                                    <th>Applicant Name</th>
                                    <th>Generated/Submitted</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($eforms as $ef)
                                    <tr>
                                    <td>{{$ef->vacancy->job_title}}</td>
                                    @if($ef->status == "Submitted")
                                    <td>Open</td>
                                    @elseif($ef->status == "Processed")
                                    <td>Processed</td>
                                    @elseif($ef->status == "Called")
                                    <td>Called for Interview</td>
                                    @elseif($ef->status == "Accepted")
                                    <td>Accepted</td>
                                    @elseif($ef->status == "Denied")
                                    <td>Denied</td>
                                    @elseif($ef->status == "Generated")
                                    <td>Generated</td>
                                    @endif
                                    <td>{{$ef->metas->count() != 0  ? $ef->metas[1]->meta_value : "No Info Yet"}}</td>
                                    <td>{{$ef->created_at->diffForHumans()}}</td>
                                    <td>
                                        @if($ef->status == "Generated")
                                        <button type="button"
                                                class="btn btn-primary" disabled>View</button>
                                        @else
                                        <a href="{{ route('admin-view-application', $ef) }}"><button type="button"
                                                class="btn btn-primary">View</button></a>
                                        @endif
                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $eforms->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- JOB VACANCIES --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header">
                        <h5 class="card-title">Job Vacancies</h5>
                        <p>Showing all vacancies posted on the careers site</p>
                    </div>
                    <div class="card-body">
                        <div class="table">
                            <table class="table-striped col-md-12">
                                <thead class=" text-primary">
                                    <th>Job Title</th>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Published</th>
                                    <th>No. of Applications</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($vacancies as $vc)
                                    <tr>
                                    <td>{{$vc->job_title}}</td>
                                    <td>{{$vc->location}}</td>
                                    <td>{{$vc->job_type}}</td>
                                    <td>{{$vc->created_at->diffForHumans()}}</td>
                                    <td>{{$vc->applications->count()}}</td>
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
