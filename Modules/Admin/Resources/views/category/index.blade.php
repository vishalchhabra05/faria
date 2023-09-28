@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
            <h1 class="h3 m-0">Category List</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Category List</li>
                    </ol>
                </nav>
               
            </div>
            <div class="col-md-12 text-right mt-3">
           <a href="{{ url('admin/category/add') }}" class="btn btn-sm btn-primary" >Add Category</a>
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
                                        <th scope="col">Category Name</th>
                                       <th scop="col">Trust & Support Fees</th>
                                       <th scop="col">Type</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="action" style="text-align:right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i = 1; @endphp
                                  @foreach($data as $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$value->category_name}}</td>                                           
                                            <td>{{$value->trust ?? ''}}</td>
                                            @if($value->type == 0)
                                            <td>Free Quote</td>
                                            @else
                                            <td>Free Quote</td>
                                            @endif
                                           
                                            <td>
                                                @if($value->status == 1) 
                                                    <a href="javascript:void(0);" onclick="updateStatus('{{url('admin/category/status', $value->slug)}}')" class="btn btn-sm btn-success">Active</a> 
                                                @else 
                                                    <a href="javascript:void(0);" onclick="updateStatus('{{url('admin/category/status', $value->slug)}}')" class="btn btn-sm btn-danger">Deactive</a> 
                                                @endif 
                                            </td>
                                            <td class="action">                                 
                                           <!-- <a href="javascript:void(0);" data-toggle="tooltip" title="Delete" type="button" class="icon-btn delete" onclick="deleteRecord('{{url('admin/category/delete', $value->slug)}}')"><i  class="fal fa-times"></i></a> -->
                                            <a data-toggle="tooltip" title="Update" type="button" class="icon-btn preview" href="{{url('admin/category/edit', $value->slug)}}"><i class="fal fa-edit"></i></a>
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
            text: "Are you sure want to delete category.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Deleted!',
                        'Category Deleted.',
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
            text: "Are you sure want to change the category status.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Updated!',
                        'category Status Updated.',
                        'success'
                    )
                    window.location = param
                }
        })
    }
    
</script>
@endsection 