@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">@if(!empty($detail->first_name))Edit @else Add @endif Company/Acount Information</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider')}}">Provider</a></li>
                        @if(!empty($detail->first_name))
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider/edit',$id)}}">Edit Details</a></li>
                          @endif 
                        <li class="breadcrumb-item active" aria-current="page">@if(!empty($detail->first_name))Edit @else Add @endif Company/Acount Information</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/provider/step8',$id)}}" enctype="multipart/form-data">
    @csrf
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">First Name</label>
                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$detail->first_name ?? ''}}" >
                    @if ($errors->has('first_name'))
                      <strong>{{ $errors->first('first_name') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Last Name</label>
                <input class="form-control required" id="last_name" name="last_name" type="text" placeholder="last name" value="{{$detail->last_name ?? ''}}" >
                    @if ($errors->has('last_name'))
                      <strong>{{ $errors->first('last_name') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">D.O.B</label>
                <input class="form-control date required" id="date-input" name="dob" type="date" placeholder="dob" value="{{$detail->dob ?? ''}}" >
                    @if ($errors->has('dob'))
                      <strong>{{ $errors->first('dob') }}</strong>
                    @endif
            </div>
        </div>  
        
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">SSN Number</label>
                <input class="form-control required" id="ssn_number" name="ssn_number" type="text" placeholder="SSN Number" value="{{$detail->ssn_number ?? ''}}" >
                    @if ($errors->has('ssn_number'))
                      <strong>{{ $errors->first('ssn_number') }}</strong>
                    @endif
            </div>
        </div>  
        
 
     
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