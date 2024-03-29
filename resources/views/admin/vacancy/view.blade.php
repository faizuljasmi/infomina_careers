@extends('layouts.app', [
'class' => '',
'elementActive' => 'vacancies'
])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card ">
                <div class="card-header ">
                <h5 class="card-title col-md-12">{{$vacancy->job_title}} <span class="badge badge-{{$vacancy->is_active == "Yes" ? "success":"danger"}}">{{$vacancy->is_active == "Yes" ? "Active":"Inactive"}}</span></h5>
                    <div class="row">
                    <p class="col-md-6 p-3 mb-2 bg-light rounded">Created by: @if($vac_log_created == null)
                        -
                        @else
                        {{$vac_log_created->user->name}}
                        @endif</p></p>
                    <p class="col-md-6 p-3 mb-2 bg-light rounded">Updated by:
                        @if($vac_log_edited == null)
                        -
                        @else
                        {{$vac_log_edited->user->name}}
                        @endif</p>
                    </div>
                </div>
                <div class="card-body ">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="job_title">Job Title</label>
                                <div class="p-3 mb-2 bg-light rounded">
                                    {{{ $vacancy->job_title}}}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="job_type">Job Type</label>
                                <div class="p-3 mb-2 bg-light rounded">
                                    @if($vacancy->job_type == 'full-time')
                                        Full time
                                    @elseif($vacancy->job_type == 'contract')
                                        Contract
                                    @elseif($vacancy->job_type == 'internship')
                                        Internship
                                    @elseif($vacancy->job_type == 'elite-program')
                                        Elite Program
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="job_desc">Job Description</label>
                            <div class="p-3 mb-2 bg-light rounded">
                                {!! $vacancy->job_desc !!}
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="job_req">Job Requirement</label>
                            <div class="p-3 mb-2 bg-light rounded">
                                {!! $vacancy->job_req !!}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="location">Location</label>
                                <div class="p-3 mb-2 bg-light rounded">
                                    {{{ $vacancy->location}}}
                                </div>
                            </div>
                        </div>
                        <a href="{{route('admin-edit-vacancy', $vacancy)}}"><button type="button"
                                class="btn btn-success">Edit</button></a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card ">
                <div class="card-header ">
                    <h5 class="card-title">Vacancy Applications</h5>
                </div>
                <div class="card-body ">
                    @if($vac_apl->count() == 0)
                    No Applications Found
                    @else
                    <div class="table">
                        <table class="table table-bordered table-sm col-md-12">
                            <thead class="text-primary">
                                <th>No.</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Posted</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @php $count = ($vac_apl->currentPage()-1) * $vac_apl->perPage(); @endphp
                                @foreach($vac_apl as $va)
                                <tr>
                                    <td>{{++$count}}</td>
                                    <td>{{$va->metas->count() != 0  ? $va->metas[1]->meta_value : "Form incomplete"}}</td>
                                    <td>{{$va->status}}</td>
                                    <td>{{$va->created_at->diffForHumans()}}</td>
                                    <td><a href="{{ route('admin-view-application', $va) }}"><button type="button"
                                                class="btn btn-sm btn-primary"><i class="nc-icon nc-zoom-split"></i></button></a></td>
                                </tr>
                                @endforeach
                                {{$vac_apl->links()}}
                            </tbody>
                        </table>
                    </div>
                    @endif
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
            //demo.initChartsPages();
            var trix1 = document.getElementsByTagName("trix-editor")[0];
            trix1.removeAttribute('contenteditable');

            var trix2 = document.getElementsByTagName("trix-editor")[1];
            trix2.removeAttribute('contenteditable');
        });

</script>
@endpush
