@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Add Banner</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/banner/list')}}">Banner List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Banner</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/banner/store')}}" enctype="multipart/form-data">
    @csrf
 

    <div class="col-sm-12">
            <div class="form-group">
                <label for="service">Service</label>
                    <select class="form-control required" id="service_id" name="service_id">
                        <option value="">Select</option>
                        @foreach($service as $value)
                            <option value="{{ $value->id }}">{{ $value->service_name }}</option>        
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('service_id'))
                <strong>{{ $errors->first('service_id') }}</strong>
            @endif
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="image">Image Size(600*300)</label>
                <div class="upload-btn">
                    <input class="form-control" id="image" type="file" name="image">
                    <label class="btn btn-primary btn-sm required" for="image" id="image_button">Upload Image</label>
                    @if ($errors->has('image'))
                      <strong>{{ $errors->first('image') }}</strong>
                    @endif
                    </div>
            </div>
        </div>
            <div class="col-sm-12 mb-4">
            <ul class="gallery-box"  id="pass_preview">
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
<script>
jQuery(function($) {
  $("#validate-form").validate({ 
  });
});

    


        
$("#image").change(function(){

$('#pass_preview').html("");

var total_file=document.getElementById("image").files.length;

for(var i=0;i<total_file;i++)

{
$('#pass_preview').append("<li data-src='images/1.jpg'><img src='"+URL.createObjectURL(event.target.files[i])+"'></li>");

}

});


$("#icon").change(function(){

$('#pass_preview1').html("");

var total_file1=document.getElementById("icon").files.length;

for(var i=0;i<total_file1;i++)

{
    $('#pass_preview1').append("<li data-src='images/1.jpg'><img src='"+URL.createObjectURL(event.target.files[i])+"'></li>");
}

});

</script>
@endsection