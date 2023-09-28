@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
            <h1 class="h3 m-0">Dispute Job</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dispute Job</li>
                    </ol>
                </nav>
                <!-- <a href="{{ url('admin/service/add') }}" class="btn btn-primary " style="float:right;">Add Service</a> -->
            </div>
            <!-- <div class="col-md-12 text-right mt-3">
           <a href="{{ url('admin/cancel-master/add') }}" class="btn btn-sm btn-primary" >Add Hours Price</a>
           </div> -->
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
                                        <th scope="col">Job ID</th>
                                        <th scope="col">Customer Name</th>
                                        <th scop="col">Service Provider Name</th>
                                        <th scope="col" class="action" style="text-align:right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i = 1; @endphp
                                  @foreach($data as $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$value->order_id ?? ''}}</td>   
                                            <td>{{$value->user->first_name.' '.$value->user->last_name ?? ''}}</td> 
                                            <td>{{$value->order->user->first_name.' '.$value->order->user->last_name ?? ''}}</td>                              
                                            <td class="action">    
                                             <a data-toggle="tooltip" title="view" type="button" class="icon-btn preview" href="{{url('admin/dispute/view', $value->id)}}"><i class="fal fa-eye"></i></a>
                                             <a data-toggle="tooltip" title="Edit" href="{{url('admin/dispute/edit', $value->order_id)}}"><button type="button" class="icon-btn edit"><i class="fal fa-edit"></i></button></a>
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
            text: "Are you sure want to delete price.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Deleted!',
                        'price Deleted.',
                        'success'
                    )
                    window.location = param
                }
        })
    }
    

    
    
</script>
@endsection 