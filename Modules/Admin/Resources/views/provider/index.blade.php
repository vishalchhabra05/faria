@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
            <h1 class="h3 m-0">Service Provider</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Service Provider</li>
                    </ol>
                </nav>
                <!-- <a href="{{ url('admin/provider/add') }}" class="btn btn-primary " style="float:right;">Add Provider</a>   -->
            </div>
            <div class="col-md-12 text-right mt-3">
           <a href="{{ url('admin/provider/add') }}" class="btn btn-sm btn-primary" >Add  Service Provider</a>
           </div>
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <table id="dataTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th scop="col">Sr.No</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Mobile Number</th>
                                       
                                       
                                        <th scope="col">Profile Status</th>
                                        <!-- <th scope="col">Update Profile Request</th> -->
                                        <th scop="col">Service Provider Status</th>
                                        <th scope="col" class="action" style="text-align:right;">Action</th>
                                        <th scop="col">Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i = 1; @endphp
                                  @foreach($user as $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$value->first_name.' '.$value->last_name}}</td>
                                            <td>{{$value->email}}</td>
                                            <td>{{$value->mobile}}</td>
                                            
                                            
                                            <td>
                                                @if($value->status == 1) 
                                                    <a href="javascript:void(0);" onclick="updateStatus('{{url('admin/user/status', $value->slug)}}')" class="btn btn-sm btn-success">Active</a> 
                                                @else 
                                                    <a href="javascript:void(0);"  onclick="updateStatus('{{url('admin/user/status', $value->slug)}}')" class="btn btn-sm btn-danger">Deactive</a> 
                                                @endif 
                                            </td>
                                            <!-- <td>
                                            @if($value->provider_status->edit_profile_status == 1)
                                              <a data-toggle="tooltip" title="Not Approve Change" onclick="profileApprove('{{url('admin/provider/profileStatus', $value->id)}}')" class="btn btn-success">Approve</a>
                                            @else
                                              <a  data-toggle="tooltip" title="Approve" onclick="profileApprove('{{url('admin/provider/profileStatus', $value->id)}}')" class="btn btn-danger">Not Approve</a>
                                            @endif
                                            </td> -->
                                            </td>
                                            <td>
                                            @if($value->provider_status->service_provide_status == 1)
                                            <a href="javascript:void(0);" onclick="updateApprove('{{url('admin/provider/status', $value->id)}}')" class="btn btn-sm btn-success">Approved</a>
                                            @else
                                            <a href="javascript:void(0);" onclick="updateApprove('{{url('admin/provider/status', $value->id)}}')" class="btn btn-sm btn-danger">Not Approved</a>
                                            @endif
                                            </td>
                                            <td class="action">                                 
                                            <a href="{{ url('admin/provider/view/'.$value->id) }}" data-toggle="tooltip" title="View" type="button" class="icon-btn preview"><i class="fal fa-eye"></i></a>
                                            <a href="javascript:void(0);" data-toggle="tooltip" title="Delete" type="button" class="icon-btn delete" onclick="deleteRecord('{{url('admin/provider/delete', $value->id)}}')"><i  class="fal fa-times"></i></a>
                                            <a data-toggle="tooltip" title="Update" type="button" class="icon-btn preview" href="{{url('admin/provider/provider/edit', $value->id)}}"><i class="fal fa-edit"></i></a>
                                            </td> @php $user_id = '1001'; @endphp
                                            <td><a href="{{ url('admin/message/message/'.$value->id) }}" data-toggle="tooltip" title="Message" type="button" class="icon-btn preview"><i class="fab fa-facebook-messenger"></i></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    
@endsection
@section('admin::custom_js')
<script type="text/javascript">
    $(function () {
        $('#dataTable').DataTable();
    });
    function deleteRecord(param) {
        console.log(param);
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure want to delete service provider.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Deleted!',
                        'service provider deleted.',
                        'success'
                    )
                    window.location = param
                }
        })
    }
    

    function updateStatus(param) {
        console.log(param);
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure want to change service provider status.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Updated!',
                        'service provider Status Updated.',
                        'success'
                    )
                    window.location = param
                }
        })
    }
    

    function updateApprove(param) {
        console.log(param);
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure want to change service provider status.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Updated!',
                        'Service provider status updated.',
                        'success'
                    )
                    window.location = param
                }
        })
    }
    

    function profileApprove(param) {
        console.log(param);
        Swal.fire({
            title: 'Are you sure?',
            text: "Are You Sure Want change the Update Profile Status.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Updated!',
                        'Service Provider Edit Profile Status Updated.',
                        'success'
                    )
                    window.location = param
                }
        })
    }
</script>
@endsection 