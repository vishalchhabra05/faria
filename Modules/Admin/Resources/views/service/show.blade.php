@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">View Service</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/service/service')}}">Service</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Service</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/user/update',$value->slug)}}" enctype="multipart/form-data">
    @csrf
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Category Name</label>
                <input class="form-control required" type="text"  value="{{$value->category_get->category_name ?? ''}}" disabled>
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Service Name</label>
                <input class="form-control required" type="text"  value="{{$value->service_name}}" disabled>
            </div>
        </div> 
        <div class="col-sm-12 mb-4">
        <label for="name">Image</label>
        <ul class="gallery-box">
            <li data-src="images/1.jpg">
                <a href="">
                    <img class="img-responsive" src="{{$value->image}}">
                </a>
            </li>
            </ul>
        </div>
        <div class="col-sm-12 mb-4">
        <label for="name">Icon</label>
        <ul class="gallery-box">
        <li data-src="images/2.jpg">
            <a href="">
                <img class="img-responsive" src="{{$value->icon}}">
            </a>
        </li>
        </ul>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">Description</label>
            <textarea class="form-control required" id="description" name="description" rows="9" placeholder="Description.." spellcheck="false" disabled>{{$value->description}}</textarea>
                                    
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">Price Note</label>
            <textarea class="form-control required" id="description" name="description" rows="9" placeholder="Price Note.." spellcheck="false" disabled>{{$value->price_note ?? ''}}</textarea>
                                    
            </div>
        </div>  
      
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Commission</label>
                <input class="form-control required" type="text"  value="{{$value->commission  ?? ''}}" disabled>
            </div>
        </div>  
 
       
        <!-- <div class="card-footer">
            <button class="btn btn-sm btn-primary" type="submit">Submit</button>
        </div> -->
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