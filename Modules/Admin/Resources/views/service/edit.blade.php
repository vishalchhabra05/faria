@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Edit Service</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/service/service')}}">Service</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Service</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/service/update',$data->slug)}}" enctype="multipart/form-data">
    @csrf
    <div class="col-sm-12">
        <div class="form-group">
            <label for="category">Category</label>
                <select class="form-control required" id="category_id" name="category_id">
                    <option value="">Select</option>
                    @foreach($category as $value)
                        <option value="{{ $value->id }}" {{($data->category_get->id == $value->id)? "selected" : ""}}>{{ $value->category_name }}</option>        
                    @endforeach
                </select>
            </div>
            @if ($errors->has('category_id'))
            <strong>{{ $errors->first('category_id') }}</strong>
        @endif
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Service Name</label>
                <input class="form-control required" id="service_name" name="service_name" type="text" placeholder="Service Name" value="{{$data->service_name}}" >
                    @if ($errors->has('service_name'))
                      <strong>{{ $errors->first('service_name') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="image">Image (600*450)</label>
                <div class="upload-btn">
                    <input class="form-control" id="image" type="file" name="image">
                    <label class="btn btn-primary btn-sm" for="image" id="image_button">Upload Image</label>
                    <label for="error1"></label>
                </div>
            </div>
        </div>
        <input type="hidden" id="update_image" name="image" value="{{$data->image}}">
        

    <div class="col-sm-12 mb-4">
            <ul class="gallery-box"  id="privew">
            <li data-src="images/3.jpg">
                <a href="">
                    <img class="img-responsive"  src="{{$data->image}}">
                </a>
            </li>
            </ul>
            </div>
        
        <div class="col-sm-12">
            <div class="form-group">
                <label for="icon">Icon</label>
                <div class="upload-btn">
                    <input class="form-control" id="icon" type="file" name="icon">
                    <label class="btn btn-primary btn-sm" for="icon" id="icon_button">Upload Icon</label>
                    <label for="error2"></label>
                    </div>
            </div>
        </div>
<input type="hidden" id="update_icon" name="icon" value="{{$data->icon}}">
       

    <div class="col-sm-12 mb-4">
            <ul class="gallery-box"  id="privew1">
            <li data-src="images/3.jpg">
                <a href="">
                    <img class="img-responsive"  src="{{$data->icon}}">
                </a>
            </li>
            </ul>
            </div>

        <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">Description (100 char)</label>
            <textarea class="form-control required" id="description" name="description" rows="9" maxlength="100" placeholder="Description (100 char)" spellcheck="false">{{$data->description ?? ''}}</textarea>
                                           @if ($errors->has('description'))
                      <strong>{{ $errors->first('description') }}</strong>
                    @endif
                   
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">Price Note (100 char)</label>
            <textarea class="form-control required" id="price_note" name="price_note" rows="9" maxlength="100" placeholder="Price Note..(100 char)" spellcheck="false">{{$data->price_note ?? ''}}</textarea>
                                           @if ($errors->has('price_note'))
                      <strong>{{ $errors->first('price_note') }}</strong>
                    @endif
            </div>
        </div> 
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Commission</label>
                <input class="form-control required number" minlength="1" maxlength="2" id="commission" name="commission" type="text" placeholder="Commission" value="{{$data->commission ?? ''}}" >
                    @if ($errors->has('commission'))
                      <strong>{{ $errors->first('commission') }}</strong>
                    @endif
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
            <label >Peak Hours</label>
            @foreach($hours as $val)
            <div class="col-md-9 col-form-label">
                <div class="form-check checkbox mb-2">
                    <input class="form-check-input" name="pickhours[]" id="{{$val->id}}" type="checkbox" value="{{$val->id}}" {{in_array($val->id,explode(",",$data->pickhours)) ? 'checked' : ''}}>
                    <label class="form-check-label" for="{{$val->id}}">{{$val->sloat}}</label>
                </div> 
            </div>
            @endforeach
      </div>
      </div>
      
      <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">Insuranse Number Required</label>
             <input type="radio" name="req_status" id="req_status1"  value="1" {{($data->req_status == '1') ? 'checked' : ''}}> Required
             </br>
             <input type="radio" name="req_status" id="req_status2" value="0" {{($data->req_status == '0') ? 'checked' : ''}} > Not Required
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

    $("form").submit(function(){
        var service_name = $("#service_name").val();
        var image = $("#update_image").val();
        var icon = $("#update_icon").val();
        var desc = $("#description").val();
        if(image.length < 1){
           $('label[for="error1"]').text("This Field Required").css('color','#ff5c75');
        }else{
            $('label[for="error1"]').text('');
        }

        if(icon.length < 1){
           $('label[for="error2"]').text("This Field Required").css('color','#ff5c75');
        }else{
            $('label[for="error2"]').text('');
        }

       
    });


//     var wordLen = 100,
// 		len; // Maximum word length
// $('#description').keydown(function(event) {	
// 	len = $('#description').val().split(/[\s]+/);
// 	if (len.length > wordLen) { 
// 		if ( event.keyCode == 46 || event.keyCode == 8 ) {// Allow backspace and delete buttons
//     } else if (event.keyCode < 48 || event.keyCode > 57 ) {//all other buttons
//     	event.preventDefault();
//     }
// 	}
// 	console.log(len.length + " words are typed out of an available " + wordLen);
// 	wordsLeft = (wordLen) - len.length;
// 	$('.error').html(wordsLeft+ ' words left');
// 	if(wordsLeft == 0) {
// 		$('.error').prepend('<i class="fa fa-exclamation-triangle"></i>');
// 	}
// });

        $('#image_button').click(function(){
            $('label[for="error1"]').text('');
        })

        $("#icon_button").click(function(){
            $('label[for="error2"]').text('');
        })


              
$("#image").change(function(){

$('#privew').html("");

var total_file=document.getElementById("image").files.length;

for(var i=0;i<total_file;i++)

{
$('#privew').append("<li data-src='images/1.jpg'><img src='"+URL.createObjectURL(event.target.files[i])+"'></li>");

}

});


$("#icon").change(function(){

$('#privew1').html("");

var total_file1=document.getElementById("icon").files.length;

for(var i=0;i<total_file1;i++)

{
    $('#privew1').append("<li data-src='images/1.jpg'><img src='"+URL.createObjectURL(event.target.files[i])+"'></li>");
}

});

</script>
@endsection