@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
            <h1 class="h3 m-0">Discount Coupon</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Discount Coupon</li>
                    </ol>
                </nav>
                <!-- <a href="{{ url('admin/service/add') }}" class="btn btn-primary " style="float:right;">Add Service</a> -->
            </div>
            <div class="col-md-12 text-right mt-3">
           <a href="{{ url('admin/discount/add') }}" class="btn btn-sm btn-primary" >Add Coupon</a>
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
                                        <th scope="col">Coupon</th>
                                        <th scope="col">Discount (in amount)</th>
                                        <th scop="col">Expiry Date</th>
                                        <th scope="col" class="action" style="text-align:right;">Action</th>
                                    </tr>
                                </thead>
                               
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
    
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('admin/discount/all') }}",
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'coupon', name: 'coupon'},
            {data: 'price', name: 'price'},
            {data: 'expirey_date', name: 'expirey_date'},
            {data: 'action', name: 'action', class:'action' , orderable: false, searchable: false},
        ]
    });
    
  });
    // $(function () {
    //     $('#dataTable').DataTable();
    // });
    function deleteRecord(param) {
        console.log(param);
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure want to delete coupon.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Deleted!',
                        'Coupon Deleted.',
                        'success'
                    )
                    window.location = param
                }
        })
    }
    

    
    
</script>
@endsection 