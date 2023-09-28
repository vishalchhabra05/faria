@extends('layouts.app')

@section('content')
<div class="login-page">
        <div class="login-box">
            <div class="imgBox">
                <img class="img-fluid" src="{{asset('images/side_image.svg')}}" alt="image">
            </div>
            <div class="contentBox">
                <div class="logo">
                    <img class="img-fluid" src="{{asset('images/logo.svg')}}" alt="logo">
                </div>
            <h1>{{ __('Reset Password') }}</h1>
            <form method="POST" class="form" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                         <div class="input-box active">                      
                         <input id="email" type="email" class="form-control required valid @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" autocomplete="email" autofocus="" placeholder="example@arkasoftwares.com">
                        <label class="label" for="email">Email</label>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                
                           
                        <div class="input-box active">
                        
                        <input id="password" type="password" placeholder="***********" class="form-control @error('password') is-invalid @enderror required valid" name="password" autocomplete="new-password">
                        <label class="label" for="password">Password
                       
                        </label>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <!-- <a href="javascript:void(0)" class="toggleBtn"></a> -->
                    </div>


                    <div class="input-box active">
                        
                    <input id="password-confirm" type="password" class="form-control required" name="password_confirmation" autocomplete="new-password">
                        <label class="label" for="password-confirm">{{ __('Confirm Password') }}
                        <!-- <a href="javascript:void(0)" class="toggleBtn"></a> -->
                    </div>
                        

                    <div class="text-right">
                        <button type="submit" class="login_submit">   {{ __('Reset Password') }} <img class="img-fluid" src="{{asset('images/login.svg')}}"></button>
                        
                    </div>
        


                        
                    </form>
        </div>
        
    </div>
</div>
@endsection
@section('custom_js')
<script type="text/javascript" src="{{ asset('logincss/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('logincss/js/bootstrap.bundle.js')}}"></script>
<script>
  
        
        $('.toggleBtn').click(function(){
        $('.toggleBtn').toggleClass("active");
        var x = document.getElementById("password");
                    if (x.type === "password") {
                        x.type = "text";
                    } else {
                        x.type = "password";
                    }
        });
   </script>
@endsection
