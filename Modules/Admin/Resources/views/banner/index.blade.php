@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Banner List</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Banner List</li>
                    </ol>
                </nav>
                <!-- <a href="{{ url('admin/cms/add') }}" class="btn btn-primary " style="float:right;">Add Page</a> -->
            </div>
            <div class="col-md-12 text-right mt-3">
           <a href="{{ url('admin/banner/add') }}" class="btn btn-sm btn-primary" >Add Banner</a>
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
                                        <th scope="col">Sr.No</th>
                                        <th scope="col">Service Name</th>
                                        <th scope="col">Image</th>
                                        <th scop="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i=1; @endphp
                                    @forelse ($data as $val)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{ $val->service->service_name }}</td>                                         
                                            <td> 
                                            <img src="{{$val->image}}" width="100px" height="100px">
                                           </td>
                                           <td>
                                           @if($val->status == '1')
                                            <a href="javascript:void(0);" onclick="updateStatus('{{url('admin/banner/appstatus', $val->slug)}}')" class="btn btn-sm btn-success">Active</a> 
                                            @else 
                                            <a href="javascript:void(0);" onclick="updateStatus('{{url('admin/banner/appstatus', $val->slug)}}')" class="btn btn-sm btn-danger">Deactive</a> 
                                            @endif 
                                           </td>
                                            <td class="action" style="text-align:left;">
                                                <!-- <a data-toggle="tooltip" title="View" href="{{url('admin/banner/view', $val->slug)}}"><button type="button" class="icon-btn preview"><i class="fal fa-eye"></i></button></a> -->
                                                <a data-toggle="tooltip" title="Edit" href="{{url('admin/banner/edit', $val->slug)}}"><button type="button" class="icon-btn edit"><i class="fal fa-edit"></i></button></a>
                                                <button data-toggle="tooltip" title="Delete" type="button" onclick="deleteRecord('{{url('admin/banner/delete', $val->slug)}}')" class="icon-btn delete"><i class="fal fa-times"></i></button>
                                              
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                           <td colspan="5" style="text-align:center">No Banner</td>
                                        </tr>
                                    @endforelse
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
            text: "Are you sure want delete banner.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Deleted!',
                        'banner deleted.',
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
            text: "Are you sure want to change Banner status.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        
                    )
                    window.location = param
                }
        })
    }
    
    
    
</script>
@endsection