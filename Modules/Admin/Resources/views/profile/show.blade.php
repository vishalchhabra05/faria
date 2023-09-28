@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">My Profile</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Profile</li>
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
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$data->first_name ?? ''}}" disabled> 
                               
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Last Name</label>
                                <input class="form-control required" id="last_name" name="last_name" type="text" placeholder="Last Name" value="{{$data->last_name ?? ''}}" disabled>
                             
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Code</label>
                                <input class="form-control required" id="mobile" name="mobile" type="text" placeholder="Mobile Number" value="{{$data->mobile_code ?? ''}}" disabled> 
                               
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Mobile</label>
                                <input class="form-control required" id="mobile" name="mobile" type="text" placeholder="Mobile Number" value="{{$data->mobile ?? ''}}" disabled> 
                               
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Email Address</label>
                                <input class="form-control required" id="email" name="email" type="email" placeholder="Email Address" value="{{$data->email ?? ''}}" disabled >
                               
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Image</label>
                                @if(!empty($data->image))
                                <img src="{{$data->image}}" width="100px" height="100px">
                                @else
                               Not Found
                               @endif
                            </div>
                        </div>
                        <a href="{{ url('admin/profile/edit') }}" class="btn btn-primary " style="float:right;">Edit Profile</a>



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