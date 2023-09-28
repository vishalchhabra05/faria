@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Job Detail</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Job Request</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Job Detail</li>
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
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$data->user->first_name ?? ''}}" disabled> 
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
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->address ?? ''}}" disabled> 
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Unit</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->unit ?? ''}}" disabled> 
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Service Name</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Provider Name" value="{{$data->service->service_name ?? ''}}" disabled> 
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                            <label for="textarea-input">Description</label>
                            <textarea class="form-control required" id="desc" name="desc" rows="9" placeholder="Description.." spellcheck="false">{{$data->desc ?? ''}}</textarea> 
                            </div>
                        </div>  
                       @if(!empty($data->assine->my_way_time ))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">On My Way Time</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Time" value="{{$data->assine->my_way_time ?? ''}}" disabled> 
                            </div>
                        </div>
                      @endif
                      @if(!empty($data->assine->arrived_time ))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Arrived</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Time" value="{{$data->assine->arrived_time ?? ''}}" disabled> 
                            </div>
                        </div>
                     @endif

                     @if(!empty($data->assine->complite_service_time ))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Complite Service Time</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Time" value="{{$data->assine->complite_service_time ?? ''}}" disabled> 
                            </div>
                        </div>
                     @endif

                     @if(!empty($data->assine->resume_job_time ))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Resume Job Time</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Time" value="{{$data->assine->resume_job_time ?? ''}}" disabled> 
                            </div>
                        </div>
                     @endif

                     @if(!empty($data->assine->another_visit_request ))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Another Visit Request</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Time" value="{{$data->assine->another_visit_request ?? ''}}" disabled> 
                            </div>
                        </div>
                     @endif

                     @if(!empty($data->assine->reschedule_request_time ))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Reschedule Job Time</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Time" value="{{$data->assine->reschedule_request_time ?? ''}}" disabled> 
                            </div>
                        </div>
                     @endif

                     @if(!empty($data->assine->dispute_time ))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Dispute Job Time</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Time" value="{{$data->assine->dispute_time ?? ''}}" disabled> 
                            </div>
                        </div>
                     @endif

                     @if(!empty($data->assine->cancel_job_time ))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Cancel Job Time</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Time" value="{{$data->assine->cancel_job_time ?? ''}}" disabled> 
                            </div>
                        </div>
                     @endif

                     @if(!empty($data->assine->quote_revived_time ))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Quote Recived Time</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Time" value="{{$data->assine->quote_revived_time ?? ''}}" disabled> 
                            </div>
                        </div>
                     @endif
                        
                       
                     <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Service Type</label>
                                @if($data->service->category_get->type == '1')
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Time" value="Fixed Price" disabled> 
                                 @else
                                 <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Time" value="Free Quote" disabled> 
                                 @endif
                            </div>
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