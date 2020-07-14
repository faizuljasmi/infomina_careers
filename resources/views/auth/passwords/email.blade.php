@extends('layouts.app', [
'class' => 'login-page',

])

@section('content')
<div class="content">
    <div class="content col-md-6 ml-auto mr-auto">
        <div class="header py-5 pb-7 pt-lg-9">
            <div class="container col-md-10">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-12 pt-5">
                            <div class="col-md-12">
                                <div class="card">
                                    <form class="form" method="POST" action="{{ route('password.email') }}">
                                        @csrf
                                        <div class="card card-login">
                                            <div class="card-header ">
                                                <h3 class="header text-center">{{ __('Reset Password') }}</h3>
                                            </div>
                                            @if (session('status'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('status') }}
                                            </div>
                                            @endif
                                            <div class="card-body ">
                                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} mb-3">
                                                    <div class="input-group input-group-alternative">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="nc-icon nc-single-02"></i></span>
                                                        </div>
                                                        <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}"
                                                            type="email" name="email" value="{{ old('email') }}" required autofocus>
                                                    </div>
                                                    @if ($errors->has('email'))
                                                    <div>
                                                        <span class="invalid-feedback" style="display: block" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-warning btn-round mb-3">{{ __('Send Password Reset Link') }}</button>
                                                </div>
                                            </div>
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
@endsection


<style>
    html, body {
      height: 100%;
      margin: 0;
    }

    .content {
      height: 100%;
    }
    </style>

