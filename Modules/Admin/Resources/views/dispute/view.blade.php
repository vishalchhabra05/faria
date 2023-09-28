@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">View Dispute Detail</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/dispute/manager')}}">Dispute List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Dispute List</li>
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
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$data->user->first_name.' '.$data->user->last_name}}" disabled> @if ($errors->has('first_name'))
                                <strong>{{ $errors->first('first_name') }}</strong> @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Provider Name</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$data->order->user->first_name.' '.$data->order->user->last_name}}" disabled> @if ($errors->has('first_name'))
                                <strong>{{ $errors->first('first_name') }}</strong> @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Job Completion Date Time</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$data->invoice->created_at}}" disabled> @if ($errors->has('first_name'))
                                <strong>{{ $errors->first('first_name') }}</strong> @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Customer Address</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Address" value="{{$data->user->address ?? ''}}" disabled> @if ($errors->has('first_name'))
                                <strong>{{ $errors->first('first_name') }}</strong> @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Dispute Reason</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Address" value="{{$data->reason ?? ''}}" disabled> @if ($errors->has('first_name'))
                                <strong>{{ $errors->first('first_name') }}</strong> @endif
                            </div>
                        </div>
                            <?php
                            $id = $data->roles->role_id;
                            $role = $data->rolename($id);
                            ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Submitted By Customer/Provider</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="Address" value="{{($role->name=='user') ? 'Customer' : 'Provider'}}" disabled> @if ($errors->has('first_name'))
                                <strong>{{ $errors->first('first_name') }}</strong> @endif
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