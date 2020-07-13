@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'applications'
])

@section('content')
    <div class="content">
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
