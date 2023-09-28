@extends('admin::layouts.master')

@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">View Service Provider</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/provider/provider')}}">Provider</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Service Provider</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-xl-12">
                <div class="card">
                    <div class="card-header">Service Provider Detail</small></div>
                    
                            <div class="card-body">
                                <div class="accordion" id="accordion" role="tablist">

        

                                <div class="card mb-0">
                                <div class="card-header" id="headingOne" role="tab">
                                <h5 class="mb-0"><a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"  class>User Detail <label style="float:right;">-</label>
                                </a></h5>
                                </div>
                                
                                <div class="collapse show" id="collapseOne" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                <div class="card-body">
                                <div class="col-sm-12">
                                <div class="form-group">
                                <label for="name">First Name</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->first_name}}" disabled>

                                </div>
                                </div>  
                                <div class="col-sm-12">
                                <div class="form-group">
                                <label for="name">Last Name</label>
                                <input class="form-control required" id="last_name" name="last_name" type="text" placeholder="Last Name" value="{{$value->last_name}}" disabled>

                                </div>
                                </div>  
                                <div class="col-sm-12">
                                <div class="form-group">
                                <label for="name">Mobile</label>
                                <input class="form-control required" id="mobile" name="mobile" type="text" placeholder="Mobile Number" value="{{$value->mobile}}" disabled >

                                </div>
                                </div>  
                                <div class="col-sm-12">
                                <div class="form-group">
                                <label for="name">Email Address</label>
                                <input class="form-control required" id="email" name="email" type="email" placeholder="Email Address" value="{{$value->email}}" disabled>

                                </div>
                                </div>  
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-0">
                                <div class="card-header" id="headingTwo" role="tab">
                                    <h5 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" >Bussiness Information <label style="float:right;">+</label></a></h5>
                                </div>
                                <div class="collapse" id="collapseTwo" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion" style="">
                                    <div class="card-body">
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Business Name</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->bussiness->business_name ?? ''}}" disable>
                                        </div>
                                        </div> 
                                     
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Business Address</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->bussiness->business_address ?? ''}}" disable>
                                        </div>

                                        </div> 
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Unit</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->bussiness->unit ?? ''}}" disable>
                                        </div>
                                        </div> 

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Website</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->bussiness->website ?? ''}}" disable>
                                        </div>
                                        </div> 

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">HST Number</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->bussiness->hst_number ?? ''}}" disable>
                                        </div>
                                        </div> 

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">RT Number</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->bussiness->rt_number ?? ''}}" disable>
                                        </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-0">
                                <div class="card-header" id="headingThree" role="tab">
                                    <h5 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree" >Service Information <label style="float:right;">+</label></a></h5>
                                </div>
                                <div class="collapse" id="collapseThree" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion" style="">
                                    <div class="card-body">
                            @if($user != '')
                                @foreach(explode(",",$value->user_service->service) as $data)
                                <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Service</label> <?php $val= GetService($data); ?>
                                        <input class="form-control required"  name="first_name" type="text" placeholder="First Name" value="{{$val->service_name ?? ''}}" disable>
                                        </div>
                                        </div> 
                                        @if($val->req_status != '0')
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                    <input class="form-control hidden required"  name="number[]" type="text" 
                                    value="<?php if(in_array($val->id, explode(",",$user->service))){
                                    $key = array_search($val->id, explode(",",$user->service));
                                    $number = explode(",",$user->number);
                                    echo $number[$key];
                                    } ?>
                                    " 
                                    placeholder="license Number" >
                                    </div>
                                    </div>
                       
                                @endif
                                    
                                 @endforeach
                                 @endif
                                </div> 
                            </div>
                        </div>


                            <div class="card mb-0">
                                <div class="card-header" id="headingfour" role="tab">
                                    <h5 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#collapsefour" aria-expanded="true" aria-controls="collapsefour" >User Insurance Detail <label style="float:right;">+</label></a></h5>
                                </div>
                                <div class="collapse" id="collapsefour" role="tabpanel" aria-labelledby="headingfour" data-parent="#accordion" style="">
                                <div class="card-body">
                              
                                <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Agent Email</label>
                                        <input class="form-control required"  name="agent_email" type="text" placeholder="First Name" value="{{$value->insurance->email ?? ''}}" disable>
                                        </div>
                                </div> 

                                <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Policy Number</label>
                                        <input class="form-control required"  name="agent_email" type="text" placeholder="First Name" value="{{$value->insurance->insurance_policy_number ?? ''}}" disable>
                                        </div>
                                </div> 

                                <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Insurance Company</label>
                                        <input class="form-control required"  name="agent_email" type="text" placeholder="First Name" value="{{$value->insurance->insurance_company ?? ''}}" disable>
                                        </div>
                                </div> 
                                
                                </div> 
                            </div>
                        </div>

                            <div class="card mb-0">
                                <div class="card-header" id="headingFive" role="tab">
                                    <h5 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="true" aria-controls="collapseFive" >User Review Link <label style="float:right;">+</label></a></h5>
                                </div>
                                <div class="collapse" id="collapseFive" role="tabpanel" aria-labelledby="headingFive" data-parent="#accordion" style="">
                                <div class="card-body">
                              
                                <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Google Review Link</label>
                                        <input class="form-control required"  name="agent_email" type="text" placeholder="First Name" value="{{$value->review->link ?? ''}}" disable>
                                        </div>
                                </div> 
                            </div>                              
                                </div> 
                            </div>
                        


                        <div class="card mb-0">
                                <div class="card-header" id="headingSix" role="tab">
                                    <h5 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix" >User Ref One <label style="float:right;">+</label></a></h5>
                                </div>
                                <div class="collapse" id="collapseSix" role="tabpanel" aria-labelledby="headingSix" data-parent="#accordion" style="">
                                    <div class="card-body">
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->firstRef->full_name ?? ''}}" disable>
                                        </div>
                                        </div> 
                                     
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Relation Ship</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->firstRef->relationship ?? ''}}" disable>
                                        </div>

                                        </div> 
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Company</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->firstRef->company ?? ''}}" disable>
                                        </div>
                                        </div> 

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Email</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->firstRef->email ?? ''}}" disable>
                                        </div>
                                        </div> 

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Code</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->firstRef->code ?? ''}}" disable>
                                        </div>
                                        </div> 

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Phone</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->firstRef->phone ?? ''}}" disable>
                                        </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>

                                <div class="card mb-0">
                                <div class="card-header" id="headingSaven" role="tab">
                                    <h5 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#collapseSaven" aria-expanded="false" aria-controls="collapseSaven" >User Ref Second <label style="float:right;">+</label></a></h5>
                                </div>
                                <div class="collapse" id="collapseSaven" role="tabpanel" aria-labelledby="headingSaven" data-parent="#accordion" style="">
                                    <div class="card-body">
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->secondRef->full_name ?? ''}}" disable>
                                        </div>
                                        </div> 
                                     
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Relation Ship</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->secondRef->relationship ?? ''}}" disable>
                                        </div>

                                        </div> 
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Company</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->secondRef->company ?? ''}}" disable>
                                        </div>
                                        </div> 

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Email</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->secondRef->email ?? ''}}" disable>
                                        </div>
                                        </div> 

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Code</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->secondRef->code ?? ''}}" disable>
                                        </div>
                                        </div> 

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Phone</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->secondRef->phone ?? ''}}" disable>
                                        </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                                

