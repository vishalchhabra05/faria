@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
            <h1 class="h3 m-0">Service</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Service</li>
                    </ol>
                </nav>
                <!-- <a href="{{ url('admin/service/add') }}" class="btn btn-primary " style="float:right;">Add Service</a> -->
            </div>
            <div class="col-md-12 text-right mt-3">
           <a href="{{ url('admin/service/add') }}" class="btn btn-sm btn-primary" >Add Service</a>
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
                                        <th scope="col">Service Name</th>
                                        <th scop="col">Category Name</th>
                                        <th scop="col">App Home Screen</th>
                                        <th scop="col">Add Price</th>
                                        <th scope="col" class="action" style="text-align:right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i = 1; @endphp
                                  @foreach($data as $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$value->service_name ?? ''}}</td>  
                                            <td>{{$value->category_get->category_name ?? ''}}</td>  
                                            <td>
                                            @if($value->appstatus == 1) 
                                                    <a href="javascript:void(0);" onclick="updateStatus('{{url('admin/service/appstatus', $value->slug)}}')" class="btn btn-sm btn-success">Active</a> 
                                                @else 
                                                    <a href="javascript:void(0);" onclick="updateStatus('{{url('admin/service/appstatus', $value->slug)}}')" class="btn btn-sm btn-danger">Deactive</a> 
                                                @endif 
                                            </td>          
                                            <td> 
                                            @if(!empty($value->priceGet))
                                            <a href="{{url('admin/service/addprice', $value->id)}}" class="btn btn-sm btn-primary">Edit Price</a>
                                            @else
                                            <a href="{{url('admin/service/addprice', $value->id)}}" class="btn btn-sm btn-success">Add Price</a> 
                                            @endif
                                            </td>                             
                                            <td class="action">    
                                            <a data-toggle="tooltip" title="View" type="button" class="icon-btn preview" href="{{ url('admin/service/view/'.$value->slug) }}"><i class="fal fa-eye"></i></a>                           
                                           <a href="javascript:void(0);" data-toggle="tooltip" title="Delete" type="button" class="icon-btn delete" onclick="deleteRecord('{{url('admin/service/delete', $value->slug)}}')"><i class="fal fa-times"></i></a>
                                           <a data-toggle="tooltip" title="Update" type="button" class="icon-btn preview" href="{{url('admin/service/edit', $value->slug)}}"><i class="fal fa-edit"></i></a>
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
            text: "Are you sure want to delete service.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Deleted!',
                        'service Deleted.',
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
            text: "Are you sure want to change service status.",
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