@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'vacancies'
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12"><a href=""><button type="button" class="btn btn-success">+ New Vacancy</button></a></div>

            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Job Vacancies</h5>
                    </div>
                    <div class="card-body ">
                        <div class="table">
                            <table class="table-striped">
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
                                    <td><a href="{{ route('admin-view-vacancy', $vc) }}"><button type="button" class="btn btn-sm btn-primary"> <i class="nc-icon nc-zoom-split"></i></button></a>
                                        <a href="{{ route('view-vacancy', $vc) }}"><button type="button" class="btn btn-sm btn-danger"> <i class="nc-icon nc-basket"></i></button></a></td>
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