<!-- //Bank Address -->
                           <div class="card mb-0">
                                <!-- <div class="card-header" id="headingSaven" role="tab">
                                    <h5 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#collapseEt" aria-expanded="false" aria-controls="collapseEt" >User Bank Address <label style="float:right;">+</label></a></h5>
                                </div> -->
                                <div class="collapse" id="collapseEt" role="tabpanel" aria-labelledby="headingEt" data-parent="#accordion" style="">
                                    <div class="card-body">
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Bank Address</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->bank_address->company_address ?? ''}}" disable>
                                        </div>
                                        </div>

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Unit</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->bank_address->unit ?? ''}}" disable>
                                        </div>
                                        </div> 
                                      
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">City</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->bank_address->city ?? ''}}" disable>
                                        </div>
                                        </div>

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">State</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->bank_address->state ?? ''}}" disable>
                                        </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                                <!-- Company Account Info -->

                                <div class="card mb-0">
                                <div class="card-header" id="headingNin" role="tab">
                                    <h5 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#collapseNin" aria-expanded="false" aria-controls="collapseNin" >Company/Acount Information <label style="float:right;">+</label></a></h5>
                                </div>
                                <div class="collapse" id="collapseNin" role="tabpanel" aria-labelledby="headingNin" data-parent="#accordion" style="">
                                    <div class="card-body">
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">First Name</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->Account_info->first_name ?? ''}}" disable>
                                        </div>
                                        </div>

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Last Name</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->Account_info->last_name ?? ''}}" disable>
                                        </div>
                                        </div>

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">D.O.B</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->Account_info->dob ?? ''}}" disable>
                                        </div>
                                        </div>

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">SSN Number</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="SSN Number" value="{{$value->Account_info->ssn_number ?? ''}}" disable>
                                        </div>
                                        </div>

                                        <!-- <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Hst Number</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->Account_info->hst_number ?? ''}}" disable>
                                        </div>
                                        </div> -->

                                    </div>
                                </div>
                            </div>

                                <!-- Bank Info -->
                                <div class="card mb-0">
                                <!-- <div class="card-header" id="headingTen" role="tab">
                                    <h5 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#collapseTen" aria-expanded="false" aria-controls="collapseTen" >Bank Information <label style="float:right;">+</label></a></h5>
                                </div> -->
                                <div class="collapse" id="collapseTen" role="tabpanel" aria-labelledby="headingTen" data-parent="#accordion" style="">
                                    <div class="card-body">
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Tranist Number</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->Bank_info->tranist_number ?? ''}}" disable>
                                        </div>
                                        </div>

                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Institution Number</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->Bank_info->institution_number ?? ''}}" disable>
                                        </div>
                                        </div>
                                     
                                        <div class="col-sm-12">
                                        <div class="form-group">
                                        <label for="name">Account Number</label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name" value="{{$value->Bank_info->account_number ?? ''}}" disable>
                                        </div>
                                        </div>


                                    </div>
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
<script>
jQuery(function($) {
  $("#validate-form").validate({
  });
  
});
$(document).ready(function(){
    $('a').click(function(){
        var alable =  $(this).find('label').html();
        $('a label').html('+'); 
        if(alable == '+'){
            $(this).find('label').html('-'); 
        }else{
            $(this).find('label').html('+'); 
        }
    });
    
})



</script>
@endsection