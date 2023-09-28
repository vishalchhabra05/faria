@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">@if(isset($check)) Edit @else Add @endif Price</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/service/service')}}">Service</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@if(isset($check)) Edit @else Add @endif Price</li>
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
                    @if(isset($check))
                    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/addprice/update')}}" enctype="multipart/form-data">
                    @else
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/addprice/store')}}" enctype="multipart/form-data">
                   @endif
    @csrf
         <input type="hidden" name="service_id" value="{{$id}}">
         <div class="col-sm-12">
            <div class="form-group">
                <label for="service">Variant</label>
                    <select class="form-control required" id="varient" name="varient">
                        <option value="">Select</option>
                        @foreach($varient as $key => $value)
                            <option value="{{ $value->name }}" @php if($value->name==$slected_varient->varient) { echo 'selected'; } @endphp>{{ $value->name }}</option>        
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('varient'))
                <strong>{{ $errors->first('varient') }}</strong>
            @endif
        </div> 
        <div class="col-sm-12">
        @foreach($check as $key => $val)
            <div class="row field">
                <div class="col">
                   <input type="text" class="form-control required" id="city_{{$key}}" name="city[{{$key}}]" value="{{$val->city}}">
                </div>
                <input type="hidden" name="lat[]" id="lat_{{$key}}"  class="form-control">
                <input type="hidden" name="long[]"  id="long_{{$key}}" class="form-control">
                <input type="hidden" name="state[]"  id="state_{{$key}}" value="{{$val->state ?? ''}}" class="form-control">
                <div class="col">
                    <input type="text" class="form-control required" id="price_{{$key}}" name="price[{{$key}}]" value="{{$val->price}}">
                </div>
                <div class="col">
                     <input type="text" class="form-control required" name="extra_price[{{$key}}]" id="extra_price_{{$key}}" value="{{$val->extra_price}}">
                </div>
                @if($key=='0')
                <div class="col">
                     <input type="button" class="btn-primary add" id="add" value="Add More">
                </div>
                @else
                <div class="col"><input type="button" class="btn-danger remove" value="Remove"></div>
                @endif
            </div>
          @endforeach
            </div>
        <div class="col-sm-12" id="new"></div>
        
           
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

var count= {{$total}};

initialize(count={{$total}} - 1)
$("body").on("click", ".add", function () {
 count++;
        $("#new").append('<div class="row field"><div class="col"><input type="text" class="form-control required" id="city_'+count+'" name="city['+count+']" placeholder="City"></div><input type="hidden" name="state[]"  id="state_'+count+'" class="form-control"><input type="hidden" name="lat[]" id="lat_'+count+'"  class="form-control"><input type="hidden" name="long[]"  id="long_'+count+'" class="form-control"><div class="col"><input type="text" class="form-control required" name="price['+count+']" id="price_'+count+'" placeholder="Price"></div><div class="col"><input type="text" class="form-control required" name="extra_price['+count+']" id="extra_price_'+count+'" placeholder="Extra Price"></div><div class="col"><input type="button" class="btn-danger remove" value="Remove"></div></div>');
  
        initialize(count)
    //     function initialize() {
    //       var input =  document.getElementById('city_'+count+'');
    //       var autocomplete = new google.maps.places.Autocomplete(input);
    //       google.maps.event.addListener(autocomplete, 'place_changed', function () {
    //           var place = autocomplete.getPlace();
    //           $('#city_'+count+'').val(place.name);
    //           $('#lat_'+count+'').val(place.geometry.location.lat());
    //           $('#long_'+count+'').val(place.geometry.location.lng());
    //       });
    //   }
    //   google.maps.event.addDomListener(window, 'load', initialize);
    });


$("body").on("click", ".remove", function () {
   // $(".field").last().closest("div").remove();
   $(this).closest("div.row").remove();
    });

function initialize(count) {
   
    //console.log(count);

          var input =  document.getElementById('city_'+count+'');
          var autocomplete = new google.maps.places.Autocomplete(input);
          google.maps.event.addListener(autocomplete, 'place_changed', function () {
              var place = autocomplete.getPlace();
              $('#state_'+count+'').val(place.address_components[2].long_name);
              $('#city_'+count+'').val(place.name);
              $('#lat_'+count+'').val(place.geometry.location.lat());
              $('#long_'+count+'').val(place.geometry.location.lng());
          });
      }
      google.maps.event.addDomListener(window, 'load', initialize);

jQuery(function($) {
  $("#validate-form").validate({
    
  });
  
});
// $("#submit").click(function(){
//    $('input').each(function(){
//     $("input").prop('required',true);
   
//    })
// })


   

</script>
@endsection