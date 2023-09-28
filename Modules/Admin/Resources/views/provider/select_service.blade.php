@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Select Service</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                   <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider')}}">Provider</a></li>
                        @if(!empty($value->id))
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/step1',$id)}}">Business Info</a></li>
                        @endif
                        @if(empty($value->id))
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider/edit',$id)}}">Edit Details</a></li>
                          @endif 
                        <li class="breadcrumb-item active" aria-current="page">Select Service</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/provider/step2',$id)}}" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
    <label class="col-md-3 col-form-label">Select Service</label>
    <div class="col-md-9 col-form-label">
            @foreach($service as $value)        
                <div class="form-check checkbox mb-2">
                @if($edit == "Edit")
                    <input class="form-check-input"  <?php  if($value->req_status == '0'){ echo 'data-req_status="0"'; }else{ echo 'data-req_status="1"';  } ?> id="{{$value->id}}" name="service[]" type="checkbox" value="{{$value->id}}"  {{ in_array($value->id, explode(",",$user->service)) ? "checked" : "" }} >
                    <label class="form-check-label" for="{{$value->id}}">{{$value->service_name}}</label>
                    @if($value->req_status != '0')
                        <input class="form-control hidden required"  name="number[]" type="text" 
                        value="<?php if(in_array($value->id, explode(",",$user->service))){
                            $key = array_search($value->id, explode(",",$user->service));
                            $number = explode(",",$user->number);
                            echo $number[$key];
                        } ?>" 
                        placeholder="license Number" >
                        @if ($errors->has('number'))
                            <strong>{{ $errors->first('number') }}</strong>
                        @endif
                    @endif
                @else
                    <input class="form-check-input" id="{{$value->id}}" name="service[]" type="checkbox" value="{{$value->id}}">
                    <label class="form-check-label" for="{{$value->id}}">{{$value->service_name}}</label>
                    @if($value->req_status != '0')
                        <input class="form-control hidden required"  name="number[]" type="text" value="0" placeholder="license Number" >
                        @if ($errors->has('number'))
                            <strong>{{ $errors->first('number') }}</strong>
                        @endif
                    @endif
                @endif
                    
                </div> 
                     
            @endforeach
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
$(document).ready(function(){
    $('.hidden').each(function(){
        var chech = $(this).val();
       var value = $.trim(chech);
       console.log(value.length)
        if(chech.length < 1){
            $(this).remove();
        }
    })
})
jQuery(function($) {
  $("#validate-form").validate({
  });

    $(':checkbox:checked').each(function(i){
        if($(this).is(':checked')){
            var data = $(this).attr('data-req_status');
            if(data == 0){
                $(this).parent().find('.form-check-label').after('<input class="form-control hidden"  name="number[]" type="hidden" value="0" placeholder="license Number" >')
            }
        }  
    });
});


$('input[type="checkbox"]').click(function(){
    var data = $(this).attr('data-req_status');
    if($(this).is(':checked')){
        if(data == 1){
            $(this).parent().find('input[type="text"]').addClass('required');
            $(this).parent().find('.form-check-label').after('<input class="form-control hidden"  name="number[]" type="text" value="{{old('number')}}" placeholder="license Number" >')
        }else{
            $(this).parent().find('.form-check-label').after('<input class="form-control hidden"  name="number[]" type="hidden" value="0" placeholder="license Number" >')
        }
        
    }else{
        if(data == 1){
            $(this).parent().find('input[type="text"]').removeClass('required');
        }
        $(this).parent().find('.hidden').remove();
    }
}) 
</script>
@endsection