@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">@if(isset($data)) Edit @else Add @endif City</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/city/city')}}">All City</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@if(isset($data)) Edit @else Add @endif City</li>
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
                    @if(isset($data))
                    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/city/update',$data->id)}}" enctype="multipart/form-data">
                    @else
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/city/store')}}" enctype="multipart/form-data">
    @endif
    @csrf
  

        @if(isset($data))
        <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">City</label>
            <input class="form-control city required"  name="city" type="text" placeholder="City Name" value="{{$data->city}}" >
            <div class="textbox"></div>
                    @if ($errors->has('city'))
                      <strong>{{ $errors->first('city') }}</strong>
                    @endif
                    <!-- <input type="button" id="Add" value="add">
            <input type="button" id="Remove" value="Remove"> -->
            </div>
        </div>  

        <div class="col-sm-12"> 
    <div class="form-group">
                <label for="service">State</label>
                    <select class="form-control required" id="state_id" name="state_id">
                        <option value="">Select</option>
                        @foreach($state as $value)
                            <option value="{{ $value->id }}" @if($data->state_id == $value->id) selected @endif>{{ $value->name }}</option>        
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('state_id'))
                <strong>{{ $errors->first('state_id') }}</strong>
            @endif
        </div> 
        @else

        <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">City</label>
            <select class="form-control required select2" multiple="multiple" name="city[]" style="width: 100%;"></select>
                    @if ($errors->has('city'))
                      <strong>{{ $errors->first('city') }}</strong>
                    @endif
                    <!-- <input type="button" id="Add" value="add">
            <input type="button" id="Remove" value="Remove"> -->
            </div>
        </div>  
        <input type="hidden" id="counter" value="1">
        <div class="col-sm-12"> 
    <div class="form-group">
                <label for="service">State</label>
                    <select class="form-control required" id="state_id" name="state_id">
                        <option value="">Select</option>
                        @foreach($state as $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>        
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('state_id'))
                <strong>{{ $errors->first('state_id') }}</strong>
            @endif
        </div> 
    @endif
       
              
           
        <div class="card-footer">
            <button class="btn btn-sm btn-primary submit" type="submit">Submit</button>
        </div>
    </form>
           
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

    $(".select2").select2({
        tags: true,
        tokenSeparators: [',', ' '],
        placeholder: "Select City"
    });

$('#state_id').select2({});
  $("#validate-form").validate({
    
    errorPlacement: function (error, element) {
    		if(element.hasClass('select2') && element.next('.select2-container').length) {
        	error.insertAfter(element.next('.select2-container'));
        } else if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        }
        else {
            error.insertAfter(element);
        }
    }
  
});
});

$('#expirey_date').daterangepicker({
            singleDatePicker: true,
            opens: 'right',
            minDate: new Date()
        });

       
        // $("#Add").on("click", function() {
        //     var counter = parseInt($('#counter').val());  
        //         $(".textbox").append("<div ><br> <input class='form-control required city' id='test' name='city_"+counter+ "[]' type='text' placeholder='City Name'> <br></div>");  
        //   $('#counter').val( counter + 1 );
        //     });  
        //     $("#Remove").on("click", function() {  
        //         $(".textbox").children().last().remove();  
        //     });
              
            // $('form').on('submit', function(event) {
            //     $('.city').each(function() {
            //     $(this).rules("add", 
            //     {
            //     required: true,
            //     messages: {
            //         required: "This field is required",
            //     }
            //     });
            //     });
            // });
</script>
@endsection