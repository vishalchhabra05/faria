@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Add Taxes</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/price/price')}}">Taxes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Taxes</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/tax/store')}}" enctype="multipart/form-data">
    @csrf
    <div class="col-sm-12">
            <div class="form-group">
                <label for="service">State</label>
                    <select class="form-control required" id="state" name="state">
                        <option value="">Select</option>
                        @foreach($state as $value)
                            <option value="{{ $value->name }}">{{ $value->name }}</option>        
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('state'))
                <strong>{{ $errors->first('state') }}</strong>
            @endif
        </div> 
      
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Taxes</label>
                <input class="form-control required number" minlength="1" maxlength="10" id="texes" name="texes" type="text" placeholder="Taxes" value="{{old('texes')}}" >
                    @if ($errors->has('texes'))
                      <strong>{{ $errors->first('texes') }}</strong>
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