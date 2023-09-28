@extends('layouts.app')
@section('content')

        <div class="login-page">
        <div class="login-box">
            <div class="imgBox">
                <img class="img-fluid" src="images/side_image.svg" alt="image">
            </div>
            <div class="contentBox">
                <div class="logo">
                    <img class="img-fluid" src="images/logo.png" alt="logo">
                </div>
                <h1>Welcome to Faria!</h1>
                <p>Please Login to Admin Panel</p>
                <form class="form" id="validate" method="POST" action="{{ route('login') }}">
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
                    <div class="input-box active">
                        
                     <input type="password" name="password" class="form-control required @error('password') is-invalid @enderror" id="password" placeholder="" autocomplete="current-password">
                        <label class="label" for="password">Password
                       
                        </label>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <a href="javascript:void(0)" class="toggleBtn"></a>
                    </div>
                    <div class="more-option">
                        <div class="form-check float-left">
                        <input type="checkbox" class="form-check-input"  id="exampleCheck1" name="remember" id="remember"  {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="float-right" href="{{ route('password.request') }}"> Forgot your password?</a>
                        @endif 
                    </div>
                    <div class="text-right">
                        <button type="submit" class="login_submit">Login <img class="img-fluid" src="images/login.svg"></button>
                        
                    </div>
                </form>
            </div>
            
        </div>
    </div>
    




@endsection
@section('custom_js')
<script type="text/javascript" src="{{ asset('logincss/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('logincss/js/bootstrap.bundle.js')}}"></script>
<script type="text/javascript">
 $(function()
  {
    $('#validate').validate(
      {
        rules:
        {
          email:{ required:true }
        },
        rules:
        {
          password:{ required:true }
        },
        messages:
        {
          email:
          {
            required:"Email is required<br/>"
          },
          password:
          {
            required:"Password is required<br/>"
          }
          
        },
        
      });
    
  });
    $(document).ready(function() {
        
        $('.toggleBtn').click(function(){
        $('.toggleBtn').toggleClass("active");
        var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
        })
        // $('#email').click(function(){

        //     // Get the Login Name value and trim it
        //     var email = $('#email').val();

        //     // Check if empty of not
        //     if (email.length < 1) {
        //         $(this).parent().addClass('active')
        //         return false;
        //     }
        // });
        // $('#password').click(function(){

        //     // Get the Login Name value and trim it
        //     var password = $('#password').val();

        //     // Check if empty of not
        //     if (password.length < 1) {
        //         $(this).parent().addClass('active')
        //         return false;
        //     }
        // });
    });
</script>
@endsection
