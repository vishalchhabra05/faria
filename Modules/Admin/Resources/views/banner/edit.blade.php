@extends('admin::layouts.master')


@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Edit Banner</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/banner/list')}}">Banner List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Banner</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/banner/update',$data->slug)}}" enctype="multipart/form-data">
    @csrf
    <div class="col-sm-12">
            <div class="form-group">
                <label for="service">Service</label>
                    <select class="form-control required" id="service_id" name="service_id">
                        <option value="">Select</option>
                        @foreach($service as $value)
                            <option value="{{ $value->id }}" {{($value->id == $data->service_id) ? 'selected' : ''}}>{{ $value->service_name }}</option>        
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
                    <input class="form-control" id="image" type="file" name="image" class="image">
                   
                    <label class="btn btn-primary btn-sm" for="image" id="image_button">Upload Image</label>
                    @if ($errors->has('image'))
                      <strong>{{ $errors->first('image') }}</strong>
                    @endif
                    </div>
            </div>
        </div>
        <input type="hidden" name="image" value="{{$data->image}}">
            <div class="col-sm-12 mb-4">
            <ul class="gallery-box"  id="pass_preview">
            <img src="{{$data->image}}" height="100px" width="120px">
            </ul>
            </div>

            <div class="col-sm-12 mb-4">
            <p><img id="previewimage" style="display:none;"/></p>
            @if(session('path'))
                <img src="{{ session('path') }}" />
            @endif
        </div>

        <div class="col-md-4 text-center">
        <div id="upload-demo"></div>
        </div>

      
    
        <div class="card-footer">
            <button class="btn btn-sm btn-primary" type="submit" id="upload-image">Submit</button>
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

   
// jQuery(function($) {
//         var p = $("#previewimage");
 
//         $("#image").change(function(){
//             alert("test");
//             var imageReader = new FileReader();
//             imageReader.readAsDataURL(document.querySelector("#image").files[0]);
 
//             imageReader.onload = function (oFREvent) {
//                 p.attr('src', oFREvent.target.result).fadeIn();
//             };
//         });
 
//         $('#previewimage').imgAreaSelect({
//             handles: true,
//     maxWidth: '1000', // this value is in pixels
//     onSelectEnd: function (img, selection) {
//         $('input[name="x1"]').val(selection.x1);
//         $('input[name="y1"]').val(selection.y1);
//         $('input[name="w"]').val(selection.width);
//         $('input[name="h"]').val(selection.height);            
//     }
// });
//     });

    
$("#image").change(function(){

$('#pass_preview').html("");

var total_file=document.getElementById("image").files.length;

for(var i=0;i<total_file;i++)

{
$('#pass_preview').append("<li data-src='images/1.jpg'><img src='"+URL.createObjectURL(event.target.files[i])+"'></li>");

}

});

// $.ajaxSetup({
// headers: {
//     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
// }
// });
 
 
// var resize = $('#upload-demo').croppie({
//     enableExif: true,
//     enableOrientation: true,    
//     viewport: { // Default { width: 100, height: 100, type: 'square' } 
//         width: 250,
//         height: 250,
//         type: 'square' //square
//     },
//     boundary: {
//         width: 350,
//         height: 350
//     }
// });
 
 
// $('#image').on('change', function () { 
//   var reader = new FileReader();
//     reader.onload = function (e) {
//       resize.croppie('bind',{
//         url: e.target.result
//       }).then(function(){
//         console.log('jQuery bind complete');
//       });
//     }
//     reader.readAsDataURL(this.files[0]);
// });
 
 
// $('#upload-image').on('click', function (ev) {
//     var service_id = $("#service_id").val();
//   resize.croppie('result', {
//     type: 'canvas',
//     size: 'viewport'
//   }).then(function (img) {
//       console.log(img);
//     $.ajax({
//       url: "{{url('admin/banner/update',$data->slug)}}",
//       type: "POST",
//       data: {"image":img,"service_id":service_id},
//       success: function (data) {
//         html = '<img src="' + img + '" />';
//         $("#preview-crop-image").html(html);
//       }
//     });
//   });
// });
 

</script>
@endsection