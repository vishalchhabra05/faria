@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Dispute Job Update</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/dispute/manager')}}">Dispute List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dispute Job Update</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/dispute/update',$data->order_id)}}" enctype="multipart/form-data">
    @csrf
   
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Type</label>
                <input class="form-control required" id="type" name="type" type="text" placeholder="Type" value="{{$data->type ?? ''}}" >
                    @if ($errors->has('type'))
                      <strong>{{ $errors->first('type') }}</strong>
                    @endif
            </div>
        </div>  
       
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Ragular Hours</label>
                <input class="form-control required" id="regular_hours" name="regular_hours" type="text" placeholder="Ragular Hours" value="{{$data->regular_hours ?? ''}}" >
                    @if ($errors->has('regular_hours'))
                      <strong>{{ $errors->first('regular_hours') }}</strong>
                    @endif
            </div>
        </div>  
 
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">After Hours</label>
                <input class="form-control required" id="after_hours" name="after_hours" type="text" placeholder="After Hours" value="{{$data->after_hours ?? ''}}" >
                    @if ($errors->has('after_hours'))
                      <strong>{{ $errors->first('after_hours') }}</strong>
                    @endif
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Extra Hours Cost</label>
                <input class="form-control required" id="extra_hours_cost" name="extra_hours_cost" type="text" placeholder="Extra Hours Cost" value="{{$data->extra_hours_cost ?? ''}}" >
                    @if ($errors->has('extra_hours_cost'))
                      <strong>{{ $errors->first('extra_hours_cost') }}</strong>
                    @endif
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Item</label>
                <input class="form-control required" id="item" name="item" type="text" placeholder="Item" value="{{$data->item ?? ''}}" >
                    @if ($errors->has('item'))
                      <strong>{{ $errors->first('item') }}</strong>
                    @endif
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Comment</label>
                <input class="form-control required" id="comment" name="comment" type="text" placeholder="Comment" value="{{$data->comment ?? ''}}" >
                    @if ($errors->has('comment'))
                      <strong>{{ $errors->first('comment') }}</strong>
                    @endif
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Sub Total</label>
                <input class="form-control required" id="sub_total" name="sub_total" type="text" placeholder="Sub Total" value="{{$data->sub_total ?? ''}}" >
                    @if ($errors->has('sub_total'))
                      <strong>{{ $errors->first('sub_total') }}</strong>
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