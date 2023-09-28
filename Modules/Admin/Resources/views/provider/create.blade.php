@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">@if(!empty($data))Edit @else Add @endif provider</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider')}}">Add Service Provider</a></li>
                        @if(!empty($data))
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider/edit',$data->id)}}">Edit Details</a></li>
                        @endif 
                        <li class="breadcrumb-item active" aria-current="page">@if(!empty($data))Edit @else Add @endif Service Provider List</li>
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
                    @if(!empty($data))
                    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/provider/add',$data->id)}}" enctype="multipart/form-data">
                    @else
                    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/provider/add')}}" enctype="multipart/form-data">
                    @endif
                       
                            @csrf
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name">First Name</label>
                                    <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$data->first_name ?? old('first_name')}}"> @if ($errors->has('first_name'))
                                    <strong>{{ $errors->first('first_name') }}</strong> @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name">Last Name</label>
                                    <input class="form-control required" id="last_name" name="last_name" type="text" placeholder="Last Name" value="{{$data->last_name ?? old('last_name')}}"> @if ($errors->has('last_name'))
                                    <strong>{{ $errors->first('last_name') }}</strong> @endif
                                </div>
                            </div>

                            <div class="col-sm-12">
                            <div class="form-group">
                                <label for="category">Country Code</label>
                                    <select class="form-control required" id="mobile_code" name="mobile_code">
                                    <!-- <option value="+1"}}>+1</option>  -->
                                        <option value="">Select</option>
                                        @foreach($country_code as $val)
                                        @if(!empty($data->mobile_code))
                                        <option value="{{$val->phonecode}}"}} {{$val->phonecode == $data->mobile_code ? 'selected' : ''}}>{{$val->phonecode.' '.$val->name}}</option>   
                                        @else
                                        <option value="{{$val->phonecode}}"}} >{{$val->phonecode.' '.$val->name}}</option>   
                                        @endif
                                           
                                            @endforeach     
                                            
                                    </select>
                                </div>
                                @if ($errors->has('mobile_code'))
                                <strong>{{ $errors->first('mobile_code') }}</strong>
                            @endif
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name">Mobile Number</label>
                                    <input class="form-control mobile required" minlength="5" maxlength="13" id="mobile" name="mobile" type="text" placeholder="Mobile Number" value="{{$data->mobile ?? old('mobile') }}"> @if ($errors->has('mobile'))
                                    <strong>{{ $errors->first('mobile') }}</strong> @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name">Email Address</label>
                                    <input class="form-control required" id="email" name="email" type="email" placeholder="Email Address" value="{{$data->email ?? old('email')}}"> @if ($errors->has('email'))
                                    <strong>{{ $errors->first('email') }}</strong> @endif
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name">Password</label>
                                    <input class="form-control" minlength="6" maxlength="15" id="password" name="password" type="password" placeholder="Password"> @if ($errors->has('password'))
                                    <strong>{{ $errors->first('password') }}</strong> @endif
                                </div>
                            </div>

                            <div class="col-sm-12">
            <div class="form-group">
            <label for="name">Image</label>
                <div class="upload-btn">
                    <input id="image" type="file" name="image">
                    <label class="btn btn-primary btn-sm" for="image">Upload File</label>
                </div>
                @if(!empty($data->image))
              <input type="hidden" name="image" value="{{$data->image}}">
              @endif
            </div>
            </div>
            <div class="col-sm-12 mb-4">
            <ul class="gallery-box"  id="pass_preview">
                @if(!empty($data->image))
                <li><img src="{{$data->image}}" width="100px" height=""></li>
              
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



$('.mobile').keyup(function(){
    updateTotal()
})
           
            

function updateTotal() {
    var optionz = $('#mobile').val();
    console.log(optionz.length)
  
    if(optionz.length == '1'){
        var value = $('#mobile').val();
        $('#mobile').val('('+value);

    }
    if(optionz.length == '4'){
        var value = $('#mobile').val();
        $('#mobile').val(value+')');

    }

    if(optionz.length == '8'){
        var value = $('#mobile').val();
        $('#mobile').val(value+'-');

    }

            // var newTotal = 0;
            // $("#mobile").each(function() {

            //    var optionz = $(this).val();



            //    console.log(optionz.length)

            //     newTotal += optionz;
            // });
            //console.log(newTotal);
            //$("#total").text("Total: " + newTotal);
}

</script>
@endsection