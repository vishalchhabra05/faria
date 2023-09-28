@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
            <h1 class="h3 m-0">Update Profile Request</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Profile Request</li>
                    </ol>
                </nav>
                <!-- <a href="{{ url('admin/provider/add') }}" class="btn btn-primary " style="float:right;">Add Provider</a>   -->
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
                                       
                                        <th scope="col">Update Profile Request</th>
                                        <th scope="col" class="action" style="text-align:right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i = 1; @endphp
                                  @foreach($data as $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$value->UserDetail->first_name.' '.$value->UserDetail->last_name}}</td>
                                        
                                            <td>
                                            @if($value->status == 1)
                                              <a  onclick="profileApprove('{{url('admin/provider/profileStatus', $value->id)}}')" class="btn btn-sm btn-success">Approve</a>
                                            @else
                                              <a  onclick="profileApprove('{{url('admin/provider/profileStatus', $value->id)}}')" class="btn btn-sm btn-danger">Not Approve</a>
                                            @endif
                                            </td>
                                           
                                           
                                            <td class="action">                                 
                                            <button data-toggle="tooltip" title="View" type="button" class="icon-btn preview"><a href="{{ url('admin/provider/view/'.$value->user_id) }}"><i class="fal fa-eye"></i></a></button>
                                            
                                            </td>

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
            text: "Are You Sure want to Delete User.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Deleted!',
                        'User Deleted.',
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