@extends('layouts.app', [
    'class' => 'login-page',
    'elementActive' => '',
    'isPublic' => 'Yes'
])

@section('content')
    <div class="content col-md-12 ml-auto mr-auto">
        <div class="header py-5 pb-7 pt-lg-9">
            <div class="container col-md-10">
                <div class="header-body  mb-7">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-12 pt-5">
                            <div class ="content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Vacancy Info</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="table">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <th>Job Title</th>
                                                            <td><strong>{{$vacancy->job_title}}</strong></td>
                                                              </tr>
                                                              <tr>
                                                                <th>Job Description</th>
                                                              <td> 
                                                                <div class="trix-content">
                                                                {!! $vacancy->job_desc !!}
                                                                </div>
                                                              </td>
                                                              </tr>
                                                              <tr>
                                                                <th>Requirements</th>
                                                              <td> <div class="trix-content">
                                                                {!! $vacancy->job_req !!}
                                                                </div></td>
                                                              </tr>
                                                              <tr>
                                                                <th>Location</th>
                                                              <td>{{$vacancy->location}}</td>
                                                              </tr>
                                                              <tr>
                                                              <th>Job Type</th>
                                                                <td>@if($vacancy->job_type == 'full-time')
                                                                            Full time
                                                                        @elseif($vacancy->job_type == 'contract')
                                                                            Contract
                                                                        @elseif($vacancy->job_type == 'internship')
                                                                            Internship
                                                                        @elseif($vacancy->job_type == 'elite-program')
                                                                            Elite Program
                                                                        @endif
                                                                </td>
                                                              </tr>
                                                        </tbody>
                                                    </table>
                                                <a href="{{route('create-application', $vacancy)}}"><button type="button" class="btn btn-success">Apply Now</button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            demo.checkFullPageBackgroundImage();
        });
    </script>
@endpush
