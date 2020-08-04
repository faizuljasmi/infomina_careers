@extends('layouts.app', [
'class' => '',
'elementActive' => 'e-form'
])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12"><a href="{{route('e-form-create')}}"><button type="button" class="btn btn-success">+ New
                    e-Form</button></a></div>
        <div class="col-md-12">
            @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="icon fa fa-check"></i>
                {{ session()->get('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div class="card ">
                <div class="card-header ">
                    <h5 class="card-title">e-Forms</h5>
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
                                <th>Published</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach($eforms as $ef)
                                <tr>
                                    <td>{{$ef->vacancy->job_title}}</td>
                                    @if($ef->status == "Submitted")
                                    <td>Open</td>
                                    @elseif($ef->status == "Processed")
                                    <td>Reviewed</td>
                                    @elseif($ef->status == "Called")
                                    <td>Called for Interview</td>
                                    @elseif($ef->status == "Accepted")
                                    <td>Accepted</td>
                                    @elseif($ef->status == "Denied")
                                    <td>Denied</td>
                                    @elseif($ef->status == "Generated")
                                    <td>Generated</td>
                                    @endif
                                    <td>{{$ef->metas->count() != 0  ? $ef->metas[1]->meta_value : "Form incomplete"}}
                                    </td>
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
