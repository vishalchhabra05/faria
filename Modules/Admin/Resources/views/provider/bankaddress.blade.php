@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">@if(!empty($detail->unit))Edit @else Add @endif Address on Bank Account</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider')}}">Provider</a></li>
                        @if(!empty($detail->unit))
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider/edit',$id)}}">Edit Details</a></li>
                          @endif 
                        <li class="breadcrumb-item active" aria-current="page">@if(!empty($detail->unit))Edit @else Add @endif Address</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/provider/step7',$id)}}" enctype="multipart/form-data">
    @csrf
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Company Address</label>
                <input class="form-control required" id="company_address" name="company_address" type="text" placeholder="Company Address" value="{{$detail->company_address ?? ''}}" >
                    @if ($errors->has('company_address'))
                      <strong>{{ $errors->first('company_address') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Unit</label>
                <input class="form-control required" id="unit" name="unit" type="text" placeholder="Unit" value="{{$detail->unit ?? ''}}" >
                    @if ($errors->has('unit'))
                      <strong>{{ $errors->first('unit') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">City</label>
                <input class="form-control required" id="city" name="city" type="text" placeholder="city" value="{{$detail->city ?? ''}}" >
                    @if ($errors->has('city'))
                      <strong>{{ $errors->first('city') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
        <div class="form-group">
            <label for="category">State</label>
                <select class="form-control required" id="state" name="state">
                    <option value="">Select</option>
                        <option value="Rajasthan">Rajasthan</option>        
                        <option value="UP">UP</option> 
                </select>
            </div>
            @if ($errors->has('state'))
            <strong>{{ $errors->first('state') }}</strong>
        @endif
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