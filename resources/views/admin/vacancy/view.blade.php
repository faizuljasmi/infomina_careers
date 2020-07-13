@extends('layouts.app', [
'class' => '',
'elementActive' => 'vacancies'
])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header ">
                    <h5 class="card-title">{{$vacancy->job_title}}</h5>
                </div>
                <div class="card-body ">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="job_title">Job Title</label>
                                <div class="p-3 mb-2 bg-secondary text-white rounded">
                                    {{{ $vacancy->job_title}}}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="job_type">Job Type</label>
                                <div class="p-3 mb-2 bg-secondary text-white rounded">
                                    {{{ $vacancy->job_type}}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="job_desc">Job Description</label>
                            <div class="p-3 mb-2 bg-secondary text-white rounded">
                                {!! $vacancy->job_desc !!}
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="job_req">Job Requirement</label>
                            <div class="p-3 mb-2 bg-secondary text-white rounded">
                                {!! $vacancy->job_req !!}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="location">Location</label>
                                <div class="p-3 mb-2 bg-secondary text-white rounded">
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
