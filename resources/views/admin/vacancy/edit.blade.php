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
                    <form method="post" action="{{route('admin-update-vacancy',$vacancy)}}" accept-charset="UTF-8">
                            @csrf
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="job_title">Job Title</label>
                              <input type="text" class="form-control" name="job_title" id="job_title" value="{{$vacancy->job_title}}" >
                              </div>
                              <div class="form-group col-md-6">
                                <label for="job_type">Job Type</label>
                                <select class="form-control" name="job_type" id="job_type" >
                                    <option value = "full-time" {{ ( $vacancy->job_type == "full-time") ? 'selected' : '' }}>Full Time</option>
                                    <option value = "contract" {{ ( $vacancy->job_type == "contract") ? 'selected' : '' }}>Contract</option>
                                    <option value = "internship" {{ ( $vacancy->job_type == "internship") ? 'selected' : '' }}>Internship</option>
                                    <option value = "elite-program" {{ ( $vacancy->job_type == "elite-program") ? 'selected' : '' }}>Elite Program</option>
                                  </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="job_desc">Job Description</label>
                            {{-- <textarea class="form-control" name="job_desc" id="job_desc" rows="3" >{{$vacancy->job_desc}}</textarea> --}}
                            <!-- @trix($vacancy, 'job_desc') -->
                            <input id="job_desc" type="hidden" name="job_desc" value="{{ $vacancy->job_desc ?? "" }}" >
                            <trix-editor input="job_desc" placeholder="Job Description"></trix-editor>
                            </div>
                            <div class="form-group">
                              <label for="job_req">Job Requirement</label>
                              {{-- <textarea class="form-control" name="job_req" id="job_req" rows="50" >{{$vacancy->job_req}}</textarea> --}}
                              <!-- @trix($vacancy, 'job_req') -->
                              <input id="job_req" type="hidden" name="job_req" value="{{ $vacancy->job_req ?? "" }}" >
                            <trix-editor input="job_req" placeholder="Job Requirement"></trix-editor>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="location">Location</label>
                              <input type="text" class="form-control" name="location" id="location" value="{{$vacancy->location}}" >
                              </div>
                            </div>
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="{{ route('admin-view-vacancy',$vacancy) }}"><button type="button" class="btn btn-secindary">Back</button></a>
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
            demo.initChartsPages();
        });
    </script>
@endpush
