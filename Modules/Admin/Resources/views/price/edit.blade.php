@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Edit Price</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/price/price')}}">Price</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Price</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/price/update',$data->slug)}}" enctype="multipart/form-data">
    @csrf
    <div class="col-sm-12">
            <div class="form-group">
                <label for="service">Service</label>
                    <select class="form-control required" id="service_id" name="service_id">
                        <option value="">Select</option>
                        @foreach($service as $value)
                            <option value="{{ $value->id }}" {{ $value->id == $data->service_id ? 'selected' : ''}}>{{ $value->service_name }}</option>        
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('service_id'))
                <strong>{{ $errors->first('service_id') }}</strong>
            @endif
        </div> 
        <div class="col-sm-12">
            <div class="form-group">
                <label for="service">Variant</label>
                    <select class="form-control required" id="varient" name="varient">
                        <option value="">Select</option>
                        @foreach($varient as $value)
                            <option value="{{ $value->name }}" {{ $value->name == $data->varient ? 'selected' : ''}}>{{ $value->name }}</option>        
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('varient'))
                <strong>{{ $errors->first('varient') }}</strong>
            @endif
        </div> 
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Price</label>
                <input class="form-control required number" minlength="1" maxlength="10" id="price" name="price" type="text" placeholder="Service Price" value="{{$data->price ?? ''}}" >
                    @if ($errors->has('price'))
                      <strong>{{ $errors->first('price') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Extra Price</label>
                <input class="form-control required number" minlength="1" maxlength="10" id="extra_price" name="extra_price" type="text" placeholder="Extra Price" value="{{old('extra_price') ?? $data->extra_price}}" >
                    @if ($errors->has('extra_price'))
                      <strong>{{ $errors->first('extra_price') }}</strong>
                    @endif
            </div>
        </div> 
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">City</label>
                <input class="form-control required" id="city" name="city" type="text" placeholder="City" value="{{old('city') ?? $data->city}}" >
                    @if ($errors->has('city'))
                      <strong>{{ $errors->first('city') }}</strong>
                    @endif
            </div>
        </div> 
        <input type="hidden" name="state"  id="state" value="{{$data->state ?? ''}}" class="form-control">  
        
        <div class="card-footer">
            <button class="btn btn-sm btn-primary" id="submit" type="submit">Submit</button>
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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPt0Rcopq7I-pxf8lNvvmcG2dNFomhols&libraries=places"></script>
<script>
function initialize() {
          var input =  document.getElementById('city');
          var autocomplete = new google.maps.places.Autocomplete(input);
          google.maps.event.addListener(autocomplete, 'place_changed', function () {
              var place = autocomplete.getPlace();
              $('#city').val(place.name);
              $('#state').val(place.address_components[2].long_name);
            //   $('#lat').val(place.geometry.location.lat());
            //   $('#long').val(place.geometry.location.lng());
          });
      }
      google.maps.event.addDomListener(window, 'load', initialize);


jQuery(function($) {
  $("#validate-form").validate({
    
  });
  
});


</script>
@endsection