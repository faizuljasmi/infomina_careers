@extends('layouts.app', [
'class' => '',
'elementActive' => 'e-form'
])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header ">
                    <h5 class="card-title">Create New e-Form</h5>
                </div>
                <div class="card-body ">
                    <form class="needs-validation" novalidate method="POST" action="{{route('e-form-generate')}}"
                        enctype="multipart/form-data" id="submit-application">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="float-left" for="applied_for">Position Applied
                                    for</label>
                                <select class="form-control" id="applied_for" name="applied_for" required>
                                    <option value="">Select one</option>
                                    @foreach($vacancies as $vc)
                                    <option value="{{$vc->id}}">{{$vc->job_title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="float-left" for="applicant_email">Applicant's Email</label>
                                <input class="form-control" type="email" name="applicant_email" required>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success float-center">Generate Form</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    form label {
        font-weight: bold;
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

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');

    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
@endpush
