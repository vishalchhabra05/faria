@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">@if(!empty($detail->tranist_number))Edit @else Add @endif Banking Information</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider')}}">Provider</a></li>
                        @if(!empty($detail->tranist_number))
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider/edit',$id)}}">Edit Details</a></li>
                          @endif 
                        <li class="breadcrumb-item active" aria-current="page">@if(!empty($detail->tranist_number))Edit @else Add @endif Banking Information</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/provider/step9',$id)}}" enctype="multipart/form-data">
    @csrf
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Tranist Number</label>
                <input class="form-control required" id="tranist_number" name="tranist_number" type="text" placeholder="Tranist Number" value="{{$detail->tranist_number ?? ''}}" >
                    @if ($errors->has('tranist_number'))
                      <strong>{{ $errors->first('tranist_number') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Institution Number</label>
                <input class="form-control required" id="institution_number" name="institution_number" type="text" placeholder="Institution Number" value="{{$detail->institution_number ?? ''}}" >
                    @if ($errors->has('institution_number'))
                      <strong>{{ $errors->first('institution_number') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Account Number</label>
                <input class="form-control required" id="account_number" name="account_number" type="text" placeholder="Account Number" value="{{$detail->account_number ?? ''}}" >
                    @if ($errors->has('account_number'))
                      <strong>{{ $errors->first('account_number') }}</strong>
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