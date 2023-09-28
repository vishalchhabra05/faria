@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Edit Page</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/cms/cms')}}">Page List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Page</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/cms/update')}}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="slug" value="{{$data->slug}}">
        <div class="col-sm-12">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="form-control required"  id="title" name="title" type="text" placeholder="Enter Title" value="{{$data->title}}">
                </div>
                @if ($errors->has('title'))
                <strong>{{ $errors->first('title') }}</strong>
                @endif
        </div>

        <div class="col-sm-12">
                <div class="form-group">
                    <label for="title">Meta Tile</label>
                    <input class="form-control required"  id="meta_tile" name="meta_tile" type="text" placeholder="Meta Tile" value="{{$data->meta_tile}}">
                </div>
                @if ($errors->has('meta_tile'))
                <strong>{{ $errors->first('meta_tile') }}</strong>
                @endif
        </div>

        <div class="col-sm-12">
                <div class="form-group">
                    <label for="title">Meta Keywords</label>
                    <input class="form-control required"  id="meta_keywords" name="meta_keywords" type="text" placeholder="Meta Keywords" value="{{$data->meta_keywords}}">
                </div>
                @if ($errors->has('meta_keywords'))
                <strong>{{ $errors->first('meta_keywords') }}</strong>
                @endif
        </div>

            <div class="col-md-12">
                <div class="form-group">
                <label for="title">Description</label>
                <textarea id="textarea" name="body" class="required" placeholder="body">{{$data->body}}</textarea>
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
                        <option value="1" <?php if(!empty($data)){ if( $data->status == '1'){ echo 'selected'; } } ?>>Active</option>
                        <option value="0" <?php if(!empty($data)){ if( $data->status == '0'){ echo 'selected'; } } ?>>Deactive</option>
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
        <button type="submit" class="btn btn-primary pull-right">
            Save 
            <div class="ripple-container"></div>
        </button>
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