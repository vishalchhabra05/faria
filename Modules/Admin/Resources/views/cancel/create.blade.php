@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Cancel Price Master</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/cancel-master/master')}}">Cancel Price Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Price</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/cancel-master/store')}}" enctype="multipart/form-data">
    @csrf
    <div class="col-sm-12">
            <div class="form-group">
                <label for="service">Type (Customer/Provider)</label>
                    <select class="form-control required" id="type" name="type">
                            <option value="">Select</option>                    
                            <option value="customer">Customer</option>        
                            <option value="provider">Provider</option>  
                    </select>
                </div>
                @if ($errors->has('type'))
                <strong>{{ $errors->first('type') }}</strong>
            @endif
        </div> 
      
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Hours</label>
                <input class="form-control required number" minlength="1" maxlength="2" id="hours" name="hours" type="text" placeholder="Hours" value="{{old('hours')}}" >
                    @if ($errors->has('hours'))
                      <strong>{{ $errors->first('hours') }}</strong>
                    @endif
            </div>
        </div>    
        
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Price</label>
                <input class="form-control required number" minlength="1" maxlength="3" id="price" name="price" type="text" placeholder="price" value="{{old('price')}}" >
                    @if ($errors->has('price'))
                      <strong>{{ $errors->first('price') }}</strong>
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