<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\User;
use App\RoleUser;
use Validator;  
use DateTime;
use Mail;
use Hash;
use URL;
use DB;
use App\UserAddress;
use App\OrderImage;
use App\Walkthrough;
use App\Reason;
use App\OrderQuotation;
use App\Page;
use App\ServiceProviderRequest;
use App\LoginData;
use App\StripeCard;
use App\ProviderPhoto;
use App\BusinessInformation;
use App\Category;
use App\Service;
use App\ProfessionalService;
use App\ServiceCertificate;
use App\Reference;
use App\SecondReference;
use App\ReviewLink;
use App\ProfessionalDetail;
use App\AccountInformation;
use App\BankingInfo;
use App\AssignOrder;
use App\Order;
use App\AnotherVisit;
use App\Invoice;
use App\CancelJob;
use App\Review;
use App\QuotePrice;
use App\CancelPriceMaster;
use App\Reschedule;
use App\Province;
use Carbon\Carbon;
use App\Notification;
use App\Discount;
use Stripe;
use Log;

class ProviderController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'loginFrom' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' =>  'This loginFrom is required.',
            ]);
        }
        if($request->loginFrom == 'provider'){
            $validator1 = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password'=> 'required',
                'device_token'=> 'required',
            ]);
            if ($validator1->fails()) {
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => $validator1->messages()->first(),
                ]);
            }    
        }
        try{
            if($request->loginFrom == 'provider'){
                if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
                    $user = Auth::user(); 
                    if($user->status != 1){
                        return response()->json([
                            'status_code' => 401,
                            'response' => 'error',
                            'message' => 'Your account is temporarily inactive. Please contact Faria Support.'
                        ]);    
                    }
                    // else if($user->approve == 0){
                    //     return response()->json([
                    //         'status_code' => 401,
                    //         'response' => 'error',
                    //         'message' => 'Your account is not approve. Please contact Faria Support.'
                    //     ]);
                    // }
                    else{
                        if($user->otp_verify == 0){
                            $randomid = mt_rand(100000,999999); 
                            $input['otp'] = $randomid;
                            $input['email'] = $request->email;
                            $input['device_token'] = $request->device_token;
                            $input['otp_verify'] = 0;
                            User::where('email',$request->email)->update($input); 
                            //otp send 
                            $body = [
                                'otp' => $randomid,  
                                'email' => $input['email'],                 
                            ];
                            Mail::send('emails.resendotp', $body, function ($message) use ($input)  {        
                                $message->from(env('MAIL_FROM_ADDRESS'), 'Faria Application');        
                                $message->to($input['email']);        
                                $message->subject('please verify your OTP.');        
                            });
                            $user->otp = $randomid;
                            $user->address = $user->getaddress->address ?? '';
                            $user->lati = $user->getaddress->lat ?? '';
                            $user->longti = $user->getaddress->long ?? '';
                            $user->state = $user->getaddress->state ?? '';
                            $user->mobile_code = '+'.$user->mobile_code;
                            $user->address = $user->bussiness->business_address ?? '';
                            $user->unit = $user->bussiness->unit ?? '';
                            $user->website = $user->bussiness->website ?? '';
                            $user->business_address = $user->bussiness->business_address ?? '';
                            $input['device_token'] = $request->device_token;
                            User::where('email',$request->email)->update($input); 
                            $sendSmsUser = User::where('email',$request->email)->first();
                            $countryCode = $sendSmsUser->mobile_code;
                            $mobileNumber = preg_replace('/\D+/', '', $sendSmsUser->mobile); 
                            $sendingNumber = $countryCode.$mobileNumber;
                            //sendMessage('Your OTP is '.$randomid, $sendingNumber);
                            $user['token'] =  $user->createToken('token')->accessToken; 
                            foreach ($user->toArray() as $key => $value) {
                                if($value == null){
                                    $user[$key] = '';
                                }  
                            }
                            unset($user->bussiness);
                            return response()->json([
                                'status_code' => 403,
                                'response' => 'error',
                                'message' => 'please verify your email address.',
                                'data' => $user
                            ]);
                        }
                    }
                }else{ 
                    return response()->json([
                        'status_code' => 500,
                        'response' => 'error',
                        'message' => 'Please Login with Valid Details'
                    ]);
                }
            }
         
            //check role
            if($user->roles[0]->id == '2'){
                $input['device_token'] = $request->device_token;
                User::where('email',$request->email)->update($input); 
                $user['token'] =  $user->createToken('token')->accessToken; 
                       
                unset($user['roles']);
                $user->address = $user->getaddress->address ?? '';
                $user->lati = $user->getaddress->lat ?? '';
                $user->longti = $user->getaddress->long ?? '';
                $user->state = $user->getaddress->state ?? '';
                $user->mobile_code = '+'.$user->mobile_code;
                $user->address = $user->bussiness->business_address ?? '';
                $user->unit = $user->bussiness->unit ?? '';
                $user->website = $user->bussiness->website ?? '';
                unset($user['bussiness']);
                foreach ($user->toArray() as $key => $value) {
                    if($value == null){
                         $user[$key] = '';
                    }
                }
                unset($user['getaddress']);
                $login_data = LoginData::where('user_id',$user->id)->first();
                if(empty($login_data)){
                   LoginData::create(['user_id'=>$user->id,'date'=>date('Y-m-d'),'time'=>date('H:m:s')]);
                }else{
                    LoginData::where('user_id',$user->id)->update(['user_id'=>$user->id,'date'=>date('Y-m-d'),'time'=>date('H:m:s')]);
                }
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'data' => $user
                ]);
            }else{
                return response()->json([
                    'status_code' => 201,
                    'response' => 'error',
                    'message' => 'your account type is Customer.'
                ]);
            }
        }
        catch(Exception $e){
            $data = ['error_type'=>'Login Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    //All Step data Get using pass step1 to step9
    public function checkStep(Request $request){
        $validator = Validator::make($request->all(), [ 
            'step' => 'required',
            'user_id' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try{
          if($request->step == 'step1'){
              $data = ProviderPhoto::where('user_id',$request->user_id)->first();
              
          }
          else if($request->step == 'step2'){
              $data = BusinessInformation::where('user_id',$request->user_id)->first();
          }
          else if($request->step == 'step3'){
            $data = ProfessionalService::where('user_id',$request->user_id)->first();
          }
          else if($request->step == 'step4'){
            $data = Reference::where('user_id',$request->user_id)->first();
          
          }
          else if($request->step == 'step5'){
            $data = SecondReference::where('user_id',$request->user_id)->first();
          }
          else if($request->step == 'step6'){
            $data = ReviewLink::where('user_id',$request->user_id)->first();
          }
          else if($request->step == 'step7'){
              $data = ProfessionalDetail::where('user_id',$request->user_id)->first();
          }
          else if($request->step == 'step8'){
              $data = AccountInformation::where('user_id',$request->user_id)->first();
                  $data->business_address = $data->addressget->business_address ?? '';
                  $data->unit = $data->addressget->unit ?? '';
                  $data->city = $data->addressget->city ?? '';
                  $data->state = $data->addressget->state ?? '';
              
          }
          else{
              $data = [];
          }

        //   if($data != null){
        //     // foreach($data->toArray() as $key => $val){
        //     //     if($val == null){
        //     //         $data[$key] = '';
        //     //     }
        //     //   }
        //     $data = $data;
        //  }else{
        //      $data = [];
        //  }
         

          return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'success',
            'data' => $data
        ]);
         
        }catch(\Exception $e){
         
            $data = ['error_type'=>'Step get data',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }


    public function AllStep(){
        try{
                $step1 = ProviderPhoto::where('user_id',auth()->user()->id)->first();
                $step2 = BusinessInformation::where('user_id',auth()->user()->id)->first();
                $step3 = ProfessionalService::where('user_id',auth()->user()->id)->first();
                if(empty($step3)){
                    $data['service'] = [];
                }else{
                    $service = ProfessionalService::where('user_id',auth()->user()->id)->first();
                    $servicies = (explode(",",$service->service));
                    $insurence = (explode(",",$service->number));
                    $data['service'] = array();
                    foreach($servicies as $key => $val){
                        $name = Service::where('id',$val)->first();
                        if($name){
                            $data['service'][$key]['service_name'] = $name->service_name ?? '';
                            $data['service'][$key]['insurence_number'] = $insurence[$key] ?? '';
                            $data['service'][$key]['service_id'] = $val ?? '';
                        }else{
                            $data['service'] = [];
                        }
                    }  
                }
                $step4 = Reference::where('user_id',auth()->user()->id)->first();
                $step5 = SecondReference::where('user_id',auth()->user()->id)->first();
                $step6 = ReviewLink::where('user_id',auth()->user()->id)->first();
                $step7 = ProfessionalDetail::where('user_id',auth()->user()->id)->first();
                $step8 = AccountInformation::where('user_id',auth()->user()->id)->first();
                $step9 = BankingInfo::where('user_id',auth()->user()->id)->first();
                if(empty($step1)){
                    $step1 = new \stdClass();
                }else{
                    $step1->license_photo = $step1->license_photo ?? '';
                    $step1->passport_photo = $step1->passport_photo ?? '';
                       //$step1 = $step1;
                }
                if(empty($step2)){
                    $step2 = new \stdClass();
                }else{
                    foreach($step2->toArray() as $key => $val){
                        if($val == null){
                            $step2[$key] = '';
                        }
                    }
                        //$step2->website = $step2->website ?? '';
                }
                
                    // if(empty($step3)){
                    //     $step3 = new \stdClass();
                    // }else{
                      
                    //     $servicies = (explode(",",$step3->service));
                    //     $insurence = (explode(",",$step3->number));
                       
                    //     $step3['service'] = array();
                    //     foreach($servicies as $key => $val){
                    //        $name = Service::where('id',$val)->first();
                          
                    //         if($name){
                    //         $step3['service'][$key]['service_name'] = $name->service_name ?? '';
                    //         }else{
                    //         $step3['service'][$key]['service_name'] = "";
                    //         }
                    //         $step3['service'][$key]['insurence_number'] = $insurence[$key];
                    //         $step3['service'][$key]['service_id'] = $val;
                    //     } 
                    //     return $step3['service']; 
                    // }
                
                    if(empty($step4)){
                        $step4 = new \stdClass();
                    }else{
                        foreach($step4->toArray() as $key => $val){
                            if($val == null){
                                $step4[$key] = '';
                            }
                          }
                        //$step4 = $step4;
                    }
               
                    if(empty($step5)){
                        $step5 = new \stdClass();
                    }else{
                        foreach($step5->toArray() as $key => $val){
                            if($val == null){
                                $step5[$key] = '';
                            }
                          }
                        //$step5 = $step5;
                    }
                   
                    if(empty($step6)){
                        $step6 = new \stdClass();
                    }else{
                        foreach($step6->toArray() as $key => $val){
                            if($val == null){
                                $step6[$key] = '';
                            }
                          }
                        //$step6 = $step6;
                    }
                   
                    if(empty($step7)){
                        $step7 = new \stdClass();
                    }else{
                        foreach($step7->toArray() as $key => $val){
                            if($val == null){
                                $step7[$key] = '';
                            }
                          }
                        //$step7 = $step7;
                    }
                    
                    if(empty($step8)){
                        $step8 = new \stdClass();
                    }else{
                       $step8->dob = Carbon::parse($step8->dob)->format('Y/m/d');
                        $step8->business_address = $step8->addressget->business_address ?? '';
                        $step8->unit = $step8->addressget->unit ?? '';
                        $step8->city = $step8->addressget->city ?? '';
                        $step8->state = $step8->addressget->state ?? '';
                        $step8->postal_code = $step8->addressget->postal_code ?? '';
                    }
                   
                    if(empty($step9)){
                        $step9 = new \stdClass();
                    }else{
                        $step9 = $step9;
                    }
                   
                    
               
          $data= ['step1' => $step1,
                   'step2' => $step2,
                   'step3' => $data['service'],
                   'step4' => $step4,
                   'step5' => $step5,
                   'step6' => $step6,
                   'step7' => $step7,
                   'step8' => $step8,
                   'step9'  => $step9];
            
                   return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'message' => 'success',
                    'data' => $data
                ]);
        }catch(\Exception $e){
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

    //step first passport and licence photo save
    public function step1(Request $request){
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required',
            // 'license_photo' => 'required',
            // 'passport_photo' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
      try{
        $input = $request->all();  
            if($request->license_photo =='' && $request->passport_photo==''){
                return response()->json([
                    'status_code' => 401,
                    'response' => 'error',
                    'message' => 'Image is Required',
                ]); 
            }       
        if($request->hasFile('license_photo')) {
            $avatar = $request->file('license_photo');
            $avatarName = 'license_photo'.time().'.'.$avatar->getClientOriginalExtension();
            $avatar->move('storage/user/',$avatarName);
            $path = url('/storage/user/'.$avatarName);
            $input['license_photo'] = $path;
        }

        if($request->hasFile('passport_photo')) {
            $avatar = $request->file('passport_photo');
            $avatarName = 'passport_photo'.time().'.'.$avatar->getClientOriginalExtension();
            $avatar->storeAs('public/user/',$avatarName);
            $path = url('/storage/user/'.$avatarName);
            $input['passport_photo'] = $path;
        }
        $input['user_id'] = $request->user_id;
          $data = ProviderPhoto::where('user_id',$request->user_id)->first();
          if(!empty($data)){
             ProviderPhoto::where('user_id',$request->user_id)->update($input); 
          }else{
              ProviderPhoto::create($input);
          }
         // User::where('id',auth()->user()->id)->update(['step'=>'step1']);
          return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'Photo ID upload successfully'
        ]);
      }catch(\Exception $e){
        $data = ['error_type'=>'Step First time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => 'something wrong !'
        ]);
      }
    }

    //secon step business information save
    public function step2(Request $request){
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required',
            'business_name' => 'required',
            'business_address' => 'required',
            //'unit' => 'required',
            'city' => 'required',
            'state' => 'required',
            'lat' => 'required',
            'long' => 'required',
            //'rt_number' => 'required',
            'hst_number' => 'required',
            //'postal_code' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
      try{
        $input = $request->all();
        $input['user_id'] = $request->user_id;
        $data = BusinessInformation::where('user_id',$request->user_id)->first();
        if(!empty($data)){
            BusinessInformation::where('user_id',$request->user_id)->update($input); 
        }else{
            BusinessInformation::create($input);
        }
        User::where('id',$request->user_id)->update(['lat'=>$request->lat,'long'=>$request->long]);
       // User::where('id',auth()->user()->id)->update(['step'=>'step2']);
        return response()->json([
          'status_code' => 200,
          'response' => 'success',
          'message' => 'Business detail save successfully'
      ]);
      }catch(\Exception $e){
        $data = ['error_type'=>'Step First time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => 'something wrong !'
        ]);
      }
    }

    //service list get category wise
    public function serviceList(){
        try{
            $service = array();
            $data = Category::where('status','1')->get();
            foreach($data as $val){
            $service[] = Service::where('category_id',$val->id)->where('appstatus',1)->get();
        }
        foreach($service as $val){
            foreach($val as $v){
                $v->title = $v->category_get->category_name ?? '';
                unset($v->category_get);
            }
        }
        //   return json_decode(json_encode($service), true); 
        return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'success',
            'data'  => $service
        ]); 
        }catch(\Exception $e){
            //dd($e);
            $data = ['error_type'=>'service list get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]); 
        }
    }

    //third step selected service save 
    public function step3(Request $request){
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required',
            'service' => 'required',
            'number' => 'required'   
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
       try{

        $service = explode (",", $request->service);
        $totalService = count($service);

        if($totalService < 1 || $totalService > 5){
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'Service can be selected minimum 1 OR maximum 5'
            ]);
        }

        $input = $request->all();
        $input['user_id'] = $request->user_id;

        $service = ServiceProviderRequest::where('user_id',$request->user_id)->first();
        if(!empty($service)){
            $service->edit_profile_status = 1;
            $service->service_provide_status = 0;
            $service->save();
        }

        $data = ProfessionalService::where('user_id',$request->user_id)->first();
        if(!empty($data)){
            $data = ProfessionalService::where('user_id',$request->user_id)->first(); 
            $data->user_id = $request->user_id;
            $data->service = $request->service;
            $data->number = $request->number;
            $data->save();
        }else{
            $data = new ProfessionalService;
            $data->user_id = $request->user_id;
            $data->service = $request->service;
            $data->number = $request->number;
            $data->save();
        }
        if($request->hasFile('image')) {
            $files = $request->file('image'); 
            foreach ($files as $key => $value) {
               $certificateData = ServiceCertificate::where('user_id', $request->user_id)->where('service_id',$key)->first();
               if(!empty($certificateData)){
                    $avatar = $value;
                    $avatarName = 'image'.time().'.'.$avatar->getClientOriginalExtension();
                    $avatar->move('storage/images/',$avatarName);
                    $path = url('/storage/images/'.$avatarName);
                    $certificateData->certificate = $path;
                    $certificateData->save();
               } else {
                    $certificateData = new ServiceCertificate;
                    $certificateData->user_id = $request->user_id;
                    $certificateData->service_id = $key;
                    $avatar = $value;
                    $avatarName = 'image'.time().'.'.$avatar->getClientOriginalExtension();
                    $avatar->move('storage/images/',$avatarName);
                    $path = url('/storage/images/'.$avatarName);
                    $certificateData->certificate = $path;
                    $certificateData->save();
               }
            }
        }
        return response()->json([
          'status_code' => 200,
          'response' => 'success',
          'message' => 'service save successfully'
        ]);
       }catch(\Exception $e){
        $data = ['error_type'=>'Step 3 Save time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => $e->getLine().' - '.$e->getMessage()
        ]);
       }
    }

    //four step referance one save
    public function step4(Request $request){
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required',
            'full_name' => 'required',
            'relationship' => 'required',
            'company' => 'required', 
            'email' => 'required|email',
            'phone' => 'required'  
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try{
            $input = $request->all();
            $input['user_id'] = $request->user_id;
            $data = Reference::where('user_id',$request->user_id)->first();
            if(!empty($data)){
                Reference::where('user_id',$request->user_id)->update($input); 
            }else{
                Reference::create($input);
            }
            //User::where('id',auth()->user()->id)->update(['step'=>'step4']);
            return response()->json([
              'status_code' => 200,
              'response' => 'success',
              'message' => 'First Reference Added Success.'
            ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'Step For time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

    //five step referance second save
    public function step5(Request $request){
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required',
            'full_name' => 'required',
            'relationship' => 'required',
            'company' => 'required', 
            'email' => 'required',
            'phone' => 'required', 
            'description' => 'required'   
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try{
            $input = $request->all();
            $input['user_id'] = $request->user_id;
            $data = SecondReference::where('user_id',$request->user_id)->first();
            if(!empty($data)){
                SecondReference::where('user_id',$request->user_id)->update($input); 
            }else{
                SecondReference::create($input);
            }
            //User::where('id',auth()->user()->id)->update(['step'=>'step5']);
            return response()->json([
              'status_code' => 200,
              'response' => 'success',
              'message' => 'Reference Second Added Success.'
          ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'Step Five time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

    //Six Google review link add
    public function step6(Request $request){
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'link' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
            $input = $request->all();
            $input['user_id'] = $request->user_id;
            $data = ReviewLink::where('user_id',$request->user_id)->first();
            if(!empty($data)){
               $data= ReviewLink::where('user_id',$request->user_id)->update($input); 
            }else{
                ReviewLink::create($input);
            }
            //User::where('id',auth()->user()->id)->update(['step'=>'step6']);
            return response()->json([
              'status_code' => 200,
              'response' => 'success',
              'message' => 'Link Added Success.'
          ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'Step Six time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

    //saven step insurance details add
    public function step7(Request $request){
        $validator = Validator::make($request->all(), [ 
            //'insurance_policy_number' => 'required',
            'insurance_company' => 'required',
            'email' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try{
            $input = $request->all();
            $input['user_id'] = $request->user_id;
            $data = ProfessionalDetail::where('user_id',$request->user_id)->first();
            if(!empty($data)){              
               $data= ProfessionalDetail::where('user_id',$request->user_id)->update($input); 
            }else{
                ProfessionalDetail::create($input);
            }
            //User::where('id',auth()->user()->id)->update(['step'=>'step7']);
            return response()->json([
              'status_code' => 200,
              'response' => 'success',
              'message' => 'Insurance Detail Added Successfully.'
          ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'Step Saven time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

    //step 8 save account holder detail
    public function step8(Request $request){
          $validator = Validator::make($request->all(), [ 
              'first_name' => 'required',
              'last_name' => 'required',
              'dob' => 'required',
              'ssn_number' => 'required',
              'user_id' => 'required',
              'business_address' => 'required',
               //'unit' => 'required',
               'city' => 'required',
               'state' => 'required',
          ]);
          if ($validator->fails()) {
              return response()->json([
                  'status_code' => 401,
                  'response' => 'error',
                  'message' => $validator->messages()->first(),
              ]);  
          }
          try{
              $input = $request->all();
             // $input['user_id'] = auth()->user()->id;
              $input1 = ['user_id' => $request->user_id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'dob' => $request->dob,
                        'ssn_number' => $request->ssn_number];
            $input2 = ['user_id' => $request->user_id,
                        'business_address' => $request->business_address,
                        'unit' => $request->unit,
                        'city' => $request->city,
                        'state' => $request->state,
                        'postal_code' => $request->postal_code];
              $data = AccountInformation::where('user_id',$request->user_id)->first();
              if(!empty($data)){
                 $data= AccountInformation::where('user_id',$request->user_id)->update($input1); 
              }else{
                  AccountInformation::create($input1);
              }

              $data1 = BusinessInformation::where('user_id',$request->user_id)->first();
       
                if(!empty($data1)){
                    BusinessInformation::where('user_id',$request->user_id)->update($input2); 
                }else{
                    BusinessInformation::create($input2);
                }
              //User::where('id',auth()->user()->id)->update(['step'=>'step8']);
              return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'Information Added Success.'
            ]);
        }catch(\Exception $e){
          $data = ['error_type'=>'Step 8 time',
          'error_message'=>$e->getMessage(),
          'error_ref'=>$e->getFile(),
          'which_side' => 'App'];
          errorAdd($data);
          return response()->json([
              'status_code' => 500,
              'response' => 'error',
              'message' => 'something wrong !'
          ]);
        }
    }

    //step 9 Banking Detail
    public function step9(Request $request){
        $validator = Validator::make($request->all(), [ 
            'tranist_number' => 'required',
            'institution_number' => 'required',
            'account_number' => 'required',
            'user_id'  => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
      try{
        $input = $request->all();
        $input['user_id'] = $request->user_id;
        $data = BankingInfo::where('user_id',$request->user_id)->first();
        if(!empty($data)){
           $data= BankingInfo::where('user_id',$request->user_id)->update($input); 
        }else{
            BankingInfo::create($input);
            // $RoleUser = [
            //     'role_id' => 2,
            //     'user_id' => auth()->user()->id
            // ]; 
            // RoleUser::create($RoleUser);
        }
        //User::where('id',auth()->user()->id)->update(['step'=>'step9']);
        return response()->json([
          'status_code' => 200,
          'response' => 'success',
          'message' => 'Bank Information Added Success.'
      ]);
      }catch(\Exception $e){
        $data = ['error_type'=>'Step 9 time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => 'something wrong !'
        ]);
      }
    }

    //Bussiness info-confirm detail get
    public function confirmDetail(){
        try{          
            $value = BusinessInformation::where('user_id',auth()->user()->id)->first();
            $service = ProfessionalService::where('user_id',auth()->user()->id)->first();
            $servicies = (explode(",",$service->service));
            $insurence = (explode(",",$service->number));
            $data['service'] = array();
            foreach($servicies as $key => $val){
                $name = Service::where('id',$val)->first();
                if($name){
                    $data['service'][$key]['service_name'] = $name->service_name;
                }else{
                    $data['service'][$key]['service_name'] = "";
                }
                $data['service'][$key]['insurence_number'] = $insurence[$key];
                $data['service'][$key]['service_id'] = $val;
            }  
            $review_link = ReviewLink::where('user_id',auth()->user()->id)->first();
            $ref = Reference::where('user_id',auth()->user()->id)->first();
            $ref2 = SecondReference::where('user_id',auth()->user()->id)->first();
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'success',
                'business_info'  => $value,
                "service" => $data['service'],
                "review_link" => $review_link,
                "referance"  => $ref,
                "referance_second" => $ref2
            ]);
        }catch(\Exception $e){
          
            $data = ['error_type'=>'Step confirm get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

    //direct deposit- confirm details
    public function depostDetail(){
        try{
            //Account Holder Detail
          $value = AccountInformation::where('user_id',auth()->user()->id)->first();
          $value->business_address = $value->addressget->business_address ?? '';
          $value->unit = $value->addressget->unit ?? '';
          $value->city = $value->addressget->city ?? '';
          $value->state = $value->addressget->state ?? '';
           //Banking Detail
          $bank = BankingInfo::where('user_id',auth()->user()->id)->first();
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'success',
                'account_info'  => $value,
                'bank_info' => $bank
            ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'Step Deposit Confirm Detail get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }
    

    //Service Request get panding request
    public function ServiceRequest(){
        try{
            $order = Order::select('id')->where('status','0')->get();
             $data = AssignOrder::where('user_id',auth()->user()->id)->where('status', '<>' ,'20')->whereIn('order_id',$order)->orderby('id','desc')->get();


        //    $data = Order::where('status',0)
        //    ->with('assign_order')
        //    ->orderby('id','desc')->get();
           //echo json_encode($data);exit;
           User::where('id', auth()->user()->id)->update(array('notification_count' => '0'));
           foreach($data as $key => $val){
               //dd($val->order->address);
               $catId = $val->order->service->category_id ?? '';
               $val->quick_service = $val->order->quick_service ?? '';
               $val->service_name = $val->order->service->service_name ?? '';
               $val->type = $val->order->service->category_get->type ?? '';
               $val->service_id = $val->order->service->id;
               //$val->quote_status  = $val->quote_status ;
               $val->category_id = $val->order->service->category_id ?? '';
                $val->pickupAddress = $val->order->rider_pickupAddress ?? '';
                $val->dropAddress = $val->order->address ?? '';
                $val->pickuplat = $val->order->rider_pickuplat ?? '';
                $val->pickuplng = $val->order->rider_pickuplng ?? '';
                $val->dropLat = $val->order->rider_dropLat ?? '';
                $val->dropLng = $val->order->rider_dropLng ?? '';
                $val->dateTime = $val->order->rider_dateTime ?? '';
                $val->price = $val->order->rider_price ?? '';
                $val->km = $val->order->rider_km ?? '';
               $val->description = $val->order->service->description ?? '';
               $val->commission = $val->order->tax ?? '';
               $val->trust_fees = $val->order->trust_fees ?? '';
               $val->user_description  = $val->order->desc ?? '';
               $commission = ($val->commission / 100) * $val->order->min_price;
               $trust = ($val->trust_fees / 100) * $val->order->min_price;
               $val->min_price = $val->order->min_price;
               //$val->extra_hours = $val->order->service->priceGet->extra_price ?? 0;
               $val->extra_hours = $val->order->extra_price ?? 0;
               $val->hours = $val->order->hours ?? '';
               $val->schedule = \Carbon\Carbon::parse($val->order->schedule)->format('D, d, M, Y');
               //$val->comment = $val->comment ?? '';
                
                /*$lat1 = auth()->user()->lat;
                $long1 = auth()->user()->long;

                $lat2 = $val->order->user->lat;
                $long2 = $val->order->user->long;*/

                $lat1 = $val->order->rider_pickuplat;
                $long1 = $val->order->rider_pickuplng;
                $lat2 = $val->order->rider_dropLat;
                $long2 =$val->order->rider_dropLng;
              
              $km = $this->distance($lat1,$long1,$lat2,$long2);
              $val->km = round($km).' '.'KM';
              $val->my_way_time  = $val->my_way_time ?? '';
              $val->arrived_time  = $val->arrived_time ?? '';
              
              $val->complite_service_time  = $val->complite_service_time ?? '';
              $val->resume_job_time  = $val->resume_job_time ?? '';
              $val->another_visit_request  = $val->another_visit_request ?? '';
              $val->reschedule_request_time  = $val->reschedule_request_time ?? '';
              $val->dispute_time  = $val->dispute_time ?? '';
              $val->cancel_job_time  = $val->cancel_job_time ?? '';
              $val->quote_revived_time  = $val->quote_revived_time ?? '';
              $imagesAry = OrderImage::where('order_id',$val->order_id)->get();
              $val->imagesAry = $imagesAry ?? '';
              $val->quote_status = 0;
              $quote = OrderQuotation::where('order_id',$val->order_id)->where('provider_id',auth()->user()->id)->first();
              if(!empty($quote)){
                $val->quote_status = 1;
              }
                $val->quotePrice = $quote->price ?? '';
                $orderInfo = Order::where('id',$val->order_id)->first();
                $orderUserInfo = User::where('id',$orderInfo->user_id)->first();
                $val->orderUserId = $orderUserInfo->id;
                $val->orderUserName = $orderUserInfo->first_name.' '.$orderUserInfo->last_name;
                $val->orderUserImage = $orderUserInfo->image ?? '';
                $val->orderUserNumber = $orderUserInfo->mobile ?? '';
              unset($val->comment);
            // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
            // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
            // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
            unset($val->order);
          }
           return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'success',
            'data'  => $data,
        ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'service request get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $e->getMessage().'-'.$e->getLine()
            ]);
        }
    }

    public function providerQuotation(Request $request, Notification $notification){
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'price' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try{
            $orderInfo = Order::where('id',$request->order_id)->first();
            $assignOrder = AssignOrder::where('order_id',$request->order_id)->where('user_id',auth()->user()->id)->first();
            $assignOrder->quote_status = '1';
            $assignOrder->save();
            $orderQuotation = new OrderQuotation;
            $orderQuotation->customer_id = $orderInfo->user_id;
            $orderQuotation->provider_id = auth()->user()->id;
            $orderQuotation->order_id = $request->order_id;
            $orderQuotation->price = $request->price;
            $orderQuotation->description = $request->description;
            $orderQuotation->save();
            $values = ['sender_id'=>auth()->user()->id,
                'receiver_id'=>$orderInfo->user_id,
                'from' =>'Notification',
                'tag' =>'quate recived',
                'order_id' =>$request->order_id,
                'message'=> 'you have received new quotation'];
                Notification::create($values);
                $notification->pushNotification($values);
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'Quote sent successfully.',
                'data'  => [],
            ]);

         }catch(\Exception $e){
            $data = ['error_type'=>'service request using id to get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

   
    //using Service id to get new All Request
    public function ServiceRequestget(Request $request){
        $validator = Validator::make($request->all(), [
            'service_id' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
          }
        try{
            $order = Order::select('id')->where('service_id',$request->service_id)->where('status','0')->get();
             $data = AssignOrder::where('user_id',auth()->user()->id)->whereIn('order_id',$order)->orderby('id','desc')->get();
        //    $data = Order::where('status',0)
        //    ->with('assign_order')
        //    ->orderby('id','desc')->get();
           //echo json_encode($data);exit;

           foreach($data as $key => $val){
               $val->service_name = $val->order->service->service_name ?? '';
               $val->type = $val->order->service->category_get->type ?? '';
               $val->service_id = $val->order->service->id;
               $val->category_id = $val->order->service->category_id ?? '';
               $val->description = $val->order->service->description ?? '';
               $val->commission = $val->order->tax ?? '';
               $val->trust_fees = $val->order->trust_fees ?? '';
               $val->user_description  = $val->order->desc ?? '';
               $commission = ($val->commission / 100) * $val->order->min_price;
               $trust = ($val->trust_fees / 100) * $val->order->min_price;
               $val->min_price = $val->order->min_price;
              // $val->extra_hours = $val->order->service->priceGet->extra_price ?? 0;
               $val->extra_hours = $val->order->extra_price ?? 0;
               $val->hours = $val->order->hours ?? '';
               $val->schedule = \Carbon\Carbon::parse($val->order->schedule)->format('D, d, M, Y');
               //$val->comment = $val->comment ?? '';

               $lat1 = auth()->user()->lat;
               $long1 = auth()->user()->long;
               $lat2 = $val->order->user->lat;
               $long2 = $val->order->user->long;
              $km = $this->distance($lat1,$long1,$lat2,$long2);
              $val->km = round($km).' '.'KM';
              $val->my_way_time  = $val->my_way_time ?? '';
              $val->arrived_time  = $val->arrived_time ?? '';
              $val->complite_service_time  = $val->complite_service_time ?? '';
              $val->resume_job_time  = $val->resume_job_time ?? '';
              $val->another_visit_request  = $val->another_visit_request ?? '';
              $val->reschedule_request_time  = $val->reschedule_request_time ?? '';
              $val->dispute_time  = $val->dispute_time ?? '';
              $val->cancel_job_time  = $val->cancel_job_time ?? '';
              $val->quote_revived_time  = $val->quote_revived_time ?? '';
              unset($val->comment);
            // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
            // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
            // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
            unset($val->order);
          }
           return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'success',
            'data'  => $data,
        ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'service request using id to get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

    public function rejectRequest(Request $request){
        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try {
            AssignOrder::where('user_id',auth()->user()->id)->where('order_id',$request->order_id)->update(['status'=>'20']);
            Order::where('user_id',auth()->user()->id)->where('id',$request->order_id)->update(['status'=>'20']);

            //Push Notification
            $orderuser = Order::where('id',$request->order_id)->first();
            $values = ['sender_id'=>auth()->user()->id,
            'receiver_id'=>$orderuser->user_id,
            'from' =>'Notification',
            'message'=> 'Provider has rejected your request'];
            
            Notification::create($values);
            $notification->pushNotification($values);
            
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'Request cancel successfully'
            ]);
        } catch (Exception $e) {
            $data = ['error_type'=>'service request accept time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'.$e->getMessage()
            ]);
          }
    }


    //Accept request
    public function AcceptRequest(Request $request,Notification $notification){
        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
      try{
         $check = AssignOrder::where('order_id',$request->order_id)->where('status','!=','0')->get();
         if(count($check) > 0){
            // $data = AssignOrder::where('user_id',auth()->user()->id)->where('status','0')->orderby('id','desc')->get();
            $order = Order::select('id')->where('status','0')->get();
            $data = AssignOrder::where('user_id',auth()->user()->id)->whereIn('order_id',$order)->orderby('id','desc')->get();
            foreach($data as $key => $val){
                $val->service_name = $val->order->service->service_name ?? '';
                $val->category_id = $val->order->service->category_id ?? '';
                $val->description = $val->order->service->description ?? '';
                $val->commission = $val->order->tax ?? '';
                $val->trust_fees = $val->order->trust_fees ?? '';
                $val->user_description  = $val->order->desc ?? '';
                $commission = ($val->commission / 100) * $val->order->min_price;
                $trust = ($val->trust_fees / 100) * $val->order->min_price;
                $val->min_price = $val->order->min_price;
               // $val->extra_hours = $val->order->service->priceGet->extra_price ?? 0;
               $val->extra_hours = $val->order->extra_price ?? 0;
                $val->hours = $val->order->hours ?? '';
                $val->schedule = \Carbon\Carbon::parse($val->order->schedule)->format('D, d, M, Y');
               // $val->comment = $val->comment ?? '';
                unset($val->comment);
                $lat1 = auth()->user()->lat;
                $long1 = auth()->user()->long;
                $lat2 = $val->order->user->lat;
                $long2 = $val->order->user->long;
               $km = $this->distance($lat1,$long1,$lat2,$long2);
               $val->km = round($km).' '.'KM';
               $val->my_way_time  = $val->my_way_time ?? '';
               $val->arrived_time  = $val->arrived_time ?? '';
               $val->complite_service_time  = $val->complite_service_time ?? '';
               $val->resume_job_time  = $val->resume_job_time ?? '';
               $val->another_visit_request  = $val->another_visit_request ?? '';
               $val->reschedule_request_time  = $val->reschedule_request_time ?? '';
               $val->dispute_time  = $val->dispute_time ?? '';
               $val->cancel_job_time  = $val->cancel_job_time ?? '';
               $val->quote_revived_time  = $val->quote_revived_time ?? '';
              
             // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
             // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
             // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
             unset($val->order);
           }
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'data'     => $data,
                'message' => 'Order Already Accepted.'
            ]);
         }else{
            $check = AssignOrder::where('user_id',auth()->user()->id)->where('order_id',$request->order_id)->first();
            if($check->order->service->category_get->type == '0'){
                $status = '11';
            }else{
                $status = '1';
            }
            AssignOrder::where('user_id',auth()->user()->id)->where('order_id',$request->order_id)->update(['status'=>$status]);
            Order::where('id',$request->order_id)->update(['status'=>$status]);
            
            // $data = AssignOrder::where('user_id',auth()->user()->id)->where('status','0')->orderby('id','desc')->get();
            $order = Order::select('id')->where('status','0')->get();
            
             $data = AssignOrder::where('user_id',auth()->user()->id)->whereIn('order_id',$order)->orderby('id','desc')->get();

            foreach($data as $key => $val){
                $val->service_name = $val->order->service->service_name ?? '';
                $val->category_id = $val->order->service->category_id ?? '';
                $val->description = $val->order->service->description ?? '';
                $val->commission = $val->order->tax ?? '';
                $val->trust_fees = $val->order->trust_fees ?? '';
                $val->user_description  = $val->order->desc ?? '';
                $commission = ($val->commission / 100) * $val->order->min_price;
                $trust = ($val->trust_fees / 100) * $val->order->min_price;
                $val->min_price = $val->order->min_price;
               // $val->extra_hours = $val->order->service->priceGet->extra_price ?? 0;
                $val->extra_hours = $val->order->extra_price ?? 0;
                $val->hours = $val->order->hours ?? '';
                $val->schedule = \Carbon\Carbon::parse($val->order->schedule)->format('D, d, M, Y');
               // $val->comment = $val->comment ?? '';
                unset($val->comment);
                $lat1 = auth()->user()->lat;
                $long1 = auth()->user()->long;
                $lat2 = $val->order->user->lat;
                $long2 = $val->order->user->long;
               $km = $this->distance($lat1,$long1,$lat2,$long2);
               $val->km = round($km).' '.'KM';
               $val->my_way_time  = $val->my_way_time ?? '';
               $val->arrived_time  = $val->arrived_time ?? '';
               $val->complite_service_time  = $val->complite_service_time ?? '';
               $val->resume_job_time  = $val->resume_job_time ?? '';
               $val->another_visit_request  = $val->another_visit_request ?? '';
               $val->reschedule_request_time  = $val->reschedule_request_time ?? '';
               $val->dispute_time  = $val->dispute_time ?? '';
               $val->cancel_job_time  = $val->cancel_job_time ?? '';
               $val->quote_revived_time  = $val->quote_revived_time ?? '';
               $msg = 'Job Accepted Successfully.';
               if($val->category_id == '3'){
                $msg = 'Ride Accepted successfully.';
               }
             // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
             // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
             // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
             unset($val->order);
           }
                $orderuser = Order::where('id',$request->order_id)->first();
                $values = ['sender_id'=>auth()->user()->id,
                'receiver_id'=>$orderuser->user_id,
                'from' =>'Notification',
                'message'=> 'Provider has accepted your request'];

                Notification::create($values);
                $notification->pushNotification($values);
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'data'     => $data,
                    'message' => $msg
                ]);
         }
      }catch(\Exception $e){
        $data = ['error_type'=>'service request accept time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => 'something wrong !'
        ]);
      }
    }

     //distance get
     public function distance($lat1,$long1,$lat2,$long2)
     {
      // dd(['lat1'=>$lat1,'long1'=>$long1,'lat2'=>$lat2,'long2'=>$long2]);
         $pi80 = M_PI / 180; 
             $lat1 *= $pi80; 
             $long1 *= $pi80; 
             $lat2 *= $pi80; 
             $long2 *= $pi80; 
             $r = 6372.797; // mean radius of Earth in km 
             $dlat = $lat2 - $lat1; 
             $dlon = $long2 - $long1; 
             $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2); 
             $c = 2 * atan2(sqrt($a), sqrt(1 - $a)); 
             return $km = $r * $c; 
             
     }

    //In Process Request
    public function Inprocess(Request $request){
        try{
            $data = [];
            if($request->slug == 'inprocess'){
                // added later in array 20
                $order = AssignOrder::whereNotIn('status',[0,4,3,20])->where('user_id',auth()->user()->id)->orderby('id','desc')->get();
                // $order = AssignOrder::where('status','1')->where('user_id',auth()->user()->id)->get();
            }else if($request->slug == 'complite'){
                $order = AssignOrder::whereIn('status',[4])->where('user_id',auth()->user()->id)->orderby('id','desc')->get();
            }else{
                $order = [];
            }
            if(!empty($order)){       
                foreach($order as $key => $val){
                    $val->comment = $val->comment ?? '';
                    $val->image = $val->order->user->image ?? '';
                    $val->service_name = $val->order->service->service_name ?? '';
                    $val->category_id = $val->order->service->category_id ?? '';
                    $val->type = $val->order->service->category_get->type ?? '';
                    $val->description = $val->order->service->description ?? '';
                    $val->schedule = \Carbon\Carbon::parse($val->order->schedule)->format('D, d, M, Y');
                    $val->my_way_time  = $val->my_way_time ?? '';
                    $val->quote_status  = $val->quote_status ;
                    $val->arrived_time  = $val->arrived_time ?? '';
                    $val->pickupAddress = $val->order->rider_pickupAddress ?? '';
                    $val->dropAddress = $val->order->address ?? '';
                    $val->pickuplat = $val->order->rider_pickuplat ?? '';
                    $val->pickuplng = $val->order->rider_pickuplng ?? '';
                    $val->dropLat = $val->order->rider_dropLat ?? '';
                    $val->dropLng = $val->order->rider_dropLng ?? '';
                    $val->dateTime = $val->order->rider_dateTime ?? '';
                    $val->price = $val->order->rider_price ?? '';
                    $val->km = $val->order->rider_km ?? '';
                    $val->complite_service_time  = $val->complite_service_time ?? '';
                    $val->resume_job_time  = $val->resume_job_time ?? '';
                    $val->another_visit_request  = $val->another_visit_request ?? '';
                    $val->reschedule_request_time  = $val->reschedule_request_time ?? '';
                    $val->dispute_time  = $val->dispute_time ?? '';
                    $val->cancel_job_time  = $val->cancel_job_time ?? '';
                    $val->quote_revived_time  = $val->quote_revived_time ?? '';
                    $val->user_description  = $val->order->desc ?? '';
                    $val->address   = $val->order->address ?? '';
                    $val->reciver_id = $val->order->user_id;
                    $val->sender_id = $val->user_id;
                    $val->unit = $val->order->unit ?? '';
                    $val->hours = $val->order->hours ?? '';
                    $val->schedule = $val->order->schedule ?? '';
                    $commission =  $val->order->service->commission;
                    $val->imgArray = OrderImage::where('order_id',$val->order_id)->get();
                    $quote = OrderQuotation::where('order_id',$val->order_id)->where('provider_id',auth()->user()->id)->first();
                    $val->quotePrice = $quote->price ?? '';
                    $trust = ($val->order->trust_fees / 100) * $val->order->min_price;
                    if($val->order->service->category_get->type == '1'){
                        $val->minemum_price = $val->order->min_price;
                    }else{
                        $price = QuotePrice::where('order_id',$val->order_id)->first();
                        $val->minemum_price = $price->price ?? 0;
                    }
                    if($request->slug == 'complite'){
                        $invoiceData = Invoice::where('order_id',$val->order_id)->first();
                        $val->regular_hours = $invoiceData->regular_hours ?? '';
                        $val->after_hours = $invoiceData->after_hours ?? '';
                        $val->extra_hours_cost = $invoiceData->extra_hours_cost ?? '';
                        $val->sub_total = $invoiceData->sub_total ?? '';
                    }
                    $val->varient = $val->order->service->priceGet->varient ?? 'free quote';
                    //$val->extra_hours = $val->order->service->priceGet->extra_price ?? 0;
                    $val->extra_hours = $val->order->extra_price ?? 0;
                    $val->customer_name = $val->order->user->first_name.' '.$val->order->user->last_name;
                    $val->customer_number = $val->order->user->mobile ?? '';
                    $val->schedule = Carbon::parse($val->schedule)->format('Y/m/d');
                    if($val->status == '11'){
                        $status = '1';
                    }else{
                        $status = $val->status;
                    }
                    $val->job_status_id = $status;
                    if($val->status == '2'){
                        $active = '1';
                        $name = 'On My Way';
                    }else{
                        $active = '0'; 
                    }        
                    if($val->status == '4'){
                        $complite = '1';
                        $name = 'Service Completion';
                    }else{
                        $complite = '0';
                    }           
                    if($val->status == '7'){
                        $resudle = '1';
                        $name = 'Reschedule after confirmation with customer (New ETA)';
                    }else{
                        $resudle = '0';
                    }            
                    if($val->status == '9'){
                        $resume = '1';
                        $name = "Job Resume (When 'Need another visit' selected and arrived onsite for Day 2)";
                    }else{
                        $resume = '0'; 
                    }
                    if($val->status == '6'){
                        $arri = '1';
                        $name = 'Arrived on Site';
                    }else{
                        $arri = '0';
                    }
                    if($val->status == '15'){
                        $rideStatus = '1';
                        $name = 'Ride Started';
                    }else{
                        $rideStatus = '0';
                    }
                    // else{
                    //     $name = 'Send Free Quote';
                    //      $recive = '0';
                    // }
                    if($val->category_id == '9'){
                        $val->job_status = $name ?? 'In-process';
                    }else {
                        $val->job_status = $name ?? 'In-process';
                    }            
                    $val->status_detail = [
                       ['id'=>'2','name'=>'On My Way','status'=> $active ?? '0'],
                       ['id'=>'6','name'=>'Arrived on Site','status'=> $arri ?? '0'],
                       ['id'=>'7','name'=>'Reschedule after confirmation with customer (New ETA)','status'=> $resudle ?? '0'],
                       ['id'=>'9','name'=>"Job Resume (When 'Need another visit' selected and arrived onsite for Day 2)",'status'=> $resume ?? '0'],
                       //  ['id'=>'3','name'=>'cancel Job','status'=> $cancel ?? '0'],
                       ['id'=>'4','name'=>'Service Completion','status'=> $complite ?? '0'],
                       //  ['id'=>'5','name'=>'dispute','status'=> $dispute ?? '0'],
                    ];
                    $val->status_detail_rideshare = [
                        ['id'=>'2','name'=>'On My Way','status'=> $active ?? '0'],
                        ['id'=>'6','name'=>'Arrived on Site','status'=> $arri ?? '0'],
                        //  ['id'=>'3','name'=>'cancel Job','status'=> $cancel ?? '0'],
                        //['id'=>'4','name'=>'Service Completion','status'=> $complite ?? '0'],
                        //  ['id'=>'5','name'=>'dispute','status'=> $dispute ?? '0'],
                        ['id'=>'15','name'=>'Ride Started','status'=> $rideStatus ?? '0'],
                        ['id'=>'4','name'=>"Complete Ride",'status'=> $complite ?? '0'],
                    ];
                    //   ['id'=>'0','name'=>'Pending','status'=> $pending ?? '0'],
                    //    ['id'=>'1','name'=>'In Process','status'=> $recive ?? '0'],
                    //    ['id'=>'2','name'=>'Active job /on my way','status'=> $active ?? '0'],
                    //    ['id'=>'3','name'=>'cancel Job','status'=> $cancel ?? '0'],
                    //    ['id'=>'4','name'=>'Complete','status'=> $complite ?? '0'],
                    //    ['id'=>'5','name'=>'dispute','status'=> $dispute ?? '0'],
                    //    ['id'=>'6','name'=>'Arrived on site','status'=> $arrived ?? '0'],
                    //    ['id'=>'7','name'=>'Reschedule job','status'=> $resudle ?? '0'],
                    //    ['id'=>'8','name'=>'Need another visit','status'=> $need ?? '0'],
                    //    ['id'=>'9','name'=>'Resume Job','status'=> $resume ?? '0'],
                    //    ['id'=>'10','name'=>'Free Quote','status'=> $resume ?? '0'],
                    //status 11 for free quote request accept
                    unset($val->order);
                }
            }
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'success',
                'data'  => $order,
            ]);
        }catch(\Exception $e){  
            $data = ['error_type'=>'service request accept time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => 'something wrong !'
        ]);
        }
    }

    public function jobDetail(Request $request){
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try {
            $order = AssignOrder::where('order_id',$request->order_id)->where('status','!=','0')->get();
            foreach($order as $key => $val){
                $val->comment = $val->comment ?? '';
                $val->service_name = $val->order->service->service_name ?? '';
                $val->category_id = $val->order->service->category_id ?? '';
                $val->type = $val->order->service->category_get->type ?? '';
                $val->description = $val->order->service->description ?? '';
                $val->schedule = \Carbon\Carbon::parse($val->order->schedule)->format('D, d, M, Y');
                $val->my_way_time  = $val->my_way_time ?? '';
                $val->arrived_time  = $val->arrived_time ?? '';
                    $val->pickupAddress = $val->order->rider_pickupAddress ?? '';
                    $val->dropAddress = $val->order->address ?? '';
                    $val->pickuplat = $val->order->rider_pickuplat ?? '';
                    $val->pickuplng = $val->order->rider_pickuplng ?? '';
                    $val->dropLat = $val->order->rider_dropLat ?? '';
                    $val->dropLng = $val->order->rider_dropLng ?? '';
                    $val->dateTime = $val->order->rider_dateTime ?? '';
                    $val->price = $val->order->rider_price ?? '';
                    $val->km = $val->order->rider_km ?? '';
                $val->complite_service_time  = $val->complite_service_time ?? '';
                $val->resume_job_time  = $val->resume_job_time ?? '';
                $val->another_visit_request  = $val->another_visit_request ?? '';
                $val->reschedule_request_time  = $val->reschedule_request_time ?? '';
                $val->dispute_time  = $val->dispute_time ?? '';
                $val->cancel_job_time  = $val->cancel_job_time ?? '';
                $val->quote_revived_time  = $val->quote_revived_time ?? '';
                $val->user_description  = $val->order->desc ?? '';
                $val->address   = $val->order->address ?? '';
                $val->reciver_id = $val->order->user_id;
                $val->sender_id = $val->user_id;
                $val->unit = $val->order->unit ?? '';
                $val->hours = $val->order->hours ?? '';
                $val->schedule = $val->order->schedule ?? '';
                $commission =  $val->order->service->commission;
                $trust = ($val->order->trust_fees / 100) * $val->order->min_price;
                if($val->order->service->category_get->type == '1'){
                 $val->minemum_price = $val->order->min_price;
                }else{
                 $price = QuotePrice::where('order_id',$val->order_id)->first();
                 $val->minemum_price = $price->price ?? 0;
                }
                $val->varient = $val->order->service->priceGet->varient ?? 'free quote';
                $invoiceData = Invoice::where('order_id',$val->order_id)->first();
                $quoteData =OrderQuotation::where('order_id',$val->order_id)->where('provider_id',auth()->user()->id)->first();
                if(!empty($quoteData)){
                    $val->quotePrice =$quoteData->price;
                } else {
                    $val->quotePrice ='';
                }
                $val->imgAry = OrderImage::where('order_id',$val->order_id)->get();
                $val->regular_hours = $invoiceData->regular_hours ?? '';
                $val->after_hours = $invoiceData->after_hours ?? '';
                $val->extra_hours_cost = $invoiceData->extra_hours_cost ?? '';
                $val->sub_total = $invoiceData->sub_total ?? '';
                //$val->extra_hours = $val->order->service->priceGet->extra_price ?? 0;
                $val->extra_hours = $val->order->extra_price ?? 0;
                $val->customer_name = $val->order->user->first_name.' '.$val->order->user->last_name;
                $val->customer_number = $val->order->user->mobile ?? '';
                $val->schedule = Carbon::parse($val->schedule)->format('Y/m/d');
                if($val->status == '11'){
                    $status = '1';
                }else{
                    $status = $val->status;
                }
                $val->job_status_id = $status;
                if($val->status == '2'){
                    $active = '1';
                    $name = 'On My Way';
                }else{
                    $active = '0'; 
                }
                if($val->status == '4'){
                    $complite = '1';
                    $name = 'Service Completion';
                }else{
                    $complite = '0';
                }
                if($val->status == '7'){
                    $resudle = '1';
                    $name = 'Reschedule after confirmation with customer (New ETA)';
                }else{
                    $resudle = '0';
                }
                if($val->status == '9'){
                    $resume = '1';
                    $name = "Job Resume (When 'Need another visit' selected and arrived onsite for Day 2)";
                }else{
                    $resume = '0'; 
                }
                if($val->status == '6'){
                    $arri = '1';
                    $name = 'Arrived on Site';
                }else{
                    $arri = '0';
                }
                if($val->status == '15'){
                    $rideStatus = '1';
                    $name = 'Ride Start';
                }else{
                    $rideStatus = '0';
                }
             // else{
             //     $name = 'Send Free Quote';
             //      $recive = '0';
             // }
                if($val->category_id == '9'){
                    $val->job_status = $name ?? 'On My Way';
                }else {
                    $val->job_status = $name ?? 'In Progress';
                }
                $val->status_detail = [
                    ['id'=>'2','name'=>'On My Way','status'=> $active ?? '0'],
                 //  ['id'=>'3','name'=>'cancel Job','status'=> $cancel ?? '0'],
                    ['id'=>'4','name'=>'Service Completion','status'=> $complite ?? '0'],
                 //  ['id'=>'5','name'=>'dispute','status'=> $dispute ?? '0'],
                    ['id'=>'6','name'=>'Arrived on Site','status'=> $arri ?? '0'],
                    ['id'=>'7','name'=>'Reschedule after confirmation with customer (New ETA)','status'=> $resudle ?? '0'],
                    ['id'=>'9','name'=>"Job Resume (When 'Need another visit' selected and arrived onsite for Day 2)",'status'=> $resume ?? '0'],
                   ];
                   $val->status_detail_rideshare = [
                     ['id'=>'2','name'=>'On My Way','status'=> $active ?? '0'],
                  //  ['id'=>'3','name'=>'cancel Job','status'=> $cancel ?? '0'],
                     //['id'=>'4','name'=>'Service Completion','status'=> $complite ?? '0'],
                  //  ['id'=>'5','name'=>'dispute','status'=> $dispute ?? '0'],
                     ['id'=>'6','name'=>'Arrived on Site','status'=> $arri ?? '0'],
                     ['id'=>'15','name'=>'Ride Start','status'=> $rideStatus ?? '0'],
                     ['id'=>'4','name'=>"Complete Ride",'status'=> $complite ?? '0'],
                    ];
                 //   ['id'=>'0','name'=>'Pending','status'=> $pending ?? '0'],
                 //    ['id'=>'1','name'=>'In Process','status'=> $recive ?? '0'],
                 //    ['id'=>'2','name'=>'Active job /on my way','status'=> $active ?? '0'],
                 //    ['id'=>'3','name'=>'cancel Job','status'=> $cancel ?? '0'],
                 //    ['id'=>'4','name'=>'Complete','status'=> $complite ?? '0'],
                 //    ['id'=>'5','name'=>'dispute','status'=> $dispute ?? '0'],
                 //    ['id'=>'6','name'=>'Arrived on site','status'=> $arrived ?? '0'],
                 //    ['id'=>'7','name'=>'Reschedule job','status'=> $resudle ?? '0'],
                 //    ['id'=>'8','name'=>'Need another visit','status'=> $need ?? '0'],
                 //    ['id'=>'9','name'=>'Resume Job','status'=> $resume ?? '0'],
                  //    ['id'=>'10','name'=>'Free Quote','status'=> $resume ?? '0'],
                  //status 11 for free quote request accept
                unset($val->order);
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'message' => 'success',
                    'data'  => $order,
                ]);
             }
        } catch(\Exception $e){  
            $data = ['error_type'=>'service request accept time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => 'something wrong !'.$e->getMessage().' - '.$e->getLine()
        ]);
        }
    }

    //Order Detail Get
    public function OrderDetail(Request $request){
        $validator = Validator::make($request->all(),[
            'order_id' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
      try{
            $order = Order::where('id',$request->order_id)->first();
            $order->service_name = $order->service->service_name ?? '';
            $order->type = $order->service->category_get->type ?? '';
            $order->description = $order->service->description ?? '';
            $order->customer_name = $order->user->first_name.' '.$order->user->last_name;
            $order->customer_number = $order->user->mobile ?? '';
            $order->schedule = \Carbon\Carbon::parse($order->schedule)->format('D, d, M ,Y');
            $order->commission = $order->service->commission;
            $commission = ($order->commission / 100) * $order->min_price;
            $trust = ($order->trust_fees / 100) * $order->min_price;
            $order->minemum_price = $order->min_price + $commission + $trust;
            $order->extra_hours = $order->min_price;

            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'success',
                'data'  => $order,
      ]);
      }catch(\Exception $e){
        $data = ['error_type'=>'Order Detail get time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => 'something wrong !'
        ]);
      }
    }

    //job status update
    public function JobStatus(Request $request, Notification $notification){
        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required',
            'status'  => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try{
            Order::where('id',$request->order_id)->update(['status'=>$request->status]);
            $orderData = Order::where('id',$request->order_id)->first();
            $reciverUserId = $orderData->user_id;
            $input['status'] = $request->status;
            $message = '';
            if($request->status == '2'){
                $input['my_way_time'] = date('Y/m/d H:i:s');
                $message = "On My Way";
            }
            else if($request->status == '15'){
                $input['arrived_time'] = date('Y/m/d H:i:s');
                $message = "Ride Start";
            }

            else if($request->status == '6'){
                $input['arrived_time'] = date('Y/m/d H:i:s');
                $message = "Arrived on site";
            }
            else if($request->status == '4'){
                $input['complite_service_time'] = date('Y/m/d H:i:s');
                $message = "Job Complete Success";
            }
            else if($request->status == '10'){
                $input['quote_revived_time'] = date('Y/m/d H:i:s');
                $message = "Quote Send Success";
            }
            else if($request->status == '3'){
                $input['cancel_job_time'] = date('Y/m/d H:i:s');
                $message = "Job Cancel Success";
            }
            else if($request->status == '5'){
                $input['dispute_time'] = date('Y/m/d H:i:s');
                $message = "Job Dispute Success";
            }
            else if($request->status == '7'){
                $input['reschedule_request_time'] = date('Y/m/d H:i:s');
                $message = "Reschedule job Success";
            }
            else if($request->status == '8'){
                $input['another_visit_request'] = date('Y/m/d H:i:s');
                $message = "Need another visit";
            }
            else if($request->status == '9'){
                $input['resume_job_time'] = date('Y/m/d H:i:s');
                $message = "Job Resumed Successfully";
            }
            else{
                $input['my_way_time'] = NULL;
                $input['start_time'] = NULL;
                $input['arrived_time'] = NULL;
                $input['complite_service_time'] = NULL;
                $input['resume_job_time'] = NULL;
                $input['another_visit_request'] = NULL;
                $input['reschedule_request_time'] = NULL;
                $input['dispute_time'] = NULL; 
                $input['cancel_job_time'] = NULL;
                $input['quote_revived_time'] = NULL;
            }
            $values = ['sender_id'=>auth()->user()->id,
                        'receiver_id'=>$reciverUserId,
                        'from' =>'Notification',
                        'tag' =>'service detail',
                        'order_id' =>$request->order_id,
                        'status'=>$request->status,
                        'message'=> $message];
                        Notification::create($values);
                        $notification->pushNotification($values);
            AssignOrder::where('order_id',$request->order_id)->where('user_id',auth()->user()->id)->update($input);
           
            //   ['id'=>'0','name'=>'Pending','status'=> $pending ?? '0'],
            //    ['id'=>'1','name'=>'Quote Revived','status'=> $recive ?? '0'],
            //    ['id'=>'2','name'=>'Active job /on my way','status'=> $active ?? '0'],
            //    ['id'=>'3','name'=>'cancel Job','status'=> $cancel ?? '0'],
            //    ['id'=>'4','name'=>'Complete','status'=> $complite ?? '0'],
            //    ['id'=>'5','name'=>'dispute','status'=> $dispute ?? '0'],
            //    ['id'=>'6','name'=>'Arrived on site','status'=> $arrived ?? '0'],
            //    ['id'=>'7','name'=>'Reschedule job','status'=> $resudle ?? '0'],
            //    ['id'=>'8','name'=>'Need another visit','status'=> $need ?? '0'],
            //    ['id'=>'9','name'=>'Resume Job','status'=> $resume ?? '0'],
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => $message
            ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'job status update time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,            
                'response' => 'error',
                'message' => 'something wrong !'.$e->getMessage()
            ]);
        }
    }

    //Reschedule Job data save
    public function Reschedule(Request $request, Notification $notification){
        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required',
            'hours'   =>  'required',
            'schedule' => 'required',
            'reason'  => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
       try{
           $input = $request->all();
           $input['user_id'] = auth()->user()->id;
           $data = ['desc'=> $request->reason,
                  'hours' => $request->hours,
                  'schedule' => $request->schedule];
           Order::where('id',$request->order_id)->update(['status'=>'7','schedule'=>$request->schedule,'hours'=>$request->hours]);
           AssignOrder::where('user_id',auth()->user()->id)->where('order_id',$request->order_id)->update(['status'=>'7']);
           Reschedule::create($input);
           $orderuser = Order::where('id',$request->order_id)->first();
                $values = ['sender_id'=>auth()->user()->id,
                'receiver_id'=>$orderuser->user_id,
                'from' =>'Notification',
                'tag' =>'service detail',
                'order_id' =>$request->order_id,
                'status' =>'Reschedule Job',
                'message'=> 'Provider has been rescheduled your job request.'];
                Notification::create($values);
                $notification->pushNotification($values);
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'Job Reschedule success'
            ]);
       }catch(\Exception $e){
        $data = ['error_type'=>'job status update time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => 'something wrong !'.$e->getMessage().' - '.$e->getLine()
        ]);
       }
    }

    //another visit form submit
    public function AnotherVisit(Request $request){
        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required',
            'date_time'   =>  'required',
            'reason'  => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
      try{
          $input = $request->all();
          $input['user_id'] = auth()->user()->id;
          order::where('id',$request->order_id)->update(['status'=>'8']);
          AssignOrder::where('user_id',auth()->user()->id)->where('order_id',$request->order_id)->update(['status'=>'8']);
          AnotherVisit::create($input);
        return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'Another visit success'
        ]);
      }catch(\Exception $e){
        $data = ['error_type'=>'job another visit time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => 'something wrong !'
        ]);
      }
    }

    //Invoice Save 
    public function InvoiceSave(Request $request, Notification $notification){
        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required',
            //'type'   =>  'required',
            //'regular_hours'  => 'required',
            // 'after_hours' => 'required',
            // 'extra_hours_cost'   =>  'required',
            // 'item'  => 'required',
            'comment' => 'required',
            'sub_total'   =>  'required',
            //'signature'  => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try{
            $input = $request->all();
            $input['user_id'] = auth()->user()->id;
            if($request->hasFile('signature')) {
                $avatar = $request->file('signature');
                $avatarName = 'signature'.time().'.'.$avatar->getClientOriginalExtension();
                $avatar->storeAs('public/user/',$avatarName);
                $path = url('/storage/user/'.$avatarName);
                $input['signature'] = $path;
            }
            // Invoice::create($input);
            // AssignOrder::where('user_id',auth()->user()->id)->where('order_id',$request->order_id)->update(['status'=>'4']);
            // Order::where('id',$request->order_id)->update(['status'=>'4']);

            //user card information get
            $user = Order::where('id',$request->order_id)->first();
            $user_id = $user->user_id;
            $customer = StripeCard::where('user_id',$user_id)->where('status','1')->first();
            //$customer_id = $customer->stripe_id;
            $customer_id = $input['user_id'];
            //Payment
            try{
                if(!empty($user->promo)){
                  $promoprice =  Discount::where('coupon',$user->promo)->first();
                  $sub_total = $request->sub_total - $promoprice->price;
                }else{
                    $sub_total = $request->sub_total;
                }
                // \Stripe\Stripe::setApiKey('sk_test_51H7fccLVBFA034ip2OJyNrnCGaiycIi4iTv33KkXZKedabOiXSvOMqdCJsnymHIFKHcPUUfeWGK9bPJCmbOsA7du00QMvu0iD0');
                // $intent = \Stripe\PaymentIntent::create([
                //   'amount' => $sub_total*100,
                //   'currency' => 'inr',
                //   'customer' => $customer_id,
                //   'off_session' => true,
                //   'confirm' => true
                // ]);
                $input['payment_id'] = rand(1,100000);
                unset($input['service_id']);
                unset($input['state']);
                unset($input['unit']);
                Invoice::create($input);
                AssignOrder::where('user_id',auth()->user()->id)->where('order_id',$request->order_id)->update(['status'=>'4']);
                Order::where('id',$request->order_id)->update(['status'=>'4']);
                $values = ['sender_id'=>auth()->user()->id,
                'receiver_id'=>$user->user_id,
                'from' =>'Notification',
                'tag'=>'Service Complete',
                'order_id'=>$request->order_id,
                'status'=>4,
                'message'=> 'Your job has been completed'];
                Notification::create($values);
                $notification->pushNotification($values);
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                   'message' => 'your job complete successfully.',
                   'data' => new \stdClass()
                ]);
            }catch(\Stripe\Exception\CardException $e){
                //dd('Error code is:' . $e->getError()->code);
                return response()->json([
                    'status_code' => 401,
                    'response' => 'error',
                    'message' =>  $e->getError()->code
                ]);
                $payment_intent_id = $e->getError()->payment_intent->id;
                $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
            }
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'Invoice save success'
            ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'job invoice save time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $e->getMessage().' - '.$e->getLine(),
            ]);
        }
    }

    //cancel job
    public function CancelJob(Request $request){
        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
     try{
            $check = Order::where('id',$request->order_id)->first();
            $endtime =date('Y-m-d h:m:s');
            if($check->schedule != NULL){
                $starttimestamp = strtotime($check->schedule);
            }else{
                $schedule = \Carbon\Carbon::parse($check->create_at)->addHours(12);
                $starttimestamp = strtotime($schedule);
            }
           
            $endtimestamp = strtotime($endtime);
            $difference = abs($endtimestamp - $starttimestamp)/3600;
           
           //condition apply for cancel job
           $hours = CancelPriceMaster::where('type','provider')->where('hours','>=',$difference)->first();
           if(!empty($hours)){
            $price = $hours->price;
           }else{
               $price = NULL;
           }
          
           
        Order::where('id',$request->order_id)->update(['status'=>'3']);

        AssignOrder::where('order_id',$request->order_id)->where('user_id',auth()->user()->id)->update(['status'=>'3','cancel_job_time'=>date('Y/m/d H:i:s')]);
        CancelJob::create(['user_id'=>auth()->user()->id,'order_id'=>$request->order_id,'job_status'=>'3','charge'=>$price]);

        //Push Notification
        $orderuser = Order::where('id',$request->order_id)->first();
        $values = ['sender_id'=>auth()->user()->id,
        'receiver_id'=>$orderuser->user_id,
        'from' =>'Notification',
        'message'=> 'Provider has cancel job'];
        
        Notification::create($values);
        $notification->pushNotification($values);

        return response()->json(['status_code' => 200,'response' => 'success','message' => 'Job Cancel success']);
     }catch(\Exception $e){
        $data = ['error_type'=>'job invoice save time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json(['status_code' => 500,'response' => 'error','message' => 'something wrong !' ]);
     }
    }

    //completed job
    public function CompliteJob(){
        try{        
            $job = AssignOrder::where('status','4')->where('user_id',auth()->user()->id)->get();
             foreach($job as $val){
                 $val->service_name = $val->order->service->service_name;
                 $val->description = $val->order->service->description;
             }
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'success',
                'data'  => $job
            ]);

        }catch(\Exception $e){
            $data = ['error_type'=>'job Complite get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

    //Provider Profile get
    public function ProfileGet(){
        try{
        $user = User::where('id',auth()->user()->id)->first();
        //$user->approve = $user->approve ?? '5';
        $user->business_address = $user->bussiness->business_address ?? '';
        $user->unit = $user->bussiness->unit ?? '';
        $user->website = $user->bussiness->website ?? '';
        foreach($user->toArray() as $key => $value){
            if($value == null){
                $user[$key] = '';
            }
        }
        unset($user->bussiness);
        return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'success',
            'data' => $user
        ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'profile get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

    //profile update
    public function profileUpdate(Request $request){
        $validator = Validator::make($request->all(), [ 
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required|min:6|max:15|unique:users,mobile,'.auth()->user()->id,
            'address' => 'required',
            //'state'  => 'required',
            'lat'  => 'required',
            'long'  => 'required'
           
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
       try{
        $input = $request->all();
        if($request->hasFile('image')) {
            $avatar = $request->file('image');
            $avatarName = 'image'.time().'.'.$avatar->getClientOriginalExtension();
            $avatar->move('storage/user/',$avatarName);
            $path = url('/storage/user/'.$avatarName);
            $input['image'] = $path;
        }else{
            $input['image'] = $request->image;
        }
         $check =  UserAddress::where('user_id',auth()->user()->id)->first();
         if(empty($check)){
            UserAddress::create(['user_id'=>auth()->user()->id,'address'=>$request->address,
            'state'=>$request->state,
            'lat'=>$request->lat,
            'long' => $request->long]);
         }else{
            UserAddress::where('user_id',auth()->user()->id)->update(['address'=>$request->address,
            'state'=>$request->state,
            'lat'=>$request->lat,
            'long' => $request->long]);
         }
       
        unset($input['state']);

        if($request->device_token != ''){
            $input['device_token']   = $request->device_token;
        }
        User::where('id',auth()->user()->id)->update($input);
        $user = User::where('id',auth()->user()->id)->first();
        //$user->address = $user->getaddress->address ?? '';
        $user->lati = $user->getaddress->lat ?? '';
        $user->longti = $user->getaddress->long ?? '';
        $user->state = $user->getaddress->state ?? '';
        $user->mobile_code = $user->mobile_code;
        // $user->address = $user->bussiness->business_address ?? '';
        $user->unit = $user->bussiness->unit ?? '';
        $user->website = $user->bussiness->website ?? '';
        unset($user['bussiness']);
        foreach ($user->toArray() as $key => $value) {
            if($value == null){
                $user[$key] = '';
            }
        }
        return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'profile update success',
            'data'   => $user
        ]);
       }catch(\Exception $e){
        $data = ['error_type'=>'Profile update time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => 'something wrong !'
        ]);
       }
    }

    //my earnings pending
    public function MyEarnings(){
        try{
        $earnings = Invoice::where('user_id',auth()->user()->id)->get();
        foreach($earnings as $val){
            $val->service_name = $val->order->service->service_name;
            $val->schedule = \Carbon\Carbon::parse($val->order->schedule)->format('D, d, M ,Y');
            $val->hours = $val->order->hours;
            $val->extra_hours_cost = $val->extra_hours_cost ?? '';
            $val->item = $val->item ?? '';
            $val->hours = $val->hours ?? '';
            unset($val->order);
        }
        $duebalance = Invoice::selectRaw('sum(sub_total) as due')->where('user_id',auth()->user()->id)->where('status','0')->get();
        if(empty($duebalance)){
            $duebalance =  new \stdClass();
        }else{
            $duebalance = $duebalance[0]->due ?? '';
        }
        return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'success',
            'data'    => ['earning'=>$earnings,'due' => $duebalance]
        ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'Profile update time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

     
    //payment History
    public function PaymentHistory(){
      try{
      $data = Invoice::where('user_id',auth()->user()->id)->where('status','1')->get();
      foreach($data as $val){
            $val->payment_id = $val->payment_id ?? '';
            $val->service_name = $val->order->service->service_name;
            $val->schedule = \Carbon\Carbon::parse($val->order->schedule)->format('D, d, M ,Y');
            $val->hours = $val->order->hours;
            $val->commission = $val->order->tax ?? '';
            $val->trust_fees = $val->order->trust_fees ?? '';
            $val->hst = ($val->commission / 100) * $val->sub_total;
            $val->service_amount = $val->sub_total-$val->hst; 
            $val->total_amount = $val->hst+$val->service_amount;
        unset($val->order);
      }
            return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'success',
            'data'  => $data
        ]); 
      }catch(\Exception $e){
        $data = ['error_type'=>'Payment History Get Time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'App'];
        errorAdd($data);
        return response()->json([
            'status_code' => 500,
            'response' => 'error',
            'message' => 'something wrong !'
        ]); 
      }
    }

    //my feedback
    public function Feedback(){
        try{
            //$data = Array();
            //$getData = AssignOrder::where('status','4')->where('user_id',auth()->user()->id)->get();
            $getData = AssignOrder::select('order_id')->where('status','4')->where('user_id',auth()->user()->id)->get();
            $data = Review::wherein('order_id',$getData)->get();
            
            // foreach($getData as $val){            
            //     $data[] = Review::where('order_id',$val->order_id)->first();
            // }
          
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'success',
                'data'  => $data
            ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'feedback get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

    //Send Quote Request 
    public function SendQuote(Request $request,Notification $notification){
        $validator = Validator::make($request->all(), [ 
            'order_id' => 'required',
            'price' => 'required',
            'desc' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try{
             $order = Order::where('id',$request->order_id)->first();
             if(empty($order)){
                return response()->json([
                    'status_code' => 400,
                    'response' => 'error',
                    'message' => 'Order Not Found'
                ]);
             }else{
                $data= ['status'=>'10','quote_revived_time'=>date('Y/m/d H:i:s')];
                $check = AssignOrder::where('order_id',$request->order_id)->where('user_id',auth()->user()->id)->update($data);
                Order::where('id',$request->order_id)->update(['status'=>'10']);
                $reciver_id = $order->user_id;
                $sender_id = auth()->user()->id;
                $dataquote = ['sender_id'=> $sender_id,
                        'reciver_id' => $reciver_id,
                        'order_id' => $request->order_id,
                        'price' => $request->price,
                        'desc' => $request->desc,
                        'status' => '1'];
                        QuotePrice::create($dataquote);
                        $values = ['sender_id'=>auth()->user()->id,
                        'receiver_id'=>$reciver_id,
                        'from' =>'Notification',
                        'message'=> 'Provider has send a quote'];
                        Notification::create($values);
                        $notification->pushNotification($values);
                        return response()->json([
                            'status_code' => 200,
                            'response' => 'success',
                            'message' => 'Quote send success'
                        ]);
             }
        }catch(\Exception $e){
            $data = ['error_type'=>'feedback get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }

    //delete service
    public function deleteService(Request $request){
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required',
            'service_id' => 'required',
            'insurence_number'  => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try{
         $getdata = ProfessionalService::where('user_id',$request->user_id)->first();
         $service = explode(',', $getdata->service);
          $number = explode(',', $getdata->number);
          while(($i = array_search($request->insurence_number, $number)) !== false) {
            unset($number[$i]);
          break;
        }
    
        while(($i = array_search($request->service_id, $service)) !== false) {
            unset($service[$i]);
          break;
        }
       
         $service = implode(',',$service);
          $number = implode(',',$number);
          ProfessionalService::where('user_id',$request->user_id)->update(['service'=>$service,'number'=>$number]);
          return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'Service Deleted'
        ]); 
        }catch(\Exception $e){
            $data = ['error_type'=>'Delete Service Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]); 
        }

    }

    //Get State
    public function GetState(){
        try{
         $data = Province::get();
         return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'data'  => $data,
            'message' => 'Success !'
        ]); 
        }catch(\Exception $e){
            $data = ['error_type'=>'Get State Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]); 
        }
    }

    public function UpdateRegister(){
        try{
          User::where('id',auth()->user()->id)->update(['step'=>1]);
          return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'message' => 'Register Update Success !'
        ]); 
        }catch(\Exception $e){
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]); 
        }
    }

     /**
     * Forgot Password Start
     */
    public function forgotPassword(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
             $check = User::where('email',$request->email)->get();
             
             if(count($check) > 0){
                 if($check[0]->roles[0]->name != "provider"){
                    return response()->json([
                        'status_code' => 500,
                        'response' => 'error',
                        'message' => 'User Not Found'
                    ]);
                 }else{
                $randomid = mt_rand(100000,999999); 
                $data['otp'] = $randomid;
                $data['email'] = $request->email;
                $data['otp_verify'] = '1';
               
               User::where('email',$request->email)->update(['otp'=>$randomid,'otp_verify'=>'1']); 
                //otp send 
                $body = ['otp' => $randomid,'email' => $data['email'],];
    
                Mail::send('emails.resendotp', $body, function ($message) use ($data)
                {        
                    $message->from(env('MAIL_FROM_ADDRESS'), 'Faria Application');        
                    $message->to($data['email']);        
                    $message->subject('please verify your OTP.');        
                });
               $user = User::where('email',$request->email)->first();
                foreach ($user->toArray() as $key => $value) {
                        if($value == null){
                            $user[$key] = '';
                        }
                }
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'message' => 'otp send your email address.',
                    'data' => $user
                ]);
              }
             }else{
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => 'Email Not Found.',
                   
                ]);
             }

        }catch(\Exception $e){      
            $data = ['error_type'=>'Forgot Password Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
        }
    }
     /**
      * Forgot Password End
      */


      //service list get order by category id
    public function AllserviceList(){
        try{
            $data = Service::orderby('category_id','asc')->where('appstatus',1)->get();
            foreach($data as $val){
                $val->category_name = $val->category_get->category_name ?? '';
                $val->pickhours = $val->pickhours ?? '';
                unset($val->category_get);
            }
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'success',
                'data'  => $data
            ]); 
        }catch(\Exception $e){
            $data = ['error_type'=>'service list order by category name get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]); 
        }
    }

     // My service List
    public function myServiceList(){
        try{

            $alreadyServiceIds =  ProfessionalService::where('user_id',auth()->user()->id)->value('service');
            $alreadyServiceIds = explode(",",$alreadyServiceIds);

            $data1 = Service::orderby('category_id','asc')->where('appstatus',1)->whereIn('id',$alreadyServiceIds)->get();
            foreach($data1 as $val){
                $val->category_name = $val->category_get->category_name ?? '';
                $val->pickhours = $val->pickhours ?? '';
                unset($val->category_get);
            }

            $alreadyInServiceIds =  ProfessionalService::where('user_id',auth()->user()->id)->value('inactive_service');
            $alreadyInServiceIds = explode(",",$alreadyInServiceIds);

            $data2 = Service::orderby('category_id','asc')->where('appstatus',1)->whereIn('id',$alreadyInServiceIds)->get();
            foreach($data2 as $val){
                $val->category_name = $val->category_get->category_name ?? '';
                $val->pickhours = $val->pickhours ?? '';
                unset($val->category_get);
            }

            $data['active_services']   = $data1;
            $data['inactive_services'] = $data2;

            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'success',
                'data'  => $data
            ]); 
        }catch(\Exception $e){
            $data = ['error_type'=>'service list order by category name get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $e->getMessage()
            ]); 
        }
    }

     // Service list expect service which is already your
    public function addMoreServiceList(){
        try{

            $alreadyServices = [];

            $alreadyServiceIds =  ProfessionalService::where('user_id',auth()->user()->id)->value('service');
            $alreadyServiceIds = explode(",",$alreadyServiceIds);

            $alreadyInServiceIds =  ProfessionalService::where('user_id',auth()->user()->id)->value('inactive_service');
            $alreadyInServiceIds = explode(",",$alreadyInServiceIds);

            $alreadyServices = array_merge($alreadyServiceIds,$alreadyInServiceIds);

            $data = Service::orderby('category_id','asc')->where('appstatus',1)->whereNotIn('id',$alreadyServices)->get();
            foreach($data as $val){
                $val->category_name = $val->category_get->category_name ?? '';
                $val->pickhours = $val->pickhours ?? '';
                unset($val->category_get);
            }
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'success',
                'data'  => $data
            ]); 
        }catch(\Exception $e){
            $data = ['error_type'=>'service list order by category name get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]); 
        }
    }


    // Active/Inactive Service
    public function setServiceStatus(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'service_id' => 'required',
            'set_type' => 'required|in:1,2',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }

        try{

            $alreadyServiceIdsStr = ProfessionalService::where('user_id',auth()->user()->id)->value('service');
            $alreadyServiceIds = explode(",",$alreadyServiceIdsStr);

            $alreadyInServiceIdsStr = ProfessionalService::where('user_id',auth()->user()->id)->value('inactive_service');
            $alreadyInServiceIds = explode(",",$alreadyInServiceIdsStr);

            DB::beginTransaction();

            $reqService = $request->service_id;

            $serviceStr = "";
            $inactiveServiceStr = "";
            $message = "";

            if($request->set_type==2)
            {
                //remove from service and add in inactive service

                if(!in_array($reqService,$alreadyServiceIds))
                {
                    return response()->json([
                        'status_code' => 500,
                        'response' => 'error',
                        'message' => 'Invalid Service Id..',
                    ]);
                }

                $i=0;
                foreach($alreadyServiceIds as $list)
                {
                    if($list==$reqService)
                        array_splice($alreadyServiceIds,$i,1);
                
                    $i++;
                }
                $serviceStr = implode(",",$alreadyServiceIds);

                if(!empty($alreadyInServiceIdsStr))
                {
                    $inServiceSize = count($alreadyInServiceIds);
                    $alreadyInServiceIds[$inServiceSize] = $reqService;
                }

                if(empty($alreadyInServiceIdsStr))
                    $alreadyInServiceIds[0] = $reqService;

                $inactiveServiceStr = implode(",",$alreadyInServiceIds);

                $message = "Service set Inactive Successfully";

            }



            if($request->set_type==1)
            {
                //remove from service and add in inactive service

                if(!in_array($reqService,$alreadyInServiceIds))
                {
                    return response()->json([
                        'status_code' => 500,
                        'response' => 'error',
                        'message' => 'Invalid Service Id',
                    ]);
                }

                $i=0;
                foreach($alreadyInServiceIds as $list)
                {
                    if($list==$reqService)
                        array_splice($alreadyInServiceIds,$i,1);
                
                    $i++;
                }
                $inactiveServiceStr = implode(",",$alreadyInServiceIds);

                if(!empty($alreadyServiceIdsStr))
                {
                    $inServiceSize = count($alreadyServiceIds);
                    $alreadyServiceIds[$inServiceSize] = $reqService;
                }

                if(empty($alreadyServiceIdsStr))
                    $alreadyServiceIds[0] = $reqService;

                $serviceStr = implode(",",$alreadyServiceIds);
                
                $message = "Service set Active Successfully";

            }

            ProfessionalService::where('user_id',auth()->user()->id)->update(['service'=>$serviceStr,'inactive_service'=>$inactiveServiceStr]);
                
            DB::commit();

            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => $message,
                'data'  => "",
            ]); 
        }catch(\Exception $e){

            DB::rollback();

            $data = ['error_type'=>'service list order by category name get time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);

            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $e->getMessage()
            ]); 
        }
    }


    public function ChatNotificaion(Request $request){

            //userid = message reciver person
        $data = $request->all();
        $validator = Validator::make($data, [
          'userid' => 'required',
          'message'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }

        try{
            $userData = User::where('id',$request->userid)->first();
            $sender = User::where('id',auth()->user()->id)->first();
            
   if(!empty($userData->device_token)){
      $first_name = $sender->first_name;
      $last_name = $sender->last_name;
      $sendername = $first_name .' '.$last_name;
      if (!defined('Notifiction_API_ACCESS_KEY')){
        define( 'Notifiction_API_ACCESS_KEY', 'AAAAnWLICtc:APA91bEYaGmLcsPdPzF8NVGn3nqBb_-vc1Op6uQ8eGyrByNu4pnoyedt7jX5J0A9cRaYnNEYLjBGqSerJBlNqbSB30uB9lDBb2z0dR2MO8licUwu16h3RLeU2YiOUtROtur7L8tBY2e-');
      }
      
      $notification = array
      (
      "body" => $data['message'],
      "title" => $sendername,
      "vibrate" => 1,
      "sound" => 1,
      "badge" => 1,
      "color" => "#3364ac",
      "user_id" => $data['userid'],
      "sender_id"=>auth()->user()->id,
      "sender_name"=>auth()->user()->first_name.' '.auth()->user()->last_name,
      "tag"=>'notification screen',
      
      );
      $msg = array
      (
      "user_id" => $data['userid']
      );
      $registrationIds = $userData->device_token;
      $fields = array
      (
      "to" => $registrationIds,
      "priority" => "high",
      "notification" => $notification,
      "data" => $notification,
      
      );
      
      
      $headers = array(
      "Authorization:key = AAAAnWLICtc:APA91bEYaGmLcsPdPzF8NVGn3nqBb_-vc1Op6uQ8eGyrByNu4pnoyedt7jX5J0A9cRaYnNEYLjBGqSerJBlNqbSB30uB9lDBb2z0dR2MO8licUwu16h3RLeU2YiOUtROtur7L8tBY2e-",
      "Content-Type: application/json"
      );
      //dd(json_encode($fields));
      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
    //   echo "<pre>";
    //   print_r($result); die;
    //    dd($result);
      if (curl_error($ch)) {
      return curl_error($ch);
      }
      curl_close($ch);
      }
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'success',
                
            ]); 
        }catch(\Exception $e){
            dd($e);
            $data = ['error_type'=>'chat notification',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]); 
        }


    } 
   
}
