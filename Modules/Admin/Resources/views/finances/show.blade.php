@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Complete Jobs Detail</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Complete Jobs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Complite Job Detail</li>
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
                                <label for="name">Customer Name</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$data->order->user->first_name ?? ''}}" disabled> 
                            </div>
                        </div>
                       
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Provider Name</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->assine->user->first_name ?? ''}}" disabled> 
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Job Schedule</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{\Carbon\Carbon::parse($data->schedule)->format('D,M,d,Y') ?? ''}}" disabled> 
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Job Address</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->order->address ?? ''}}" disabled> 
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Unit</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->order->unit ?? ''}}" disabled> 
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Service Name</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->order->service->service_name ?? ''}}" disabled> 
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                            <label for="textarea-input">Description</label>
                            <textarea class="form-control required" id="desc" name="desc" rows="9" placeholder="Description.." spellcheck="false">{{$data->order->desc ?? ''}}</textarea> 
                            </div>
                        </div>  
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Type</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->type ?? ''}}" disabled> 
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Regular Hours</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->regular_hours ?? ''}}" disabled> 
                            </div>
                        </div>
                      
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">After Hours</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->after_hours ?? ''}}" disabled> 
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Extra Hours Cost</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->extra_hours_cost ?? ''}}" disabled> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div 


<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Provider Bank Detail</h1>
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
                                <label for="name">Service Provider Name</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->assine->user->first_name ?? ''}}" disabled> 
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Transit Number</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->Bank_info->tranist_number ?? ''}}" disabled> 
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Institution Number</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->Bank_info->institution_number ?? ''}}" disabled> 
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Account Number</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->Bank_info->account_number ?? ''}}" disabled> 
                            </div>
                        </div>
                      
                </div>
            </div>
        </div>
    </div>
</div 

    
@endsection
@section('admin::custom_js')
<script>
jQuery(function($) {
  $("#validate-form").validate({
  });
  
});
</script>
@endsection