@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Edit Service Provider Detail</h1>
            </div>
          
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider')}}">Provider</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Service Provider Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-sm-12 mb-12">
    <div class="fade-in">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                    <div class="row">
            <div class="col-sm-12 col-xl-12">
                <div class="card">
                    <div class="card-header"> Update Service Provider Detail</small></div>
                    <div class="card-body">
                        <div class="list-group">
                        <a class="list-group-item active" href="{{url('admin/provider/add', $slug)}}">User Detail <label style="float:right;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></label></a>
                        <a class="list-group-item list-group-item-action" href="{{url('admin/provider/step1', $slug)}}">Bussiness Information <label style="float:right;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></label></a>
                        <a class="list-group-item list-group-item-action" href="{{url('admin/provider/step2', $slug)}}">User Selected Service <label style="float:right;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></label></a>
                        <a class="list-group-item list-group-item-action" href="{{url('admin/provider/step3', $slug)}}">User Insurance Detail <label style="float:right;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></label></a>
                        <a class="list-group-item list-group-item-action" href="{{url('admin/provider/step4', $slug)}}">User Review Link <label style="float:right;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></label></a>
                        <a class="list-group-item list-group-item-action" href="{{url('admin/provider/step5', $slug)}}">User Ref One <label style="float:right;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></label></a>
                        <a class="list-group-item list-group-item-action" href="{{url('admin/provider/step6', $slug)}}">User Ref Second <label style="float:right;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></label></a>
                        <!-- <a class="list-group-item list-group-item-action" href="{{url('admin/provider/step7', $slug)}}">User Bank Address <label style="float:right;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></label></a> -->
                        <a class="list-group-item list-group-item-action" href="{{url('admin/provider/step8', $slug)}}">Company/Acount Information <label style="float:right;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></label></a>
                        <!-- <a class="list-group-item list-group-item-action" href="{{url('admin/provider/step9', $slug)}}">Bank Information <label style="float:right;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></label></a> -->
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

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