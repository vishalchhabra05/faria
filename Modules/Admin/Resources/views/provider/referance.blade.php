@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">@if(!empty($detail->full_name))Edit @else Add @endif Reference</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider')}}">Provider</a></li>
                        @if(!empty($detail->full_name))
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider/edit',$id)}}">Edit Details</a></li>
                          @endif 
                        <li class="breadcrumb-item active" aria-current="page">@if(!empty($detail->full_name))Edit @else Add @endif First Reference</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/provider/step5',$id)}}" enctype="multipart/form-data">
    @csrf
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input class="form-control required" id="full_name" name="full_name" type="text" placeholder="Full Name" value="{{$detail->full_name ?? ''}}" >
                    @if ($errors->has('full_name'))
                      <strong>{{ $errors->first('full_name') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Relationship</label>
                <input class="form-control required" id="relationship" name="relationship" type="text" placeholder="relationship" value="{{$detail->relationship ?? ''}}" >
                    @if ($errors->has('relationship'))
                      <strong>{{ $errors->first('relationship') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">company</label>
                <input class="form-control required" id="company" name="company" type="text" placeholder="company" value="{{$detail->company ?? ''}}" >
                    @if ($errors->has('company'))
                      <strong>{{ $errors->first('company') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Email</label>
                <input class="form-control" id="text" name="email" type="email" placeholder="email" value="{{$detail->email ?? ''}}" >
                    @if ($errors->has('email'))
                      <strong>{{ $errors->first('email') }}</strong>
                    @endif
            </div>
        </div>  
        

            <div class="col-sm-12">
            <div class="form-group">
                <label for="category">Country Code</label>
                    <select class="form-control required" id="code" name="code">
                        <option value="">Select</option>
                        @foreach($country_code as $val)
                        @if(!empty($detail->code))
                        <option value="{{$val->phonecode}}"}} {{$val->phonecode == $detail->code ? 'selected' : ''}}>{{$val->phonecode.' '.$val->name}}</option>   
                        @else
                        <option value="{{$val->phonecode}}"}} >{{$val->phonecode.' '.$val->name}}</option>   
                        @endif
                            
                            @endforeach     
                            
                    </select>
                </div>
                @if ($errors->has('code'))
                <strong>{{ $errors->first('code') }}</strong>
            @endif
            </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Mobile Number</label>
                <input class="form-control" id="text" name="phone" type="phone" placeholder="phone" value="{{$detail->phone ?? ''}}" >
                    @if ($errors->has('phone'))
                      <strong>{{ $errors->first('phone') }}</strong>
                    @endif
            </div>
        </div> 
       
        <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">Description</label>
            <textarea class="form-control required" id="description" name="description" rows="9" placeholder="Description.." spellcheck="false">{{$detail->description ?? ''}}</textarea>
                                           @if ($errors->has('description'))
                      <strong>{{ $errors->first('description') }}</strong>
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