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
                                        <form class="form" method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="card card-login">
                                                <div class="card-header ">
                                                    <div class="card-header ">
                                                        <h3 class="header text-center">{{ __('Admin Login') }}</h3>
                                                    </div>
                                                </div>
                                                <div class="card-body ">

                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-single-02"></i>
                                                            </span>
                                                        </div>
                                                        <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') }}" required autofocus>

                                                        @if ($errors->has('email'))
                                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                                <strong>{{ $errors->first('email') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-single-02"></i>
                                                            </span>
                                                        </div>
                                                        <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" type="password" required>

                                                        @if ($errors->has('password'))
                                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                                <strong>{{ $errors->first('password') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="form-check">
                                                             <label class="form-check-label">
                                                                <input class="form-check-input" name="remember" type="checkbox" value="" {{ old('remember') ? 'checked' : '' }}>
                                                                <span class="form-check-sign"></span>
                                                                {{ __('Remember me') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-footer">
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-warning btn-round mb-3">{{ __('Sign in') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <a href="{{ route('password.request') }}" class="btn btn-link">
                                            {{ __('Forgot password') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        {{-- <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                <form class="form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="card card-login">
                        <div class="card-header ">
                            <div class="card-header ">
                                <h3 class="header text-center">{{ __('Admin Login') }}</h3>
                            </div>
                        </div>
                        <div class="card-body ">

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="nc-icon nc-single-02"></i>
                                    </span>
                                </div>
                                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="nc-icon nc-single-02"></i>
                                    </span>
                                </div>
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" type="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                     <label class="form-check-label">
                                        <input class="form-check-input" name="remember" type="checkbox" value="" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="form-check-sign"></span>
                                        {{ __('Remember me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="text-center">
                                <button type="submit" class="btn btn-warning btn-round mb-3">{{ __('Sign in') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
                <a href="{{ route('password.request') }}" class="btn btn-link">
                    {{ __('Forgot password') }}
                </a>
            </div>
        </div> --}}
    </div>
@endsection


