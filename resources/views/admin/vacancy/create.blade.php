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
                        <h5 class="card-title">Create New Vacancy</h5>
                    </div>
                    <div class="card-body ">
                    <form method="post" action="{{route('admin-store-vacancy')}}" accept-charset="UTF-8">
                            @csrf
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="job_title">Job Title</label>
                              <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Job Title" required>
                              </div>
                              <div class="form-group col-md-6">
                                <label for="job_type">Job Type</label>
                                <select class="form-control" id="job_type" name="job_type" required>
                                    <option value = "full-time">Full Time</option>
                                    <option value = "contract">Contract</option>
                                    <option value = "internship">Internship</option>
                                  </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="job_desc">Job Description</label>
                            {{-- <textarea class="form-control" name="job_desc" id="job_desc" rows="3" >{{$vacancy->job_desc}}</textarea> --}}
                            @trix(\App\Vacancy::class, 'job_desc')
                            </div>
                            <div class="form-group">
                              <label for="job_req">Job Requirement</label>
                              @trix(\App\Vacancy::class, 'job_req')
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="location">Location</label>
                              <input type="text" class="form-control" name="location" id="location" placeholder="Insert Location" required>
                              </div>
                            </div>
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="{{ route('admin-view-vacancies') }}"><button type="button" class="btn btn-secindary">Back</button></a>
                          </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        form label{
  font-weight:bold;
  color: black !important;
}
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
            demo.initChartsPages();
        });
    </script>
@endpush
