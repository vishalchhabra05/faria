@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Walkthrough</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Walkthrough</li>
                    </ol>
                </nav>
               
            </div>
            <div class="col-md-12 text-right mt-3">
           <a href="{{ url('admin/walkthrough/add') }}" class="btn btn-sm btn-primary" >Add Walkthrough</a>
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
                                        <th scope="col">Page Title</th>
                                        
                                        <th scope="col">Description</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i=1; @endphp
                                    @forelse ($data as $page)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{ $page->title }}</td>
                                          
                                            <td> 
                                             @php
                                                $string = strip_tags($page->body);
                                                if (strlen($string) > 220) {
                                                    $stringCut = substr($string, 0, 220);
                                                    $endPoint = strrpos($stringCut, ' ');
                                                    $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                                    $string.='...';
                                                    
                                                }else{
                                                    $string =   $page->body;
                                                }
                                              echo $string;                               
                                             @endphp
                                           </td>
                                           <td class="action" style="text-align:left;">      
                                                <a data-toggle="tooltip" title="Edit" href="{{url('admin/walkthrough/edit', $page->id)}}"><button type="button" class="icon-btn edit"><i class="fal fa-edit"></i></button></a>
                                                <button data-toggle="tooltip" title="Delete" type="button" onclick="deleteRecord('{{url('admin/walkthrough/delete', $page->slug)}}')" class="icon-btn delete"><i class="fal fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                           <td colspan="5" style="text-align:center">Data Not Found.</td>
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
            text: "Are you sure want to delete walk through screen.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Deleted!',
                        'walkthrough screen deleted.',
                        'success'
                    )
                    window.location = param
                }
        })
    }


    function UpdateStatus(param) {
        console.log(param);
        Swal.fire({
            title: 'Are you sure?',
            text: "Are You Sure want to Change Page Status.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Updated!',
                        'Page Status Updated.',
                        'success'
                    )
                    window.location = param
                }
        })
    }

    
    
</script>
@endsection