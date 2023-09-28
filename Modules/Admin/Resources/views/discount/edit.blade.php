@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Update Coupon</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/discount/all')}}">All Coupon</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Coupon</li>
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
    <form method="post" id="validate-form" class="form-horizontal" action="{{url('admin/discount/update',$data->slug)}}" enctype="multipart/form-data">
    @csrf
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Coupon Code</label>
                <input class="form-control required" id="coupon" name="coupon" type="text" placeholder="Coupon Code" value="{{$data->coupon ?? old('coupon')}}" >
                    @if ($errors->has('coupon'))
                      <strong>{{ $errors->first('coupon') }}</strong>
                    @endif
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">Discount (in amount)</label>
            <input class="form-control required number" minlength="1" maxlength="3" id="price" name="price" type="text" placeholder="Coupon Price" value="{{$data->price ?? old('price')}}" >
                    @if ($errors->has('price'))
                      <strong>{{ $errors->first('price') }}</strong>
                    @endif
            </div>
        </div>  
      
        <div class="col-sm-12">
            <div class="form-group">
            <label for="textarea-input">Expiry Date</label>
            <input class="form-control date required"  id="expirey_date" name="expirey_date" type="text" placeholder="Expiry Date" value="{{$data->expirey_date ?? old('expirey_date')}}" >
                    @if ($errors->has('expiry_date'))
                      <strong>{{ $errors->first('expiry_date') }}</strong>
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

$('#expirey_date').daterangepicker({
            singleDatePicker: true,
            opens: 'right',
            minDate: new Date()
        });

</script>
@endsection