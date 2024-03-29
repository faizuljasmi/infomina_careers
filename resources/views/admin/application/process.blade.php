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
                    <h5 class="card-title">Application from {{$apl->metas[1]->meta_value}}</h5>
                </div>
                <div class="card-body ">
                    <form class="" method="POST" action="{{route('admin-process-application', $apl)}}" enctype="multipart/form-data" id="submit-process">
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="float-left" for="applied_for">Position Applied
                                        for</label>
                                    <input type="text" class="form-control" id="applied_for" name="applied_for" value="{{$apl->metas[0]->meta_value}}" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <p class="float-left mr-2" for="status">Status: </p>
                                    <select name="status" id="status">
                                        <option value="Submitted" {{ ($apl->status == "Submitted" ? "selected":"") }}>Submitted</option>
                                        <option value="Processed" {{ ($apl->status == "Processed" ? "selected":"") }}>Processed</option>
                                        <option value="Called" {{ ($apl->status == "Called" ? "selected":"") }}>Call for Interview</option>
                                        <option value="Accepted" {{ ($apl->status == "Accepted" ? "selected":"") }}>Accepted</option>
                                        <option value="Denied" {{ ($apl->status == "Denied" ? "selected":"") }}>Denied</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <p class="float-left mr-2" for="status">Favourite: </p>
                                    <select name="is_starred" id="is_starred">
                                        <option value="Yes" {{ ($apl->is_starred == "Yes" ? "selected":"") }}>Yes</option>
                                        <option value="No" {{ ($apl->is_starred == "No" ? "selected":"") }}>No</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <a href="{{route('admin-submit-process-application',$apl)}}"><button type="submit" class="btn btn-primary">Save</button></a>
                                </div>
                            </div>
                            @if($apl->created_at < Carbon\Carbon::parse('01-5-2021')) <div class="card-body ">
                                <form>
                                    <div class="card-body">
                                        <div class="p-3 mb-2 border rounded border-dark bg-light">
                                            <h6 class="mt-2">Applicant's Particulars</h6>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label class="float-left" for="applicant_name">Name</label>
                                                    <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="{{$apl->metas[1]->meta_value}}" readonly>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label class="float-left" for="applicant_ic">NRIC No
                                                    </label>
                                                    <input type="text" oninput="this.value = this.value.replace(/[^0-9a-zA-Z-\s]/g, '')" class="form-control" id="applicant_ic" name="applicant_ic" value="{{$apl->metas[2]->meta_value}}" readonly>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label class="float-left" for="applicant_gender">Gender</label>
                                                    <select class="form-control" id="applicant_gender" name="applicant_gender" readonly>
                                                        <option value="">Choose One</option>
                                                        <option value="Male" {{ ($apl->metas[3]->meta_value == "Male" ? "selected":"") }}>
                                                            Male</option>
                                                        <option value="Female" {{ ($apl->metas[3]->meta_value == "Female" ? "selected":"") }}>
                                                            Female</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label class="float-left" for="applicant_marital_stat">Marital
                                                        Status</label>
                                                    <select class="form-control" id="applicant_marital_stat" name="applicant_marital_stat" readonly>
                                                        <option value=" ">Choose one</option>
                                                        <option value="Single" {{ ($apl->metas[4]->meta_value == "Single" ? "selected":"") }}>
                                                            Single</option>
                                                        <option value="Married" {{ ($apl->metas[4]->meta_value == "Married" ? "selected":"") }}>
                                                            Married</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label class="float-left" for="applicant_email">Email</label>
                                                    <input type="email" class="form-control" id="applicant_email" name="applicant_email" value="{{$apl->metas[5]->meta_value}}" readonly>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="float-left" for="applicant_tel_mobile">Tel No.
                                                        (Mobile)</label>
                                                    <input type="text" maxlength="12" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" id="applicant_tel_mobile" name="applicant_tel_mobile" value="{{$apl->metas[6]->meta_value}}" readonly>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="float-left" for="applicant_tel_office">Tel No.
                                                        (Office)</label>
                                                    <input type="text" maxlength="12" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" id="applicant_tel_office" name="applicant_tel_office" value="{{$apl->metas[7]->meta_value}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                                            <h6 class="mt-2">Health Conditions</h6>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <p class="float-left">Have you suffered from or are you
                                                        currently suffering from any serious illness? ( If yes,
                                                        please state exact details )</p>
                                                    <textarea class="form-control" id="applicant_serious_health_cond" name="applicant_serious_health_cond" rows="3" placeholder="{{$apl->metas[8]->meta_value}}" readonly></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12" id="is_pregnant">
                                                    <p class="float-left mr-2">Are you pregnant now?</p>
                                                    <select class="form-control" id="applicant_pregnant" name="applicant_pregnant" readonly>
                                                        <option {{ ($apl->metas[9]->meta_value == "Yes" ? "selected":"") }}>
                                                            Yes</option>
                                                        <option {{ ($apl->metas[9]->meta_value == "No" ? "selected":"") }}>
                                                            No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                                            <h6 class="mt-2">Referees</h6>
                                            <div class="row">
                                                <div class="form-group col-md-12">
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
                                                    <label class="float-left mt-2" for="applicant_referee_1_name">Name</label>
                                                    <input type="text" class="form-control" id="applicant_referee_1_name" name="applicant_referee_1_name" value="{{$apl->metas[10]->meta_value}}" readonly>
                                                    <label class="float-left mt-2" for="applicant_referee_1_mobile">Tel
                                                        No.
                                                    </label>
                                                    <input type="text" class="form-control" maxlength="12" id="applicant_referee_1_mobile" name="applicant_referee_1_mobile" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{$apl->metas[11]->meta_value}}" readonly>
                                                    <label class="float-left mt-2" for="applicant_referee_1_occupation">Occupation
                                                    </label>
                                                    <input type="text" class="form-control" id="applicant_referee_1_occupation" name="applicant_referee_1_occupation" value="{{$apl->metas[12]->meta_value}}" readonly>
                                                    <label class="float-left mt-2" for="applicant_referee_1_known">No. of
                                                        Years Known
                                                    </label>
                                                    <input type="text" class="form-control" id="applicant_referee_1_known" name="applicant_referee_1_known" value="{{$apl->metas[13]->meta_value}}" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <h6>
                                                        Referee 2
                                                    </h6>
                                                    <label class="float-left mt-2" for="applicant_referee_2_name">Name</label>
                                                    <input type="text" class="form-control" id="applicant_referee_2_name" name="applicant_referee_2_name" value="{{$apl->metas[14]->meta_value}}" readonly>
                                                    <label class="float-left mt-2" for="applicant_referee_2_mobile">Tel
                                                        No.
                                                    </label>
                                                    <input type="text" class="form-control" maxlength="12" id="applicant_referee_2_mobile" name="applicant_referee_2_mobile" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{$apl->metas[15]->meta_value}}" readonly>
                                                    <label class="float-left mt-2" for="applicant_referee_2_occupation">Occupation
                                                    </label>
                                                    <input type="text" class="form-control" id="applicant_referee_2_occupation" name="applicant_referee_2_occupation" value="{{$apl->metas[16]->meta_value}}" readonly>
                                                    <label class="float-left mt-2" for="applicant_referee_2_known">No. of
                                                        Years Known
                                                    </label>
                                                    <input type="text" class="form-control" id="applicant_referee_2_known" name="applicant_referee_2_known" value="{{$apl->metas[17]->meta_value}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="float-left" for="applicant_willing_travel">Willing
                                                        to Travel</label>
                                                    <select class="form-control" id="applicant_willing_travel" name="applicant_willing_travel" readonly>
                                                        <option value="">Choose one</option>
                                                        <option value="No" {{ ($apl->metas[18]->meta_value == "No" ? "selected":"") }}>
                                                            No</option>
                                                        <option value="Light" {{ ($apl->metas[18]->meta_value == "Light" ? "selected":"") }}>
                                                            Light</option>
                                                        <option value="Moderate" {{ ($apl->metas[18]->meta_value == "Moderate" ? "selected":"") }}>
                                                            Moderate</option>
                                                        <option value="Heavy" {{ ($apl->metas[18]->meta_value == "Heavy" ? "selected":"") }}>
                                                            Heavy</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label class="float-left" for="applicant_notice_period">Notice
                                                        Period</label>
                                                    <input type="number" class="form-control" id="applicant_notice_period" name="applicant_notice_period" value="{{$apl->metas[19]->meta_value}}" readonly>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label class="float-left" for="applicant_notice_year_week">Week/Month</label>
                                                    <select class="form-control" id="applicant_notice_year_week" name="applicant_notice_year_week" readonly>
                                                        <option value="">Choose one</option>
                                                        <option {{ ($apl->metas[20]->meta_value == "Week(s)" ? "selected":"") }}>
                                                            Week(s)</option>
                                                        <option {{ ($apl->metas[20]->meta_value == "Month(s)" ? "selected":"") }}>
                                                            Month(s)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @if($apl->created_at < Carbon\Carbon::parse('22-9-2020')) <div class="form-group col-md-6">
                                                    <label class="float-left" for="applicant_cur_salary">Current
                                                        Salary</label>
                                                    <div class="input-group mb-2 mr-sm-2">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">RM</div>
                                                        </div>
                                                        <input type="number" class="form-control" id="applicant_cur_salary" name="applicant_cur_salary" value="{{$apl->metas[21]->meta_value}}" readonly>
                                                    </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="float-left" for="applicant_exp_salary">Expected
                                                    Salary</label>
                                                <div class="input-group mb-2 mr-sm-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">RM</div>
                                                    </div>
                                                    <input type="number" class="form-control" id="applicant_exp_salary" name="applicant_exp_salary" value="{{$apl->metas[22]->meta_value}}" readonly>
                                                </div>
                                            </div>
                                            @else
                                            <div class="form-group col-md-6">
                                                <label class="float-left" for="applicant_cur_salary">Current
                                                    Salary</label>
                                                <div class="input-group mb-2 mr-sm-2">
                                                    <input type="text" class="form-control" id="applicant_cur_salary" name="applicant_cur_salary" value="{{$apl->metas[21]->meta_value}} {{$apl->metas[22]->meta_value}}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="float-left" for="applicant_exp_salary">Expected
                                                    Salary</label>
                                                <div class="input-group mb-2 mr-sm-2">
                                                    <input type="text" class="form-control" id="applicant_exp_salary" name="applicant_exp_salary" value="{{$apl->metas[23]->meta_value}} {{$apl->metas[24]->meta_value}}" readonly>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            @if($apl->created_at < Carbon\Carbon::parse('22-9-2020')) <div class="col-md-6">
                                                <label class="float-left" for="resume_applicant">Uploaded
                                                    Resume</label>
                                                <div class="input-group">
                                                    <a href="{{$apl->resume_url}}" target="_blank">View
                                                        Attachment</a>
                                                </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="float-left" for="applicant_exp_salary">LinkedIn
                                                (Optional)</label>
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-linkedin-square"></i></div>
                                                </div>
                                                <input type="text" class="form-control" id="applicant_linkedin" name="applicant_linkedin" value="{{$apl->metas[23]->meta_value}}" readonly>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-6">
                                            <label class="float-left" for="resume_applicant">Uploaded
                                                Resume</label>
                                            <div class="input-group">
                                                <a href="{{$apl->resume_url}}" target="_blank">View
                                                    Attachment</a>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="float-left" for="applicant_linkedin">LinkedIn
                                                (Optional)</label>
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-linkedin-square"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" id="applicant_linkedin" name="applicant_linkedin" value="{{$apl->metas[25]->meta_value}}" readonly>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </form>
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
                                    @if($apl->created_at < Carbon\Carbon::parse('22-9-2020')) <div class="p-3 mb-2 bg-white  rounded">
                                        {!!$apl->metas[24]->meta_value!!}
                                </div>
                                @else
                                <div class="p-3 mb-2 bg-white  rounded">
                                    {!!$apl->metas[26]->meta_value!!}
                                </div>
                                @endif
                            </div>
                        </div>
                        @elseif ($apl->created_at > Carbon\Carbon::parse('15-02-2023')) 
            <div class="card-body ">
                <form>
                    <div class="card-body">
                        <div class="form-row">
                        <div class="p-3 mb-2 border rounded border-dark bg-light">
                            <h6 class="mt-2">Applicant's Particulars</h6>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="float-left" for="applicant_name">Name</label>
                                    <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="{{$apl->metas[1]->meta_value}}" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="float-left" for="applicant_nationality">Nationality</label>
                                    <input type="text" class="form-control" id="applicant_nationality" name="applicant_nationality" value="{{$apl->metas[2]->meta_value}}" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="float-left" for="applicant_ic">National ID No.
                                    </label>
                                    <input type="text" oninput="this.value = this.value.replace(/[^0-9a-zA-Z-\s]/g, '')" class="form-control" id="applicant_ic" name="applicant_ic" value="{{$apl->metas[3]->meta_value}}" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="float-left" for="applicant_gender">Gender</label>
                                    <select class="form-control" id="applicant_gender" name="applicant_gender" readonly>
                                        <option value="">Choose One</option>
                                        <option value="Male" {{ ($apl->metas[4]->meta_value == "Male" ? "selected":"") }}>
                                            Male</option>
                                        <option value="Female" {{ ($apl->metas[4]->meta_value == "Female" ? "selected":"") }}>
                                            Female</option>
                                    </select>
                                </div>
                               
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="float-left" for="applicant_email">Email</label>
                                    <input type="email" class="form-control" id="applicant_email" name="applicant_email" value="{{$apl->metas[5]->meta_value}}" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="float-left" for="applicant_tel_mobile">Tel No.
                                        (Mobile)</label>
                                    <input type="text" maxlength="12" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" id="applicant_tel_mobile" name="applicant_tel_mobile" value="{{$apl->metas[6]->meta_value}}" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="float-left" for="applicant_tel_office">Tel No.
                                        (Office)</label>
                                    <input type="text" maxlength="12" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" id="applicant_tel_office" name="applicant_tel_office" value="{{$apl->metas[7]->meta_value}}" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="float-left" for="applicant_dob">Date of Birth</label>
                                    <input type="text"  class="form-control" id="applicant_dob" name="applicant_dob" value="{{$apl->metas[8]->meta_value}}" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="float-left" for="applicant_marital_stat">Marital
                                        Status</label>
                                    <select class="form-control" id="applicant_marital_stat" name="applicant_marital_stat" readonly>
                                        <option value=" ">Choose one</option>
                                        <option value="Single" {{ ($apl->metas[9]->meta_value == "Single" ? "selected":"") }}>
                                            Single</option>
                                        <option value="Married" {{ ($apl->metas[9]->meta_value == "Married" ? "selected":"") }}>
                                            Married</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="float-left" for="applicant_address">Address</label>
                                    <input type="text" class="form-control" id="applicant_address" name="applicant_address" value="{{$apl->metas[10]->meta_value}}" readonly></input>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="float-left" for="applicant_address">City</label>
                                    <input type="text" class="form-control" id="applicant_city" name="applicant_city" value="{{$apl->metas[11]->meta_value}}" readonly></input>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="float-left" for="applicant_state">State</label>
                                    <input type="text" class="form-control" id="applicant_state" name="applicant_state" value="{{$apl->metas[12]->meta_value}}" readonly></input>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="float-left" for="applicant_postcode">Postcode</label>
                                    <input type="text"  class="form-control" id="applicant_postcode" name="applicant_postcode" value="{{$apl->metas[13]->meta_value}}" readonly></input>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                            <h6 class="mt-2">Health Conditions</h6>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <p class="float-left">Have you suffered from or are you
                                        currently suffering from any serious illness? ( If yes,
                                        please state exact details )</p>
                                    <textarea class="form-control" id="applicant_serious_health_cond" name="applicant_serious_health_cond" rows="3" placeholder="{{$apl->metas[14]->meta_value}}" readonly></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12" id="is_pregnant">
                                    <p class="float-left mr-2">Are you pregnant now?</p>
                                    <select class="form-control" id="applicant_pregnant" name="applicant_pregnant" readonly>
                                        <option {{ ($apl->metas[15]->meta_value == "Yes" ? "selected":"") }}>
                                            Yes</option>
                                        <option {{ ($apl->metas[15]->meta_value == "No" ? "selected":"") }}>
                                            No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                            <h6 class="mt-2">Referees</h6>
                            <div class="row">
                                <div class="form-group col-md-12">
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
                                    <label class="float-left mt-2" for="applicant_referee_1_name">Name</label>
                                    <input type="text" class="form-control" id="applicant_referee_1_name" name="applicant_referee_1_name" value="{{$apl->metas[16]->meta_value}}" readonly>
                                    <label class="float-left mt-2" for="applicant_referee_1_mobile">Tel
                                        No.
                                    </label>
                                    <input type="text" class="form-control" maxlength="12" id="applicant_referee_1_mobile" name="applicant_referee_1_mobile" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{$apl->metas[17]->meta_value}}" readonly>
                                    <label class="float-left mt-2" for="applicant_referee_1_occupation">Occupation
                                    </label>
                                    <input type="text" class="form-control" id="applicant_referee_1_occupation" name="applicant_referee_1_occupation" value="{{$apl->metas[18]->meta_value}}" readonly>
                                    <label class="float-left mt-2" for="applicant_referee_1_known">No. of
                                        Years Known
                                    </label>
                                    <input type="text" class="form-control" id="applicant_referee_1_known" name="applicant_referee_1_known" value="{{$apl->metas[19]->meta_value}}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <h6>
                                        Referee 2
                                    </h6>
                                    <label class="float-left mt-2" for="applicant_referee_2_name">Name</label>
                                    <input type="text" class="form-control" id="applicant_referee_2_name" name="applicant_referee_2_name" value="{{$apl->metas[20]->meta_value}}" readonly>
                                    <label class="float-left mt-2" for="applicant_referee_2_mobile">Tel
                                        No.
                                    </label>
                                    <input type="text" class="form-control" maxlength="12" id="applicant_referee_2_mobile" name="applicant_referee_2_mobile" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{$apl->metas[21]->meta_value}}" readonly>
                                    <label class="float-left mt-2" for="applicant_referee_2_occupation">Occupation
                                    </label>
                                    <input type="text" class="form-control" id="applicant_referee_2_occupation" name="applicant_referee_2_occupation" value="{{$apl->metas[22]->meta_value}}" readonly>
                                    <label class="float-left mt-2" for="applicant_referee_2_known">No. of
                                        Years Known
                                    </label>
                                    <input type="text" class="form-control" id="applicant_referee_2_known" name="applicant_referee_2_known" value="{{$apl->metas[23]->meta_value}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="float-left" for="applicant_willing_travel">Willing
                                        to Travel</label>
                                    <select class="form-control" id="applicant_willing_travel" name="applicant_willing_travel" readonly>
                                        <option value="">Choose one</option>
                                        <option value="No" {{ ($apl->metas[24]->meta_value == "No" ? "selected":"") }}>
                                            No</option>
                                        <option value="Light" {{ ($apl->metas[24]->meta_value == "Light" ? "selected":"") }}>
                                            Light</option>
                                        <option value="Moderate" {{ ($apl->metas[24]->meta_value == "Moderate" ? "selected":"") }}>
                                            Moderate</option>
                                        <option value="Heavy" {{ ($apl->metas[24]->meta_value == "Heavy" ? "selected":"") }}>
                                            Heavy</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="float-left" for="applicant_notice_period">Notice
                                        Period</label>
                                    <input type="number" class="form-control" id="applicant_notice_period" name="applicant_notice_period" value="{{$apl->metas[25]->meta_value}}" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="float-left" for="applicant_notice_year_week">Week/Month</label>
                                    <select class="form-control" id="applicant_notice_year_week" name="applicant_notice_year_week" readonly>
                                        <option value="">Choose one</option>
                                        <option {{ ($apl->metas[26]->meta_value == "Week(s)" ? "selected":"") }}>
                                            Week(s)</option>
                                        <option {{ ($apl->metas[26]->meta_value == "Month(s)" ? "selected":"") }}>
                                            Month(s)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                @if($apl->created_at < Carbon\Carbon::parse('22-9-2020')) <div class="form-group col-md-6">
                                    <label class="float-left" for="applicant_cur_salary">Current
                                        Salary</label>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">RM</div>
                                        </div>
                                        <input type="number" class="form-control" id="applicant_cur_salary" name="applicant_cur_salary" value="{{$apl->metas[25]->meta_value}}" readonly>
                                    </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="float-left" for="applicant_exp_salary">Expected
                                    Salary</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">RM</div>
                                    </div>
                                    <input type="number" class="form-control" id="applicant_exp_salary" name="applicant_exp_salary" value="{{$apl->metas[26]->meta_value}}" readonly>
                                </div>
                            </div>
                            @else
                            <div class="form-group col-md-6">
                                <label class="float-left" for="applicant_cur_salary">Current
                                    Salary</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <input type="text" class="form-control" id="applicant_cur_salary" name="applicant_cur_salary" value="{{$apl->metas[27]->meta_value}} {{$apl->metas[28]->meta_value}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="float-left" for="applicant_exp_salary">Expected
                                    Salary</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <input type="text" class="form-control" id="applicant_exp_salary" name="applicant_exp_salary" value="{{$apl->metas[29]->meta_value}} {{$apl->metas[30]->meta_value}}" readonly>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                            <label class="float-left" for="resume_applicant">Uploaded
                                Resume</label>
                            <div class="input-group">
                                <a href="{{$apl->resume_url}}" target="_blank">View
                                    Attachment</a>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="float-left" for="applicant_linkedin">LinkedIn
                                (Optional)</label>
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-linkedin-square"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="applicant_linkedin" name="applicant_linkedin" value="{{$apl->metas[31]->meta_value}}" readonly>
                            </div>
                        </div>
                    </div>
                </form>
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
                        @if($apl->created_at < Carbon\Carbon::parse('22-9-2020')) <div class="p-3 mb-2 bg-white  rounded">
                            {!!$apl->metas[28]->meta_value!!}
                    </div>
                    @else
                    <div class="p-3 mb-2 bg-white  rounded">
                        {!!$apl->metas[32]->meta_value!!}
                    </div>
                    @endif
                </div>
            </div>

                        @else
                        <div class="card-body ">
                            <form>
                                <div class="card-body">
                                    <div class="p-3 mb-2 border rounded border-dark bg-light">
                                        <h6 class="mt-2">Applicant's Particulars</h6>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="float-left" for="applicant_name">Name</label>
                                                <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="{{$apl->metas[1]->meta_value}}" readonly>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="float-left" for="applicant_ic">NRIC No
                                                </label>
                                                <input type="text" oninput="this.value = this.value.replace(/[^0-9a-zA-Z-\s]/g, '')" class="form-control" id="applicant_ic" name="applicant_ic" value="{{$apl->metas[2]->meta_value}}" readonly>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="float-left" for="applicant_gender">Gender</label>
                                                <select class="form-control" id="applicant_gender" name="applicant_gender" readonly>
                                                    <option value="">Choose One</option>
                                                    <option value="Male" {{ ($apl->metas[3]->meta_value == "Male" ? "selected":"") }}>
                                                        Male</option>
                                                    <option value="Female" {{ ($apl->metas[3]->meta_value == "Female" ? "selected":"") }}>
                                                        Female</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="float-left" for="applicant_marital_stat">Marital
                                                    Status</label>
                                                <select class="form-control" id="applicant_marital_stat" name="applicant_marital_stat" readonly>
                                                    <option value=" ">Choose one</option>
                                                    <option value="Single" {{ ($apl->metas[4]->meta_value == "Single" ? "selected":"") }}>
                                                        Single</option>
                                                    <option value="Married" {{ ($apl->metas[4]->meta_value == "Married" ? "selected":"") }}>
                                                        Married</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label class="float-left" for="applicant_email">Email</label>
                                                <input type="email" class="form-control" id="applicant_email" name="applicant_email" value="{{$apl->metas[5]->meta_value}}" readonly>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="float-left" for="applicant_tel_mobile">Tel No.
                                                    (Mobile)</label>
                                                <input type="text" maxlength="12" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" id="applicant_tel_mobile" name="applicant_tel_mobile" value="{{$apl->metas[6]->meta_value}}" readonly>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="float-left" for="applicant_tel_office">Tel No.
                                                    (Office)</label>
                                                <input type="text" maxlength="12" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" id="applicant_tel_office" name="applicant_tel_office" value="{{$apl->metas[7]->meta_value}}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label class="float-left" for="applicant_address">Address</label>
                                                <input type="text" class="form-control" id="applicant_address" name="applicant_address" value="{{$apl->metas[8]->meta_value}}" readonly></input>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label class="float-left" for="applicant_address">City</label>
                                                <input type="text" class="form-control" id="applicant_city" name="applicant_city" value="{{$apl->metas[9]->meta_value}}" readonly></input>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="float-left" for="applicant_state">State</label>
                                                <input type="text" class="form-control" id="applicant_state" name="applicant_state" value="{{$apl->metas[10]->meta_value}}" readonly></input>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="float-left" for="applicant_postcode">Postcode</label>
                                                <input type="text" class="form-control" id="applicant_postcode" name="applicant_postcode" value="{{$apl->metas[11]->meta_value}}" readonly></input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                                        <h6 class="mt-2">Health Conditions</h6>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <p class="float-left">Have you suffered from or are you
                                                    currently suffering from any serious illness? ( If yes,
                                                    please state exact details )</p>
                                                <textarea class="form-control" id="applicant_serious_health_cond" name="applicant_serious_health_cond" rows="3" placeholder="{{$apl->metas[12]->meta_value}}" readonly></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12" id="is_pregnant">
                                                <p class="float-left mr-2">Are you pregnant now?</p>
                                                <select class="form-control" id="applicant_pregnant" name="applicant_pregnant" readonly>
                                                    <option {{ ($apl->metas[13]->meta_value == "Yes" ? "selected":"") }}>
                                                        Yes</option>
                                                    <option {{ ($apl->metas[13]->meta_value == "No" ? "selected":"") }}>
                                                        No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                                        <h6 class="mt-2">Referees</h6>
                                        <div class="row">
                                            <div class="form-group col-md-12">
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
                                                <label class="float-left mt-2" for="applicant_referee_1_name">Name</label>
                                                <input type="text" class="form-control" id="applicant_referee_1_name" name="applicant_referee_1_name" value="{{$apl->metas[14]->meta_value}}" readonly>
                                                <label class="float-left mt-2" for="applicant_referee_1_mobile">Tel
                                                    No.
                                                </label>
                                                <input type="text" class="form-control" maxlength="12" id="applicant_referee_1_mobile" name="applicant_referee_1_mobile" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{$apl->metas[15]->meta_value}}" readonly>
                                                <label class="float-left mt-2" for="applicant_referee_1_occupation">Occupation
                                                </label>
                                                <input type="text" class="form-control" id="applicant_referee_1_occupation" name="applicant_referee_1_occupation" value="{{$apl->metas[16]->meta_value}}" readonly>
                                                <label class="float-left mt-2" for="applicant_referee_1_known">No. of
                                                    Years Known
                                                </label>
                                                <input type="text" class="form-control" id="applicant_referee_1_known" name="applicant_referee_1_known" value="{{$apl->metas[17]->meta_value}}" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <h6>
                                                    Referee 2
                                                </h6>
                                                <label class="float-left mt-2" for="applicant_referee_2_name">Name</label>
                                                <input type="text" class="form-control" id="applicant_referee_2_name" name="applicant_referee_2_name" value="{{$apl->metas[18]->meta_value}}" readonly>
                                                <label class="float-left mt-2" for="applicant_referee_2_mobile">Tel
                                                    No.
                                                </label>
                                                <input type="text" class="form-control" maxlength="12" id="applicant_referee_2_mobile" name="applicant_referee_2_mobile" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{$apl->metas[19]->meta_value}}" readonly>
                                                <label class="float-left mt-2" for="applicant_referee_2_occupation">Occupation
                                                </label>
                                                <input type="text" class="form-control" id="applicant_referee_2_occupation" name="applicant_referee_2_occupation" value="{{$apl->metas[20]->meta_value}}" readonly>
                                                <label class="float-left mt-2" for="applicant_referee_2_known">No. of
                                                    Years Known
                                                </label>
                                                <input type="text" class="form-control" id="applicant_referee_2_known" name="applicant_referee_2_known" value="{{$apl->metas[21]->meta_value}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 mb-2 border rounded border-dark mt-4 bg-light">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="float-left" for="applicant_willing_travel">Willing
                                                    to Travel</label>
                                                <select class="form-control" id="applicant_willing_travel" name="applicant_willing_travel" readonly>
                                                    <option value="">Choose one</option>
                                                    <option value="No" {{ ($apl->metas[22]->meta_value == "No" ? "selected":"") }}>
                                                        No</option>
                                                    <option value="Light" {{ ($apl->metas[22]->meta_value == "Light" ? "selected":"") }}>
                                                        Light</option>
                                                    <option value="Moderate" {{ ($apl->metas[22]->meta_value == "Moderate" ? "selected":"") }}>
                                                        Moderate</option>
                                                    <option value="Heavy" {{ ($apl->metas[22]->meta_value == "Heavy" ? "selected":"") }}>
                                                        Heavy</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="float-left" for="applicant_notice_period">Notice
                                                    Period</label>
                                                <input type="number" class="form-control" id="applicant_notice_period" name="applicant_notice_period" value="{{$apl->metas[23]->meta_value}}" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="float-left" for="applicant_notice_year_week">Week/Month</label>
                                                <select class="form-control" id="applicant_notice_year_week" name="applicant_notice_year_week" readonly>
                                                    <option value="">Choose one</option>
                                                    <option {{ ($apl->metas[24]->meta_value == "Week(s)" ? "selected":"") }}>
                                                        Week(s)</option>
                                                    <option {{ ($apl->metas[24]->meta_value == "Month(s)" ? "selected":"") }}>
                                                        Month(s)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @if($apl->created_at < Carbon\Carbon::parse('22-9-2020')) <div class="form-group col-md-6">
                                                <label class="float-left" for="applicant_cur_salary">Current
                                                    Salary</label>
                                                <div class="input-group mb-2 mr-sm-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">RM</div>
                                                    </div>
                                                    <input type="number" class="form-control" id="applicant_cur_salary" name="applicant_cur_salary" value="{{$apl->metas[25]->meta_value}}" readonly>
                                                </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="float-left" for="applicant_exp_salary">Expected
                                                Salary</label>
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">RM</div>
                                                </div>
                                                <input type="number" class="form-control" id="applicant_exp_salary" name="applicant_exp_salary" value="{{$apl->metas[26]->meta_value}}" readonly>
                                            </div>
                                        </div>
                                        @else
                                        <div class="form-group col-md-6">
                                            <label class="float-left" for="applicant_cur_salary">Current
                                                Salary</label>
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" class="form-control" id="applicant_cur_salary" name="applicant_cur_salary" value="{{$apl->metas[25]->meta_value}} {{$apl->metas[26]->meta_value}}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="float-left" for="applicant_exp_salary">Expected
                                                Salary</label>
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" class="form-control" id="applicant_exp_salary" name="applicant_exp_salary" value="{{$apl->metas[27]->meta_value}} {{$apl->metas[28]->meta_value}}" readonly>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        @if($apl->created_at < Carbon\Carbon::parse('22-9-2020')) <div class="col-md-6">
                                            <label class="float-left" for="resume_applicant">Uploaded
                                                Resume</label>
                                            <div class="input-group">
                                                <a href="{{$apl->resume_url}}" target="_blank">View
                                                    Attachment</a>
                                            </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="float-left" for="applicant_exp_salary">LinkedIn
                                            (Optional)</label>
                                        <div class="input-group mb-2 mr-sm-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-linkedin-square"></i></div>
                                            </div>
                                            <input type="text" class="form-control" id="applicant_linkedin" name="applicant_linkedin" value="{{$apl->metas[27]->meta_value}}" readonly>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <label class="float-left" for="resume_applicant">Uploaded
                                            Resume</label>
                                        <div class="input-group">
                                            <a href="{{$apl->resume_url}}" target="_blank">View
                                                Attachment</a>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="float-left" for="applicant_linkedin">LinkedIn
                                            (Optional)</label>
                                        <div class="input-group mb-2 mr-sm-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-linkedin-square"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" id="applicant_linkedin" name="applicant_linkedin" value="{{$apl->metas[29]->meta_value}}" readonly>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </form>
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
                                    @if($apl->created_at < Carbon\Carbon::parse('22-9-2020')) <div class="p-3 mb-2 bg-white  rounded">
                                        {!!$apl->metas[28]->meta_value!!}
                                </div>
                                @else
                                <div class="p-3 mb-2 bg-white  rounded">
                                    {!!$apl->metas[30]->meta_value!!}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                </div>
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