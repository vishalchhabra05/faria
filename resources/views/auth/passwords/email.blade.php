@extends('layouts.app')
@section('content')
<div class="login-page">
    <div class="login-box">
        <div class="contentBox">
              <div class="logo">
                    <img class="img-fluid" src="{{asset('images/logo.png')}}" alt="logo">
                </div>
            @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
            <h1>Reset Password!</h1>
            <p>Enter your email address and we'll send you an email with instructions to reset your password.</p>
            <form id="validate" class="form" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="input-box active">

                <input type="email" class="form-control required @error('email') is-invalid @enderror" value="{{ old('email') }}"  autocomplete="email" id="email" name="email"  >
                        <label class="label" for="email">Email</label>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    
                    </div>
                <!-- <div class="form-group">
                        <button type="submit" class="btn btn-primary w-100">{{ __('Submit') }}</button>
                    </div> -->
                    <div class="text-right mt-4">
                        <button type="submit" class="login_submit">Submit <img class="img-fluid" src="{{asset('images/login.svg')}}"></button>
                    </div>
                    <div class="more-option text-center">
                        <p class="m-0">Back to <a href="{{ route('login') }}">Login</a></p>
                    </div>
            </form>
        </div>
        <div class="imgBox d-none d-md-block">
            <img src="{{asset('images/side_image.svg')}}" alt="image">
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    $( document ).ready( function () {
        $( '#validate' ).validate();
    });
</script>
@endsection
