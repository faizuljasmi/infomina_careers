@extends('layouts.app', [
'class' => 'login-page',
'elementActive' => '',
'isPublic' => 'Yes'
])

@section('head')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">

@endsection

@section('content')
<div class="content col-md-12 ml-auto mr-auto">
    <div class="header py-5 pb-7 pt-lg-9">
        <div class="container col-md-12">
            <div class="header-body mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-12 pt-5">
                        <div class="content">
                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header border-bottom border-primary">
                                            <h4 class="card-title">Applicant Details Form</h4>
                                        </div>
                                        @if(session()->has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="icon fa fa-exclamation-triangle"></i>
                                            {!! session('error') !!}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @endif
                                        <div class="card-body">
                                            <form class="needs-validation" novalidate method="POST"
                                                action="{{route('e-form-submit')}}"
                                                enctype="multipart/form-data" id="submit-application">
                                                @csrf
                                        <input type="text" name="apl_no" hidden value="{{$app->apl_no}}">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label class="float-left" for="applied_for">Position Applied
                                                            for</label>
                                                        <select class="form-control" id="applied_for" name="applied_for"
                                                            readonly>
                                                            <option value="{{$app->vacancy->job_title}}" selected>{{$app->vacancy->job_title}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="p-3 mb-2 border rounded border-dark bg-light">
                                                    <h6 class="mt-2">Applicant's Particulars</h6>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label class="float-left" for="applicant_name">Name</label>
                                                            <input type="text" class="form-control" id="applicant_name"
                                                                name="applicant_name" placeholder="Full Name" required>
                                                            <div class="invalid-feedback">
                                                                Fill in your name, or else we'll call you Bot#217 :)
                                                            </div>
                                                            <div class="valid-feedback">
                                                                That's a nice name!
                                                            </div>
                                                        </div>
                                                          <div class="form-group col-md-2">
                                                            <label class="float-left" for="applicant_nationality">Nationality</label>
                                                            <select class="form-control" id="applicant_nationality" name="applicant_nationality" required>
                                                                <option value="">Choose one</option>
                                                                 @foreach($countries as $country)
                                                                 <option value="{{ $country->name }}" {{ old('applicant_nationality') === $country->name ? 'selected' : '' }} >{{ $country->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Please choose one
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Nice...
                                                            </div>
                                                        </div>
                                                         <div class="form-group col-md-2">
                                                         <label class="float-left" for="applicant_ic">National ID No. <a tabindex="0" class="fa fa-question-circle" role="button" data-toggle="popover" title="National ID Guide" data-content="NRIC (MY): 950101XXXXXX <br>                 
                                                                                                                                                                                                                        NIK (IND): 320000564XXXXXXX <br>         
                                                                                                                                                                                                                        National ID No. (TH): 5 0499 40598 45 0 <br>
                                                                                                                                                                                                                        National ID No. (PH): 1234-5678-9101-1213
                                                                                                                                                                                                                         " data-html="true" data-trigger="focus"></a></label>
                                                            <input type="text" oninput="this.value = this.value.replace(/[^0-9a-zA-Z-\s]/g, '')" class="form-control" id="applicant_ic" name="applicant_ic" placeholder="ID No." required>

                                                            <div class="invalid-feedback">
                                                                You forgot your ID number
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good...
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="float-left"
                                                                for="applicant_gender">Gender</label>
                                                            <select class="form-control" id="applicant_gender"
                                                                name="applicant_gender" onchange="checkGender()"
                                                                required>
                                                                <option value="" {{ old('applicant_gender') === "" ? 'selected' : '' }}>Choose One</option>
                                                                <option value="Male" {{ old('applicant_gender') === "Male" ? 'selected' : '' }}>Male</option>
                                                                <option value="Female" {{ old('applicant_gender') === "Female" ? 'selected' : '' }}>Female</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Please select a gender
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Gotcha!
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label class="float-left"
                                                                for="applicant_email">Email</label>
                                                            <input type="email" class="form-control"
                                                                id="applicant_email" name="applicant_email"
                                                                placeholder="Email" required>
                                                            <div class="invalid-feedback">
                                                                It's important that we get your email
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Good, we will contact you thru it. So make sure it's
                                                                right!
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="float-left" for="applicant_tel_mobile">Tel No.
                                                                (Mobile)</label>
                                                            <input type="text" maxlength="20"
                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                                class="form-control" id="applicant_tel_mobile"
                                                                name="applicant_tel_mobile" required>
                                                            <div class="invalid-feedback">
                                                                Please fill in your phone number
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="float-left" for="applicant_tel_office">Tel No.
                                                                (Office)</label>
                                                            <input type="text" maxlength="20"
                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                                class="form-control" id="applicant_tel_office"
                                                                name="applicant_tel_office">
                                                            <div class="valid-feedback">
                                                                Two phones...fancy
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-2">
                                                            <label class="float-left" for="applicant_dob">Date of Birth</label>
                                                                <input type="date" class="form-control" id="applicant_dob" name="applicant_dob" required>
                                                        </div>

                                                         <div class="form-group col-md-2">
                                                            <label class="float-left"
                                                                for="applicant_marital_stat">Marital
                                                                Status</label>
                                                            <select class="form-control" id="applicant_marital_stat"
                                                                name="applicant_marital_stat" onchange="checkGender()"
                                                                required>
                                                                <option value="" {{ old('applicant_marital_stat') === "" ? 'selected' : '' }}>Choose one</option>
                                                                <option value="Single" {{ old('applicant_marital_stat') === "Single" ? 'selected' : '' }}>Single</option>
                                                                <option value="Married" {{ old('applicant_marital_stat') === "Married" ? 'selected' : '' }}>Married</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Please choose one
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Nice...
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label class="float-left" for="applicant_address">Address</label>
                                                            <input type="text" class="form-control" id="applicant_address" name="applicant_address" required></input>
                                                            <div class="invalid-feedback">
                                                                Please fill in your address
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label class="float-left" for="applicant_address">City</label>
                                                            <input type="text" class="form-control" id="applicant_city" name="applicant_city" required></input>
                                                            <div class="invalid-feedback">
                                                                Please fill in your city
                                                            </div>
                                                            <div class="valid-feedback">
                                                                That's a nice city!
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label class="float-left" for="applicant_state">State</label>
                                                            <input type="text" class="form-control" id="applicant_state" name="applicant_state" required></input>
                                                            <div class="invalid-feedback">
                                                                Please fill in your state of residential
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Good!
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label class="float-left" for="applicant_postcode">Postcode</label>
                                                            <input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" id="applicant_postcode" name="applicant_postcode" required></input>
                                                            <div class="invalid-feedback">
                                                                Please fill in your postcode
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Just in case!
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                                                    <h6 class="mt-2 text-center">Health Conditions</h6>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <p class="text-center">Have you suffered from or are you
                                                                currently suffering from any serious illness? ( If yes,
                                                                please state exact details )</p>
                                                            <textarea class="form-control"
                                                                id="applicant_serious_health_cond"
                                                                name="applicant_serious_health_cond" rows="3"
                                                                placeholder="State your health condition (if any) and details here"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12" id="is_pregnant">
                                                            <p class="float-left mr-2">Are you pregnant now?</p>
                                                            <select class="form-control" id="applicant_pregnant"
                                                                name="applicant_pregnant" required>
                                                                <option {{ old('applicant_pregnant') === "Yes" ? 'selected' : '' }}>Yes</option>
                                                                <option {{ old('applicant_pregnant') === "" || old('applicant_pregnant') === "No" ? 'selected' : '' }}>No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                                                    <h6 class="mt-2 text-center">Referees</h6>
                                                    <div class="row">
                                                        <div class="form-group col-md-12 text-center">
                                                            <p>
                                                                Please provide 2 referees for our reference ( Referees
                                                                must
                                                                not be your next-of-kin )
                                                            </p>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <h6>
                                                                Referee 1
                                                            </h6>
                                                            <label class="float-left mt-2"
                                                                for="applicant_referee_1_name">Name</label>
                                                            <input type="text" class="form-control"
                                                                id="applicant_referee_1_name"
                                                                name="applicant_referee_1_name" placeholder="Full Name"
                                                                required>
                                                            <label class="float-left mt-2"
                                                                for="applicant_referee_1_mobile">Tel
                                                                No.
                                                            </label>
                                                            <input type="text" class="form-control" maxlength="12"
                                                                id="applicant_referee_1_mobile"
                                                                name="applicant_referee_1_mobile"
                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                                placeholder="" required>
                                                            <label class="float-left mt-2"
                                                                for="applicant_referee_1_occupation">Occupation
                                                            </label>
                                                            <input type="text" class="form-control"
                                                                id="applicant_referee_1_occupation"
                                                                name="applicant_referee_1_occupation"
                                                                placeholder="Occupation" required>
                                                            <label class="float-left mt-2"
                                                                for="applicant_referee_1_known">No. of
                                                                Years Known
                                                            </label>
                                                            <input type="text" class="form-control"
                                                                id="applicant_referee_1_known"
                                                                name="applicant_referee_1_known"
                                                                placeholder="X Years/Months" required>
                                                            <div class="invalid-feedback">
                                                                Please complete all details
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <h6>
                                                                Referee 2
                                                            </h6>
                                                            <label class="float-left mt-2"
                                                                for="applicant_referee_2_name">Name</label>
                                                            <input type="text" class="form-control"
                                                                id="applicant_referee_2_name"
                                                                name="applicant_referee_2_name" placeholder="Full Name"
                                                                required>
                                                            <label class="float-left mt-2"
                                                                for="applicant_referee_2_mobile">Tel
                                                                No.
                                                            </label>
                                                            <input type="text" class="form-control" maxlength="12"
                                                                id="applicant_referee_2_mobile"
                                                                name="applicant_referee_2_mobile"
                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                                placeholder="" required>
                                                            <label class="float-left mt-2"
                                                                for="applicant_referee_2_occupation">Occupation
                                                            </label>
                                                            <input type="text" class="form-control"
                                                                id="applicant_referee_2_occupation"
                                                                name="applicant_referee_2_occupation"
                                                                placeholder="Occupation" required>
                                                            <label class="float-left mt-2"
                                                                for="applicant_referee_2_known">No. of
                                                                Years Known
                                                            </label>
                                                            <input type="text" class="form-control"
                                                                id="applicant_referee_2_known"
                                                                name="applicant_referee_2_known"
                                                                placeholder="X Years/Months" required>
                                                            <div class="invalid-feedback">
                                                                Please complete all the details
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label class="float-left"
                                                                for="applicant_willing_travel">Willing
                                                                to Travel</label>
                                                            <select class="form-control" id="applicant_willing_travel"
                                                                name="applicant_willing_travel" required>
                                                                <option value="" {{ old('applicant_willing_travel') === "" ? 'selected' : '' }}>Choose one</option>
                                                                <option value="No" {{ old('applicant_willing_travel') === "No" ? 'selected' : '' }}>No</option>
                                                                <option value="Light" {{ old('applicant_willing_travel') === "Light" ? 'selected' : '' }}>Light</option>
                                                                <option value="Moderate" {{ old('applicant_willing_travel') === "Moderate" ? 'selected' : '' }}>Moderate</option>
                                                                <option value="Heavy" {{ old('applicant_willing_travel') === "Heavy" ? 'selected' : '' }}>Heavy</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Please choose one
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label class="float-left"
                                                                for="applicant_notice_period">Notice
                                                                Period</label>
                                                            <input type="number" class="form-control"
                                                                id="applicant_notice_period"
                                                                name="applicant_notice_period" placeholder="3" required>
                                                            <div class="invalid-feedback">
                                                                Please fill in this field
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label class="float-left"
                                                                for="applicant_notice_year_week">Week/Month</label>
                                                            <select class="form-control" id="applicant_notice_year_week"
                                                                name="applicant_notice_year_week" required>
                                                                <option value="" {{ old('applicant_notice_year_week') === "" ? 'selected' : '' }}>Choose one</option>
                                                                <option {{ old('applicant_notice_year_week') === "Week(s)" ? 'selected' : '' }}>Week(s)</option>
                                                                <option {{ old('applicant_notice_year_week') === "Month(s)" ? 'selected' : '' }}>Month(s)</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Please choose one
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-2">
                                                            <label class="float-left" for="applicant_cur_salary">Current
                                                                Salary</label>
                                                            <select class="form-control"  id="applicant_cur_salary_currency"
                                                            name="applicant_cur_salary_currency" required>
                                                            <option value="" {{ old('applicant_cur_salary_currency') === "Heavy" ? 'selected' : '' }}>Currency</option>
                                                                <option {{ old('applicant_cur_salary_currency') === "MYR" ? 'selected' : '' }}>MYR</option>
                                                                <option {{ old('applicant_cur_salary_currency') === "USD" ? 'selected' : '' }}>USD</option>
                                                                <option {{ old('applicant_cur_salary_currency') === "SGD" ? 'selected' : '' }}>SGD</option>
                                                                <option {{ old('applicant_cur_salary_currency') === "PHP" ? 'selected' : '' }}>PHP</option>
                                                                <option {{ old('applicant_cur_salary_currency') === "IDR" ? 'selected' : '' }}>IDR</option>
                                                                <option {{ old('applicant_cur_salary_currency') === "THB" ? 'selected' : '' }}>THB</option>
                                                                <option {{ old('applicant_cur_salary_currency') === "VND" ? 'selected' : '' }}>VND</option>
                                                                <option {{ old('applicant_cur_salary_currency') === "HKD" ? 'selected' : '' }}>HKD</option>
                                                                <option {{ old('applicant_cur_salary_currency') === "TWD" ? 'selected' : '' }}>TWD</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Please choose one
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label class="float-left" for="applicant_cur_salary">:</label>
                                                            <input type="number" class="form-control"
                                                                id="applicant_cur_salary" name="applicant_cur_salary"
                                                                placeholder="" required>
                                                            <div class="invalid-feedback">
                                                                Please state your current salary
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="float-left" for="applicant_exp_salary">Expected
                                                                Salary</label>
                                                            <select class="form-control" id="applicant_exp_salary_currency"
                                                            name="applicant_exp_salary_currency" required>
                                                            <option value="" {{ old('applicant_exp_salary_currency') === "Heavy" ? 'selected' : '' }}>Currency</option>
                                                                <option {{ old('applicant_exp_salary_currency') === "MYR" ? 'selected' : '' }}>MYR</option>
                                                                <option {{ old('applicant_exp_salary_currency') === "USD" ? 'selected' : '' }}>USD</option>
                                                                <option {{ old('applicant_exp_salary_currency') === "SGD" ? 'selected' : '' }}>SGD</option>
                                                                <option {{ old('applicant_exp_salary_currency') === "PHP" ? 'selected' : '' }}>PHP</option>
                                                                <option {{ old('applicant_exp_salary_currency') === "IDR" ? 'selected' : '' }}>IDR</option>
                                                                <option {{ old('applicant_exp_salary_currency') === "THB" ? 'selected' : '' }}>THB</option>
                                                                <option {{ old('applicant_exp_salary_currency') === "VND" ? 'selected' : '' }}>VND</option>
                                                                <option {{ old('applicant_exp_salary_currency') === "HKD" ? 'selected' : '' }}>HKD</option>
                                                                <option {{ old('applicant_exp_salary_currency') === "TWD" ? 'selected' : '' }}>TWD</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Please choose one
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label class="float-left" for="applicant_exp_salary">:</label>
                                                            <input type="number" class="form-control"
                                                                id="applicant_exp_salary" name="applicant_exp_salary"
                                                                placeholder="" required>
                                                            <div class="invalid-feedback">
                                                                Please state your expected salary
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                        @if ($app->vacancy->job_type == 'elite-program')
                                                            <div class="control-group" id="attachments">
                                                                <label class="control-label" for="attachment1">
                                                                Upload Attachment(s) (supported format: pdg, doc, docx, jpeg, jpg, png)
                                                                </label>
                                                                <div class="controls col-md-18">
                                                                    <div class="entry input-group upload-input-group">
                                                                        <input class="form-control" name="resume_applicant[]" type="file" class="form-control" data-show-upload="true" data-show-caption="true" required>
                                                                        <button class="btn btn-upload btn-success btn-add" type="button" data-toggle="tooltip" data-placement="top" title="Add another attachment">
                                                                            <i class="fa fa-plus"></i>

                                                                        </button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <!-- <label class="float-left" for="resume_applicant">Upload
                                                                Attachment(s) (supported format: pdg, doc, docx, jpeg, jpg, png)<br> Make sure to select all related documents, and upload it one go</label>
                                                                <input type="file" name="resume_applicant[]" class="form-control" data-show-upload="true" data-show-caption="true" required multiple>
                                                                
                                                            <div class="invalid-feedback">
                                                                Please upload your attachment(s)
                                                            </div> -->
                                                            @else
                                                            <label class="float-left" for="resume_applicant">Upload Resume (supported format: pdf, doc, docx)</label>
                                                                <input type="file" name="resume_applicant[]" class="form-control" data-show-upload="true" data-show-caption="true" required>
                                                            <div class="invalid-feedback">
                                                                Please upload your resume
                                                            </div>
                                                            @endif
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="float-left"
                                                                for="applicant_exp_salary">LinkedIn
                                                                (Optional)</label>
                                                            <div class="input-group mb-2 mr-sm-2">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text"><i
                                                                            class="fa fa-linkedin-square"></i></div>
                                                                </div>
                                                                <input type="text" class="form-control"
                                                                    id="applicant_linkedin" name="applicant_linkedin"
                                                                    placeholder="https://www.linkedin.com/in/[username]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                                                    <h6 class="mt-2 text-center">
                                                        Additional Information
                                                    </h6>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <p class="text-center">Please state additional information
                                                                which
                                                                may be important in support of your application. Include
                                                                any
                                                                special talents, personal qualities or achievements not
                                                                otherwise state in your resume.</p>
                                                            {{-- <textarea class="form-control" id="applicant_add_info" name="applicant_add_info"
                                                                rows="3" placeholder="Write here..."></textarea> --}}
                                                        </div>
                                                        <div class="col-md-12">
                                                            <textarea class="form-control"
                                                            id="applicant_add_info"
                                                            name="applicant_add_info" rows="3"
                                                            placeholder="Additional infos that are not stated in your resume/CV"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-3 mb-2 border rounded border-info mt-4 bg-light">
                                                    <h6 class="mt-2 text-center">
                                                        PDPA Consent
                                                    </h6>
                                                    <div class="row text-center">
                                                        <div class="form-group col-md-12">
                                                            <p class="float-left">By submitting your information to Infomina, you hereby agree that Infomina may collect, 
                                                                obtain, store and process your personal data that you provide in this form for the purpose of receiving updates, 
                                                                news, promotional and marketing mails or materials from Infomina .
                                                            </p>
                                                            <div class="scroll-box">
                                                                <div class="text-left">
                                                                    <p class="font-weight-bold">You hereby give your consent to Infomina to:-</p>
                                                                    <ul class="list-unstyled">
                                                                        <li>- Store and process your Personal Data, such as your full name, passport or identity card number, nationality and religion.</li>
                                                                        <li>- Disclose your Personal Data to the relevant governmental authorities or third parties where required by law or for legal purposes.</li>
                                                                        <li>- Resume or CVs when you apply job with us.</li>
                                                                    </ul>
                                                                    <p>In addition, your personal data may be transferred to any company within Infomina Group which may involve sending your data to a location outside Malaysia. 
                                                                        For the purpose of updating or correcting such data, you may at any time apply to the Infomina to have access to your personal data which are stored by Infomina.</p>
                                                                    <p>For the avoidance of doubt, Personal Data includes all data defined within the Personal Data Protection Act 2010 including all data you had disclosed to Infomina in this Form.</p>
                                                                </div>
                                                            </div>
                                                            <div class="mt-3">
                                                                <input class="form-check-input text-center" type="checkbox" value="Yes" id="applicant_conf_pdpa" name="applicant_conf_pdpa" required>
                                                                <label class="form-check-label" for="applicant_conf_pdpa">
                                                                    Accept
                                                                </label>
                                                                <div class="invalid-feedback">
                                                                    Please read this area and check the box
                                                                </div>
                                                                <div class="valid-feedback">
                                                                    Looks good!
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-3 mb-2 border rounded border-danger mt-4 bg-light">
                                                    <div class="row text-center">
                                                        <div class="form-group col-md-12">
                                                            <p class="float-left mt-3 text-center">I HEREBY DECLARE THAT
                                                                INFORMATION
                                                                GIVEN ABOVE AND THOSE IN MY RESUME ARE COMPLETED AND
                                                                TRUE. I
                                                                UNDERSTAND THAT ANY MISREPRESENTATION OF FACTS GIVEN
                                                                HEREIN
                                                                WILL BE SUFFICIENT CAUSE FOR DISMISSAL FROM THE
                                                                COMPANY'S
                                                                SERVICES IF I HAVE BEEN EMPLOYED.</p>
                                                            <input class="form-check-input text-center" type="checkbox"
                                                                value="Yes" id="applicant_conf_info"
                                                                name="applicant_conf_info" required>
                                                            <label class="form-check-label" for="applicant_conf_info">
                                                                Accept
                                                            </label>
                                                            <div class="invalid-feedback">
                                                                Please read this area and check the box
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-success float-center">Submit
                                                        Application</button>
                                                </div>
                                            </form>
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

<div id="loading">
    <div id="loading-image">
        <figure>
            <img src="{{asset('paper')}}\img\loading.gif" alt="Loading..." width="25%" height="25%"/>
            <figcaption>Sending in your form...</figcaption>
          </figure>
    </div>
</div>
<style>
    form,
    label,
    p {
        color: black !important;

    }

    .form-control:focus {
        border-color: rgba(49, 162, 236, 0.548);
        border-width: 1.5px !important;
    }

    .form-control {
        border-color: rgba(88, 88, 88, 0.459);
        border-width: 1px !important;
    }

    #loading {
        display: none;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        position: fixed;
        opacity: 0.7;
        background-color: #fff;
        z-index: 99;
        text-align: center;
    }

    #loading-image {
        position: fixed;
        top: 50%;
        left: 50%;
        /* bring your own prefixes */
        transform: translate(-50%, -50%);
        z-index: 100;
    }

    .scroll-box {
  overflow-y: scroll;
  width: 100%;
  height: 150px;
  margin: 0 auto;
  background-color: white;
}

/* // File Uploader Starts here */
.card {
  margin-top: 100px;
}
.btn-upload {
    padding: 10px 20px;
    margin-left: 10px;
}
.upload-input-group {
    margin-bottom: 10px;
}

.input-group>.custom-select:not(:last-child), .input-group>.form-control:not(:last-child) {
  height: 45px;
}
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
            demo.checkFullPageBackgroundImage();
            $('[data-toggle="popover"]').popover();
            $('.popover-dismiss').popover({
                trigger: 'focus'
            });
        });

        $(function () {
            $(document).on('click', '.btn-add', function (e) {
                e.preventDefault();

                var controlForm = $('.controls:first'),
                    currentEntry = $(this).parents('.entry:first'),
                    newEntry = $(currentEntry.clone()).appendTo(controlForm);

                newEntry.find('input').val('');
                controlForm.find('.entry:not(:last) .btn-add')
                    .removeClass('btn-add').addClass('btn-remove')
                    .removeClass('btn-success').addClass('btn-danger')
                    .html('<span class="fa fa-trash"></span>');
            }).on('click', '.btn-remove', function (e) {
                $(this).parents('.entry:first').remove();

                e.preventDefault();
                return false;
            });
        });

function checkGender(){
    var x = document.getElementById("applicant_gender").value;
    var y = document.getElementById("applicant_marital_stat").value;

    console.log(y);
    if(x == "Female" && y == "Married"){
        document.getElementById("is_pregnant").style.visibility = "visible"
    }
    else{
        document.getElementById("is_pregnant").style.visibility = "hidden";
    }

}


// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    var conf_info = document.getElementById("applicant_conf_info").value;
    var spinner = $('#loading');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false || conf_info != "Yes") {
          event.preventDefault();
          event.stopPropagation();
        }
        else{
            spinner.show();
        }

        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
@endpush
