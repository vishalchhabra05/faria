@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Add Walkthrough</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/walkthrough/walkthrough')}}">Walkthrough</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Walkthrough</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/walkthrough/store')}}" enctype="multipart/form-data">
    @csrf
        <div class="col-sm-12">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="form-control required"  id="title" name="title" type="text" placeholder="Enter Title">
                </div>
                @if ($errors->has('title'))
                <strong>{{ $errors->first('title') }}</strong>
                @endif
        </div>

       

       

            <div class="col-md-12">
                <div class="form-group">
                <label for="title">Description</label>
                <textarea id="textarea" name="body" class="required" placeholder="body"></textarea>
                @if ($errors->has('body'))
                    <span class="error" role="alert">
                        <strong>{{ $errors->first('body') }}</strong>
                    </span>
                @endif

                </div>
            </div>

            <!-- <div class="col-md-12">
                <div class="form-group">
                    <div class="dropdown show-tick">
                        <select class="w-100 selectpicker required" name="status" data-style="select-with-transition">
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                        <div class="dropdown-menu " role="combobox">
                            <div class="inner show" role="listbox" aria-expanded="false" tabindex="-1">
                                <ul class="dropdown-menu inner show"></ul>
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('status'))
                        <span class="error" role="alert">
                            <strong>{{ $errors->first('status') }}</strong>
                        </span>
                    @endif
                </div>
            </div> -->
            <div class="card-footer">
                                <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                            </div>
                <div class="clearfix"></div>
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
<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
<script>
//   CKEDITOR.config.image_previewText = CKEDITOR.tools.repeat(' ',1);
//         CKEDITOR.replace('body', {
//             extraPlugins: 'imageuploader',
//             filebrowserUploadMethod: 'form',
//               filebrowserUploadUrl: "{{url('ckeditor/upload', ['_token' => csrf_token() ])}}",
//         });
        window.onload = function() {
		CKEDITOR.replace( 'body', {
            filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
                        filebrowserUploadMethod: 'form'
		});
	};
jQuery(function($) {
  $("#validate-form").validate({
  });
  
});
</script>
@endsection