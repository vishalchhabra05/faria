@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Edit Variant</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/varient/varient')}}">Variant</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Variant</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/varient/update',$data->slug)}}" enctype="multipart/form-data">
    @csrf
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">variant Name</label>
                <input class="form-control required" id="name" name="name" type="text" value="{{$data->name}}" >
                    @if ($errors->has('name'))
                      <strong>{{ $errors->first('name') }}</strong>
                    @endif
            </div>
        </div>     
        <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">Price Note</label>
            <textarea class="form-control required" id="price_note" name="price_note" rows="9" placeholder="Price Note.." spellcheck="false">{{$data->price_note ?? ''}}</textarea>
                                           @if ($errors->has('price_note'))
                      <strong>{{ $errors->first('price_note') }}</strong>
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