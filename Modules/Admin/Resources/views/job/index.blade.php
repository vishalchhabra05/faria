@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
            <h1 class="h3 m-0">Job Request</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Job Request</li>
                    </ol>
                </nav>
                <!-- <a href="{{ url('admin/service/add') }}" class="btn btn-primary " style="float:right;">Add Service</a> -->
            </div>
            <!-- <div class="col-md-12 text-right mt-3">
           <a href="{{ url('admin/tax/add') }}" class="btn btn-sm btn-primary" >Add Taxes</a>
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
                                        <th scop="col">Customer Name</th>
                                        <th scop="col">Service Name</th>
                                        <th scop="col">Category</th>
                                        <th scop="col">Status</th>
                                        <th scop="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i = 1; @endphp
                                  @foreach($data as $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{@$value->user->first_name.' '.@$value->user->last_name}}</td>
                                             <td>{{$value->service->service_name ?? ''}}</td> 
                                             <td>{{$value->service->category->category_name ?? ''}}</td>                       
                                            <td>
                                            @if($value->status == '1')
                                            <span class="badge badge-success float-right">In Process</span>
                                            @elseif($value->status == '2')
                                            <span class="badge badge-success float-right">Active job /on my way</span>
                                            @elseif($value->status == '3')
                                            <span class="badge badge-danger float-right">cancel Job</span>
                                            @elseif($value->status == '4')
                                            <span class="badge badge-success float-right">Complete</span>
                                            @elseif($value->status == '5')
                                            <span class="badge badge-success float-right">Dispute</span>
                                            @elseif($value->status == '6')
                                            <span class="badge badge-success float-right">Arrived on site</span>
                                            @elseif($value->status == '7')
                                            <span class="badge badge-success float-right">Reschedule job</span>
                                            @elseif($value->status == '8')
                                            <span class="badge badge-success float-right">Need another visit</span>
                                            @elseif($value->status == '9')
                                            <span class="badge badge-success float-right">Resume job</span>
                                            @elseif($value->status == '10')
                                            <span class="badge badge-success float-right">Quote Send</span>
                                            @else
                                            <span class="badge badge-success float-right">Pending</span>
                                            @endif
                                            </td>
                                            <td class="action">
                                            <a data-toggle="tooltip" title="View" type="button" class="icon-btn preview" href="{{ url('admin/job/view/'.$value->id) }}"><i class="fal fa-eye"></i></a>
                                           @if($value->status == '2' || $value->status == '7')
                                            <a data-toggle="tooltip" title="Track" type="button" class="icon-btn preview" href="{{ url('admin/track/user/'.$value->id) }}"><i class="fas fa-map"></i></a>  
                                           @else
                                            <a data-toggle="tooltip" title="Data Not Found" type="button" class="icon-btn preview" href="#"><i class="fas fa-map"></i></a>                                 
                                           @endif
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
            text: "Are you sure want to delete tax.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Deleted!',
                        'tax Deleted.',
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
            text: "Are you sure you accept this job.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Updated!',
                        'Job accept.',
                        'success'
                    )
                    window.location = param
                }
        })
    }
    
    jQuery(function($) {
  $("#validate-form").validate({
  });
  
});
    
</script>
@endsection 