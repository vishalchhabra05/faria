@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">@if(empty($bussiness_info->unit))Add @else Edit  @endif Business</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider')}}">Provider</a></li> 
                        @if(empty($bussiness_info->unit)) 
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/add',$id)}}">Add Provider</a></li> 
                        @endif
                        @if(!empty($bussiness_info->unit))
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider/edit',$id)}}">Edit Details</a></li>
                          @endif             
                        <li class="breadcrumb-item active" aria-current="page">@if(empty($bussiness_info->unit))Add @else Edit @endif Business</li> 
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/provider/step1',$id)}}" enctype="multipart/form-data">
    @csrf
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Business Name</label>
                <input class="form-control required" id="business_name" name="business_name" type="text" placeholder="Business Name" value="{{$bussiness_info->business_name ?? ''}}" >
                    @if ($errors->has('business_name'))
                      <strong>{{ $errors->first('business_name') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Address</label>
                <input class="form-control required" id="business_address" name="business_address" type="text" placeholder="Address" value="{{$bussiness_info->business_address ?? ''}}" >
                    @if ($errors->has('business_address'))
                      <strong>{{ $errors->first('business_address') }}</strong>
                    @endif
            </div>
        </div> 
        <input type="hidden" name="lat"  value="{{Request::old('lat')}}" id="lat" class="form-control">
        <input type="hidden" name="long" value="{{Request::old('long')}}" id="long" class="form-control">
      
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Unit</label>
                <input class="form-control required" id="unit" name="unit" type="text" placeholder="Unit" value="{{$bussiness_info->unit ?? ''}}" >
                    @if ($errors->has('unit'))
                      <strong>{{ $errors->first('unit') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Website</label>
                <input class="form-control" id="text" name="website" type="website" placeholder="website" value="{{$bussiness_info->website ?? ''}}" >
                    @if ($errors->has('website'))
                      <strong>{{ $errors->first('website') }}</strong>
                    @endif
            </div>
        </div>  
 
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">City</label>
                <input class="form-control required" id="city" name="city" type="text" placeholder="City" value="{{$bussiness_info->city ?? ''}}" >
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
                    @foreach($state as $val)
                        <option value="{{$val->name}}">{{$val->name}}</option>                               
                        @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">HST Number</label>
                <input class="form-control required" id="hst_number" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="hst_number" type="text" placeholder="HST Number" value="{{$bussiness_info->hst_number ?? ''}}" >
                    @if ($errors->has('hst_number'))
                      <strong>{{ $errors->first('hst_number') }}</strong>
                    @endif
            </div>
        </div>  

        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">RT</label>
                <input class="form-control required" id="rt_number" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="rt_number" type="text" placeholder="rt Number" value="{{$bussiness_info->rt_number ?? ''}}" >
                    @if ($errors->has('rt_number'))
                      <strong>{{ $errors->first('rt_number') }}</strong>
                    @endif
            </div>
        </div>  


        <div class="col-sm-12">
            <div class="form-group">
            <label for="name">Passport Image</label>
                <div class="upload-btn">
                    <input id="passport_photo" type="file" name="passport_photo">
                    <label class="btn btn-primary btn-sm" for="passport_photo">Upload File</label>
                </div>
                @if(!empty($data->passport_photo))
              <input type="hidden" name="passport_photo" value="{{$data->passport_photo}}">
              @endif
            </div>
            </div>

            <div class="col-sm-12 mb-4">
            <ul class="gallery-box"  id="pass_preview1">
                @if(!empty($data->passport_photo))
                <li><img src="{{$data->passport_photo}}" width="100px" height=""></li>
              
                @endif
            </ul>
            </div>

            <div class="col-sm-12">
            <div class="form-group">
            <label for="name">License Image</label>
                <div class="upload-btn">
                    <input id="license_photo" type="file" name="license_photo">
                    <label class="btn btn-primary btn-sm" for="license_photo">Upload File</label>
                </div>
                @if(!empty($data->license_photo))
              <input type="hidden" name="license_photo" value="{{$data->license_photo}}">
              @endif
            </div>
            </div>

            <div class="col-sm-12 mb-4">
            <ul class="gallery-box"  id="pass_preview2">
                @if(!empty($data->license_photo))
                <li><img src="{{$data->license_photo}}" width="100px" height=""></li>
              
                @endif
            </ul>
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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtZwEKg-PUY-0NvCsPS3JLbedqO2EFxGs&libraries=places"></script>
<script>
function initialize() {
          var input =  document.getElementById('business_address');
          var autocomplete = new google.maps.places.Autocomplete(input);
          google.maps.event.addListener(autocomplete, 'place_changed', function () {
              var place = autocomplete.getPlace();
              $('#business_address').val(place.name);
              $('#lat').val(place.geometry.location.lat());
              $('#long').val(place.geometry.location.lng());
          });
      }
      google.maps.event.addDomListener(window, 'load', initialize);


jQuery(function($) {
  $("#validate-form").validate({
  });
  
});




$("#passport_photo").change(function(){

$('#pass_preview1').html("");

var total_file=document.getElementById("passport_photo").files.length;

for(var i=0;i<total_file;i++)

{
$('#pass_preview1').append("<li data-src='images/1.jpg'><img src='"+URL.createObjectURL(event.target.files[i])+"'></li>");

}

});


$("#license_photo").change(function(){

$('#pass_preview2').html("");

var total_file=document.getElementById("license_photo").files.length;

for(var i=0;i<total_file;i++)

{
$('#pass_preview2').append("<li data-src='images/1.jpg'><img src='"+URL.createObjectURL(event.target.files[i])+"'></li>");

}

});
</script>
@endsection