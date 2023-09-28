@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
            <h1 class="h3 m-0">Complete Jobs</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Listing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Complete Jobs</li>
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
                                        <th scop="col">Job Id</th>
                                        <th scop="col">Customer Name</th>
                                       
                                        <th scop="col">Payable Amount</th>
                                        <th scop="col">Status</th>
                                        <th scop="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i = 1; @endphp
                                  @foreach($data as $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$value->order_id}}</td>
                                            <td>{{$value->order->user->first_name.' '.$value->order->user->last_name}}</td>
                                           
                                            <?php
                                            $tax = ($value->order->tax / 100) * $value->sub_total;
                                            $trust_fees = ($value->order->trust_fees / 100) * $value->sub_total;
                                            $sum = $tax+$trust_fees;
                                            $payable = $value->sub_total - $sum;
                                            // $val = Paypable($value->user_id);
                                            // $payable = totalAount($value->user_id)-$val;
                                            ?>
                                            <td>{{$payable}}</td>
                                            <td>
                                            @if($value->status == '0')
                                            <span class="badge badge-danger float-right">Payment Pending</span>
                                            @else
                                            <span class="badge badge-success float-right">Payment Success</span>
                                            @endif
                                            </td>
                                            <td class="action">
                                            <a data-toggle="tooltip" title="View" type="button" class="icon-btn preview" href="{{ url('admin/finances/view/'.$value->id) }}"><i class="fal fa-eye"></i></a>
<!--                                           
                                            <a data-toggle="tooltip" title="Pay" type="button" class="icon-btn preview" href="{{ url('admin/payment/toprovider/'.$value->id) }}"><i class="fa fa-cc-stripe"></i></a>                            -->
                                          
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