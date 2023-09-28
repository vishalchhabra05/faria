@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Add Category</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/category/category')}}">Category</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Category</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/category/store')}}" enctype="multipart/form-data">
    @csrf
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Category Name</label>
                <input class="form-control required" id="category_name" name="category_name" type="text" placeholder="Category Name" value="{{old('category_name')}}" >
                    @if ($errors->has('category_name'))
                      <strong>{{ $errors->first('category_name') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">Description</label>
            <textarea class="form-control required" id="desc" name="desc" rows="9" placeholder="Description.." spellcheck="false"></textarea>
                    @if ($errors->has('desc'))
                      <strong>{{ $errors->first('desc') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="trust">Trust & Support Fees </label>
                <input class="form-control required number" minlength="1" maxlength="2"  id="trust" name="trust" type="text" placeholder="Trust & Support Fees" value="{{old('trust')}}" >
                    @if ($errors->has('trust'))
                      <strong>{{ $errors->first('trust') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="service">Type</label>
                    <select class="form-control required" id="type" name="type">
                        <option value="">Select</option>
                        <!-- <option value="0">Free Quote</option>    -->
                        <option value="1">Free Quote</option>       
                    </select>
                </div>
                @if ($errors->has('type'))
                <strong>{{ $errors->first('type') }}</strong>
            @endif
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