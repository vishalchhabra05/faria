@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Edit Profile</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Edit Profile</a></li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/profile/update')}}" enctype="multipart/form-data">
    @csrf
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">First Name</label>
                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->first_name}}" >
                    @if ($errors->has('first_name'))
                      <strong>{{ $errors->first('first_name') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Last Name</label>
                <input class="form-control required" id="last_name" name="last_name" type="text" placeholder="Last Name" value="{{$value->last_name}}" >
                    @if ($errors->has('last_name'))
                      <strong>{{ $errors->first('last_name') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="category">Country Code</label>
                                    <select class="form-control required" id="mobile_code" name="mobile_code">
                                        <option value="">Select</option>
                                        @foreach($country_code as $val)
                                            <option value="{{$val->phonecode}}" {{$val->phonecode == $value->mobile_code ? 'selected' : ''}} >{{$val->phonecode.' '.$val->name}}</option>   
                                            @endforeach     
                                            
                                    </select>
                                </div>
                                @if ($errors->has('mobile_code'))
                                <strong>{{ $errors->first('mobile_code') }}</strong>
                            @endif
                            </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Mobile</label>
                <input class="form-control required number" minlength="8" maxlength="15" id="mobile" name="mobile" type="text" placeholder="Mobile Number" value="{{$value->mobile}}" >
                    @if ($errors->has('mobile'))
                      <strong>{{ $errors->first('mobile') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Email Address</label>
                <input class="form-control required" id="email" name="email" type="email" placeholder="Email Address" value="{{$value->email}}" >
                    @if ($errors->has('email'))
                      <strong>{{ $errors->first('email') }}</strong>
                    @endif
            </div>
        </div>  
 
            <div class="col-sm-12">
            <div class="form-group">
            <label for="name">Image</label>
                <div class="upload-btn">
                    <input id="image" type="file" name="image">
                    <label class="btn btn-primary btn-sm" for="image">Upload File</label>
                </div>
                <input type="hidden" name="image" value="{{$value->image}}">
            </div>
            </div>
            <img src="{{$value->image}}" width="100px" height="100px"/>
        <div class="card-footer">
            <button class="btn btn-sm btn-primary" type="submit">Submit</button>
        </div>
    </form>
            <!-- <div class="col-sm-12 copyright">
                <p>2000 - {{date('Y')}} ©  <a href="#">Eventrol</a></p>
            </div> -->
        </div>
    </div>
</div>  
</div>
    </div>
</div>  
@endsection
@section('admin::custom_js')
<script>
jQuery(function($) {
  $("#validate-form").validate({
  });
  
});
</script>
@endsection