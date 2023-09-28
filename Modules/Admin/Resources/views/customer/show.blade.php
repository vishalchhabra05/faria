@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">View Customer</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/user')}}">Customer</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Customer</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="col-sm-12 mb-4">
    <div class="fade-in">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">First Name</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->first_name}}" disabled> @if ($errors->has('first_name'))
                                <strong>{{ $errors->first('first_name') }}</strong> @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Last Name</label>
                                <input class="form-control required" id="last_name" name="last_name" type="text" placeholder="Last Name" value="{{$value->last_name}}" disabled> @if ($errors->has('last_name'))
                                <strong>{{ $errors->first('last_name') }}</strong> @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Country Code</label>
                                <input class="form-control required" id="mobile" name="mobile" type="text" placeholder="Mobile Number" value="{{$value->mobile_code ?? ''}}" disabled> @if ($errors->has('mobile'))
                                <strong>{{ $errors->first('mobile') }}</strong> @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Mobile Number</label>
                                <input class="form-control required" id="mobile" name="mobile" type="text" placeholder="Mobile Number" value="{{$value->mobile}}" disabled> @if ($errors->has('mobile'))
                                <strong>{{ $errors->first('mobile') }}</strong> @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Email Address</label>
                                <input class="form-control required" id="email" name="email" type="email" placeholder="Email Address" value="{{$value->email}}" disabled @if ($errors->has('email'))
                                <strong>{{ $errors->first('email') }}</strong> @endif
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Profile Image</label>
                                @if(!empty($value->image))
                                <img src="{{$value->image}}" height="100px" width="100px">
                                @else
                              Not Found !
                              @endif
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div 

    
@endsection
@section('admin::custom_js')
<script>
jQuery(function($) {
  $("#validate-form").validate({
  });
  
});
</script>
@endsection