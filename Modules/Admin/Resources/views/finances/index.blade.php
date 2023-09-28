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
                            <!-- <p id="date_filter">
                            <span id="date-label-from" class="date-label">From:To </span><input class="date_range_filter date" type="text" id="datepicker_from" />                          
                            </p> -->
                            <form  class="form-horizontal" action="{{url('admin/finances/manage')}}" method="get" enctype="multipart/form-data">
                        <div class="row input-daterange">
                            <div class="col-md-4">
                            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" value="{{old('from_date')}}" readonly />
                            </div>
                            <div class="col-md-4">
                            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" value="{{old('to_date')}}" readonly />
                            </div>
                            <div class="col-md-4">
                            <button type="submit" name="filter" id="filter" class="btn-primary">Filter</button>
                            <button type="button" href="javascript:void(0);" onclick="updateStatus('{{url('admin/finances/manage')}}')"  class="btn-default">Refresh</button>
                            </div>
                        </div>
                       </form>
                       
                            <table id="dataTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th scop="col">Sr.No</th>
                                        <th scop="col">Provider Name</th>
                                        <th scop="col">Total Amount</th>
                                        <th scop="col">Due Amount</th>
                                        <!-- <th scop="col">Date</th> -->
                                        <th scop="col">Status</th>
                                        <th scop="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i = 1; @endphp
                                  @foreach($data as $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$value->assineOne->user->first_name.' '.$value->assineOne->user->last_name}}</td>
                                            <td>{{totalAount($value->user_id)}}</td>
                                            <?php
                                            // $tax = ($value->order->tax / 100) * $value->sub_total;
                                            // $trust_fees = ($value->order->trust_fees / 100) * $value->sub_total;
                                            // $sum = $tax+$trust_fees;
                                            // $payable = $value->sub_total - $sum;
                                            $val = Paypable($value->user_id);
                                            $payable = totalAount($value->user_id)-$val;
                                            ?>
                                            <td>{{$payable}}</td>
                                            <!-- <td data-order="{{strtotime(\Carbon\Carbon::parse($value->created_at)->format('m/d/Y'))}}">{{\Carbon\Carbon::parse($value->created_at)->format('d M Y')}}</td> -->
                                            <td>
                                            @if($value->status == '0')
                                            <span class="badge badge-danger float-right">Payment Pending</span>
                                            @else
                                            <span class="badge badge-success float-right">Payment Success</span>
                                            @endif
                                            </td>
                                            <td class="action">
                                            <a data-toggle="tooltip" title="View" type="button" class="icon-btn preview" href="{{ url('admin/finances/listing/'.$value->user_id) }}"><i class="fal fa-eye"></i></a>
                                          
                                            <a data-toggle="tooltip" title="Pay" type="button" class="icon-btn preview" href="{{ url('admin/payment/toprovider/'.$value->user_id) }}"><i class="fa fa-cc-stripe"></i></a>                           
                                          
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
    // function deleteRecord(param) {
    //     console.log(param);
    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "Are you sure want to delete tax.",
    //         type: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, Delete it!'
    //         }).then((result) => {
    //             if (result.value) {
    //                 Swal.fire(
    //                     'Deleted!',
    //                     'tax Deleted.',
    //                     'success'
    //                 )
    //                 window.location = param
    //             }
    //     })
    // }
    

    function updateStatus(param) {
        console.log(param);
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure you refresh record.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'List!',
                        'Referse.',
                        'success'
                    )
                    window.location = param
                }
        })
    }
 
    $('#from_date').daterangepicker({
            singleDatePicker: true,
            opens: 'right',
            // minDate: new Date()
        });

        $('#to_date').daterangepicker({
            singleDatePicker: true,
            opens: 'right',
            // minDate: new Date()
        });

// // Date range filter
// minDateFilter = "";
// maxDateFilter = "";

// $.fn.dataTableExt.afnFiltering.push(
//   function(oSettings, aData, iDataIndex) {
//     if (typeof aData._date == 'undefined') {
//       aData._date = new Date(aData[0]).getTime();
//     }

//     if (minDateFilter && !isNaN(minDateFilter)) {
//       if (aData._date < minDateFilter) {
//         return false;
//       }
//     }

//     if (maxDateFilter && !isNaN(maxDateFilter)) {
//       if (aData._date > maxDateFilter) {
//         return false;
//       }
//     }

//     return true;
//   }
// );



// $(function() {
//  var table = $("#dataTable").DataTable();

//  // Date range vars
//  minDateFilter = "";
//  maxDateFilter = "";

//  $("#datepicker_from").daterangepicker();
//  $("#datepicker_from").on("apply.daterangepicker", function(ev, picker) {
//   minDateFilter = Date.parse(picker.startDate);
//   maxDateFilter = Date.parse(picker.endDate);
//   console.log(minDateFilter);
//   console.log(maxDateFilter);
//   $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
//   var date = Date.parse(data[1]);

//   if (
//    (isNaN(minDateFilter) && isNaN(maxDateFilter)) ||
//    (isNaN(minDateFilter) && date <= maxDateFilter) ||
//    (minDateFilter <= date && isNaN(maxDateFilter)) ||
//    (minDateFilter <= date && date <= maxDateFilter)
//   ) {
//    return true;
//   }
//   return false;
//  });
//  table.draw();
// }); 
  

// });
 
 



</script>
@endsection 