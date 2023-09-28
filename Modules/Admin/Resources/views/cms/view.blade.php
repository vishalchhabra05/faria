@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">View Page</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/cms/cms')}}">Page List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Page</li>
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
        <div class="col-sm-12">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="form-control"  type="text" value="{{ $data->title }}" disabled>
                </div>
               
        </div>
        <div class="col-sm-12">
                <div class="form-group">
                    <label for="title">Meta Tile</label>
                    <input class="form-control"  type="text" value="{{ $data->meta_tile ?? ''}}" disabled>
                </div>
               
        </div>
        <div class="col-sm-12">
                <div class="form-group">
                    <label for="title">Meta Keywords</label>
                    <input class="form-control"  type="text" value="{{ $data->meta_keywords ?? ''}}" disabled>
                </div>
               
        </div>
            <div class="col-md-12">
                <div class="form-group">
                <label for="title">Description</label>
                <textarea id="textarea" name="body"  placeholder="body" disabled>{{ $data->body }}</textarea>
                </div>
            </div>
            
           
            <!-- <div class="col-sm-12">
                <div class="form-group">
                    <label for="title">Status</label>
                    <input class="form-control"  type="text" value="{{ $data->status == '1'? 'Active' : 'Deactive' }}" disabled>
                </div>
               
        </div>
            -->
                

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
  CKEDITOR.config.image_previewText = CKEDITOR.tools.repeat(' ',1);
        CKEDITOR.replace('body', {
           
            filebrowserUploadMethod: 'form',
        });
        </script>

@endsection