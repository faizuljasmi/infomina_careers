@extends('layouts.app', [
'class' => '',
'elementActive' => 'vacancies'
])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12"><a href="{{route('admin-create-vacancy')}}"><button type="button"
                    class="btn btn-success">+ New Vacancy</button></a></div>
        @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fa fa-check"></i>
            {{ session()->get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
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
                                    <td><a href="{{ route('admin-view-vacancy', $vc) }}"><button type="button"
                                                class="btn btn-primary btm-sm"><svg width="1em" height="1em"
                                                    viewBox="0 0 16 16" class="bi bi-eye" fill="currentColor"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 0 0 1.66 2.043C4.12 11.332 5.88 12.5 8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.134 13.134 0 0 0 14.828 8a13.133 13.133 0 0 0-1.66-2.043C11.879 4.668 10.119 3.5 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 0 0 1.172 8z" />
                                                    <path fill-rule="evenodd"
                                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                                </svg></button></a>
                                        <a href="{{ route('admin-view-vacancy', $vc) }}"><button type="button"
                                                class="btn btn-danger btm-sm"><svg width="1em" height="1em"
                                                    viewBox="0 0 16 16" class="bi bi-file-earmark-minus"
                                                    fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9 1H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h5v-1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h5v2.5A1.5 1.5 0 0 0 10.5 6H13v2h1V6L9 1z" />
                                                    <path fill-rule="evenodd"
                                                        d="M11 11.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z" />
                                                </svg></button></a></td>
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
