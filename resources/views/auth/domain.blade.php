@extends('layouts.auth')

@section('auth')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Sign in to your domain') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <div class="row">
                                <label for="domain" class="col-md-8 offset-md-2">{{ __('Enter Your') }} <strong>{{ __('Account Domain') }}</strong></label>
                            </div>
                            <div class="row">
                                <div class="col-md-6 offset-md-2 text-md-right">
                                    <input id="domain" type="text" class="form-control text-right inline @error('domain') is-invalid @enderror" name="domain" value="{{ old('domain') }}" placeholder="{{ __('your-domain') }}" required autofocus>
                                </div>
                                <div class="col-md-4 text-lg-left">
                                    <span class="sign-in-tld">.{{ config('app.url_base') }}</span>
                                </div>
                            </div>
                            <div class="row">
                                @error('domain')
                                    <span class="invalid-feedback col-md-6 offset-md-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-2 text-md-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Continue') }} →
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection