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
use App\Walkthrough;
use App\Reason;
use App\Page;
use App\ServiceProviderRequest;
use App\LoginData;
use App\StripeCard;
// use Stripe\Error\Card;
// use Cartalyst\Stripe\Stripe;
use Stripe;
use Log;
class UserController extends Controller
{
    //Walk Screen get
    public function WalkthroughGet(){
        try{
          $data = Walkthrough::get();
            foreach($data as $val){
              $val->body = trim(preg_replace('/\r\n\+/',' ', strip_tags($val->body)));
             }
           return response()->json([
               'status_code' => 200,
               'response' => 'success',
               'data' => $data
           ]); 
        }catch(\Exception $e){
            $data = ['error_type'=>'WalkThrow Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
           return response([
               'status_code' => 500,
               'response' => 'error',
               'message' => 'something wrong'
           ]);
        }
    }
    //End Walk

    //Cms Page Get
    public function webpages(){
        try{
            $data = Page::get();
              foreach($data as $val){
                $val->body = trim(preg_replace('/\r\n\+/',' ', strip_tags($val->body)));
               }
             return response()->json([
                 'status_code' => 200,
                 'response' => 'success',
                 'data' => $data
             ]); 
          }catch(\Exception $e){
            $data = ['error_type'=>'CMS Page Get Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
             return response([
                 'status_code' => 500,
                 'response' => 'error',
                 'message' => 'something wrong'
             ]);
          }
    }
    //End Cms

     //register user
     public function register(Request $request) 
     { 
        $validator = Validator::make($request->all(), [
            'RegisterFrom' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' =>  'This RegisterFrom is required.',
            ]);
        }

        $checkUserPhone = User::where(['mobile_code'=>$request->mobile_code,'mobile'=>$request->mobile])->pluck('id');
        $checkUserEmail = User::where('email',$request->email)->pluck('id');

        if($request->RegisterFrom == 'normaly'){
            $validator1 = Validator::make($request->all(), [
                'first_name' => 'required', 
                'last_name' => 'required',
                //'mobile' => 'required|unique:users',
                'mobile' => 'required',
                 //'email' => 'required|email|unique:users', 
                 'email' => 'required|email', 
                 'password' => 'required|min:6',               
                 //'device_token' => 'required',
                 'mobile_code' => 'required'
            ],[
                'email.unique' => 'Your email id is already registered. Please try to login',
                'mobile.unique' => 'Your phone number is already taken.'
            ]
        );
            if ($validator1->fails()) {
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => $validator1->messages()->first(),
                ]);
            }

            if($checkUserPhone)
            {
                $checkUserPhone = RoleUser::whereIn('user_id',$checkUserPhone)->where('role_id',3)->count();
                if($checkUserPhone>0)
                {
                    return response()->json([
                        'status_code' => 500,
                        'response' => 'error',
                        'message' => 'Your phone number is already taken.',
                    ]);
                }
            }

            if($checkUserEmail)
            {
                $checkUserEmail = RoleUser::whereIn('user_id',$checkUserEmail)->where('role_id',3)->count();
                if($checkUserEmail>0)
                {
                    return response()->json([
                        'status_code' => 500,
                        'response' => 'error',
                        'message' => 'Your email is already taken.',
                    ]);
                }
            }
        }
 

        if($request->RegisterFrom == 'provider'){
        
            $validator2 = Validator::make($request->all(), [
                'first_name' => 'required', 
                'last_name' => 'required',
                //'mobile' => 'unique:users',
                'mobile' => 'required',
                //'email' => 'required|email|unique:users', 
                'email' => 'required|email', 
                'password' => 'required|min:6',  
                'mobile_code'=>      'required',       
                //'device_token' => 'required'
            ]);
            if ($validator2->fails()) {
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => $validator2->messages()->first(),
                ]);
            } 

            if($checkUserPhone)
            {
                $checkUserPhone = RoleUser::whereIn('user_id',$checkUserPhone)->where('role_id',2)->count();
                if($checkUserPhone>0)
                {
                    return response()->json([
                        'status_code' => 500,
                        'response' => 'error',
                        'message' => 'Your phone number is already taken.',
                    ]);
                }
            }

            if($checkUserEmail)
            {
                $checkUserEmail = RoleUser::whereIn('user_id',$checkUserEmail)->where('role_id',2)->count();
                if($checkUserEmail>0)
                {
                    return response()->json([
                        'status_code' => 500,
                        'response' => 'error',
                        'message' => 'Your email is already taken.',
                    ]);
                }
            }   
        }
        try{

            DB::beginTransaction();

            $input = $request->all();
            //if(!empty($request->image)){ 
                if($request->hasFile('image')) {
                    $avatar = $request->file('image');
                    $avatarName = 'user_avatar'.time().'.'.$avatar->getClientOriginalExtension();
                    $avatar->move('storage/user/',$avatarName);
                    $path = url('/storage/user/'.$avatarName);
                    $input['image'] = $path;
                }
            //}
            $randomid = mt_rand(100000,999999); 
            $input['otp'] = $randomid;
            $input['password'] = bcrypt($input['password']); 
            unset($input['RegisterFrom']);
            unset($input['confirm_password']);
            $user = User::create($input);
            $countryCode = $user->mobile_code;
            $mobileNumber = preg_replace('/\D+/', '', $user->mobile); 
            $sendingNumber = $countryCode.$mobileNumber;

            //echo "SM-> $sendingNumber"; die;

            try {
                sendMessage('Your OTP is '.$randomid, $sendingNumber);
            } catch (\Execption $e) {

                User::where('email',$request->email)->delete();
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => 'This mobile number is not valid.',
                ]);
            }
            //otp send 
            $body = ['otp' => $randomid,'email' => $input['email'],];
            Mail::send('emails.resendotp', $body, function ($message) use ($input)
            {
                $message->from(env('MAIL_FROM_ADDRESS'), 'Faria Application');        
                $message->to($input['email']);
                $message->subject('Faria Account Register OTP.');        
            });
            if($request->RegisterFrom == 'provider'){
                $id = $user->id;
                $RoleUser = ['role_id' => 2,'user_id' => $user->id]; 
                RoleUser::insert($RoleUser);
                ServiceProviderRequest::create(['user_id'=>$id,'service_provide_status'=>'0']);
            }else{
                $RoleUser = ['role_id' => 3,'user_id' => $user->id]; 
                RoleUser::insert($RoleUser);
            }
            if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
                $user1 = Auth::user(); 
                $user['token'] =  $user1->createToken('token')->accessToken;
            }
            $user2 = User::where('email',$request->email)->first();
            foreach ($user2->toArray() as $key => $value) {
                if($value == null){
                    $user[$key] = '';
                }
            }
            $user->unit = '';
            $user->website = '';

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'data' => $user,
                'message' => 'OTP has been sent on your registered Email address.',
            ]);
        }
        catch(Exception $e){

           // Log::debug($e->getMessage());

            User::where('email',$request->email)->delete();

            $data = ['error_type'=>'Register Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);

            DB::rollback();

            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'Mobile number is not valid.',
                //'message' => $e->getMessage(),
            ]);
        }
     }
     //End Register

     //otp verify api
    public function OtpVerify(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required',
            'otp' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
           $email = $request->email;
           $otp = $request->otp;
           $check = User::where('email',$email)->where('otp',$otp)->get();
           if(count($check) > 0){
                User::where('email', $email)->update(['otp'=>'','otp_verify'=>'1']);
                if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
                    $user = Auth::user();
                    $user['token'] =  $user->createToken('token')->accessToken; 
                    foreach ($user->toArray() as $key => $value) {
                        if($value == null){
                            $user[$key] = '';
                        }
                    }
                    return response()->json([
                        'status_code' => 200,
                        'response' => 'success',
                        'data' => $user,
                        'message' => 'OTP Verifiy Succesfull.'
                    ]); 
                }else{
                    return response()->json([
                        'status_code' => 401,
                        'response' => 'Unauthorised',
                        'message' => 'Unauthorised'
                    ]); 
                }
                
           }else{
            return response()->json([
                'status_code' => 401,
                'response' => 'email or otp incorrect.',
                'message' => 'Incorrect OTP'
            ]); 
           }
        }
        catch(Exception $e){
            $data = ['error_type'=>'OTP Verify Time',
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
    //End Otp Verify

    /**
     * Re Send OTP Start
     */
    public function ResendOtp(Request $request){
         $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
            $check = User::where('email',$data['email'])->get();
            if(count($check) > 0){
            $randomid = mt_rand(100000,999999); 
            $data['otp'] = $randomid;         
            $user = User::where('email',$request->email)->update(['otp_verify'=>'0','otp'=>$randomid]); 
            //otp send 
            $body = [
                'otp' => $randomid,  
                'email' => $data['email'],                 
            ];
            Mail::send('emails.resendotp', $body, function ($message) use ($data)
            {        
                $message->from(env('MAIL_FROM_ADDRESS'), 'Faria Application');        
                $message->to($data['email']);        
                $message->subject('Faria Account Register OTP.');        
            });
            if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
                $user = Auth::user();
            
                $user['token'] =  $user->createToken('token')->accessToken; 
               
                foreach ($user as $key => $value) {
                    if($value == null){
                        $user[$key] = '';
                    }
                }
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'data' => $user,
                    'message' => ' Verification Succesfull.'
                ]); 
            }else{
                return response()->json([
                    'status_code' => 401,
                    'response' => 'Unauthorised',
                ]); 
            }
        }else{
            return response()->json([
                'status_code' => 402,
                'response' => 'User Not found',
                'message' => 'User Not Found!'
            ]);
        }

        }catch(\Exception $e){
            $data = ['error_type'=>'ReSend OTP Time',
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
     * End Resend OTP
     */

    /**
     * Login User
     */
    public function login(Request $request)
    {
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
        if($request->loginFrom == 'normaly'){
            $validator1 = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password'=> 'required',
                //'device_token'=> 'required',
            ],
            [
                'password.required' => 'Either email or password is incorrect. Please enter valid email and password',
               // 'mobile.unique' => 'Your phone number is already taken.'
            ]);
            if ($validator1->fails()) {
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => $validator1->messages()->first(),
                ]);
            }    
        }

        if($request->loginFrom == 'social'){
            $validator2 = Validator::make($request->all(), [
                'socialtoken' => 'required',
                'device_token'=> 'required',
            ]);
            if ($validator2->fails()) {
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => $validator2->messages()->first(),
                ]);
            }    
        }
        try{
            if($request->loginFrom == 'normaly' || $request->loginFrom == 'provider'){
                if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
                    $user = Auth::user(); 
                    if($user->status != 1){
                        return response()->json([
                            'status_code' => 300,
                            'response' => 'error',
                            'message' => 'Your account is temporarily inactive. Please contact ServiceFellow Support.'
                        ]);
                    }else{
                        if($user->otp_verify == 0){
                            $randomid = mt_rand(100000,999999); 
                            $input['otp'] = $randomid;
                            $input['email'] = $request->email;
                            $input['device_token'] = !empty($request->device_token)?$request->device_token:'';
                            $input['otp_verify'] = 0;
                            User::where('email',$request->email)->update($input); 
                            //otp send 
                            $body = [
                                
                                'otp' => $randomid,  
                                'email' => $input['email'],                 
                            ];
                            Mail::send('emails.resendotp', $body, function ($message) use ($input)
                            {        
                                $message->from(env('MAIL_FROM_ADDRESS'), 'Faria Application');        
                                $message->to($input['email']);        
                                $message->subject('please verify your OTP.');        
                            });
                            $user->otp = $randomid;
                            $countryCode = $user->mobile_code;
                            $mobileNumber = preg_replace('/\D+/', '', $user->mobile); 
                            $sendingNumber = $countryCode.$mobileNumber;
                            sendMessage('Your OTP is '.$randomid, $sendingNumber);
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
                        'message' => 'Either email or password is incorrect. Please enter valid email and password'
                    ]);
                }
            }
            if($request->loginFrom == 'social'){
                if(Auth::attempt(['socialtoken' => request('socialtoken'), 'password' => '1234567890'])){ 
                    $user = Auth::user();
                    if($user->status != 1){
                        return response()->json([
                            'status_code' => 401,
                            'response' => 'error',
                            'message' => 'please contact admin.'
                        ]);    
                    }
                }else{ 
                    //if new social user 
                    $validator3 = Validator::make($request->all(), [ 
                        'name' => 'required',
                        'email' => 'email|unique:users', 
                        'device_token' => 'required',
                        'socialtoken'  => 'required|unique:users', 
                    ]);
                    if ($validator3->fails()) { 
                        return response()->json([
                            'status_code' => 401,
                            'response' => 'error',
                            'message' => $validator3->messages()->first(),
                        ]);           
                    }
                    $input = $request->all();
                    $input['password'] = bcrypt('1234567890'); 
                    $input['otp_verify'] = '1';
                  
                   
                    unset($input['loginFrom']);
                   
                    $users = User::create($input); 
                    $RoleUser = [
                        'role_id' => 3,
                        'user_id' => $users->id
                    ]; 
                    RoleUser::insert($RoleUser);
                    if(Auth::attempt(['socialtoken' => request('socialtoken'), 'password' => '1234567890', 'status' => 1])){ 
                        $user = Auth::user(); 
                    }else{
                        return response()->json([
                            'status_code' => 401,
                            'response' => 'error',
                            'message' => 'Unauthorised'
                        ]);
                    }
                }
            }

            //check role
            if(isset($user) && !empty($user->roles) && ($user->roles[0]->name == 'user' || $user->roles[0]->name == 'provider')){
                //$input['device_token'] = $request->device_token;
                $input['device_token'] = !empty($request->device_token)?$request->device_token:'';
                           
                User::where('email',$request->email)->update($input); 
                $user['token'] =  $user->createToken('token')->accessToken; 
                unset($user['roles']);
                $user->address = $user->getaddress->address ?? '';
                $user->lati = $user->getaddress->lat ?? '';
                $user->longti = $user->getaddress->long ?? '';
                $user->state = $user->getaddress->state ?? '';
                $user->mobile_code = $user->mobile_code;
                $user->save_card = $this->Savecard($user->id);
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
                    'message' => 'your account type is Service Provider user.'
                ]);
            }
        }
        catch(Exception $e){
            //echo '<pre>';print_r($e->getMessage());exit;
            $data = ['error_type'=>'Login Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'.$data,
            ]);
        }
    }
    //End Login Process


//check save card
   public function Savecard($id){
       try{
          $data = StripeCard::where('user_id',$id)->first();
          if(!empty($data)){
              return 1;
          }else{
              return 2;
          }
       }catch(\Exception $e){
        $data = ['error_type'=>'Login Time',
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
     * Forgot Password Start
     */
    public function forgotPassword(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, ['email' => 'required']);
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
                $randomid = mt_rand(100000,999999); 
                $data['otp'] = $randomid;
                $data['email'] = $request->email;
                $data['otp_verify'] = '1';
               User::where('email',$request->email)->update(['otp'=>$randomid,'otp_verify'=>'1']); 
                //otp send 
                $body = ['otp' => $randomid,  'email' => $data['email'], ];
                Mail::send('emails.resendotp', $body, function ($message) use ($data)
                {
                    $message->from(env('MAIL_FROM_ADDRESS'), 'Faria Application');        
                    $message->to($data['email']);        
                    $message->subject('please verify your OTP.');        
                });
               $user = User::where('email',$request->email)->first();
               //$sendSmsUser = User::where('email',$request->email)->first();
                $countryCode = $user->mobile_code;
                $mobileNumber = preg_replace('/\D+/', '', $user->mobile); 
                $sendingNumber = $countryCode.$mobileNumber;
                sendMessage('Your OTP is '.$randomid, $sendingNumber);
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

      /**
       * After Forgot Password Match Otp
       */
      public function AfterMAtch(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required',
            'otp' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
             $check = User::where('email',$request->email)->where('otp',$request->otp)->get();
             if(count($check) > 0){
              User::where('email',$request->email)->update(['otp'=>NULL,'otp_verify'=>1]);
              $user = User::where('email',$request->email)->first();
              foreach ($user->toArray() as $key => $value) {
               if($value == null){
                    $user[$key] = '';
                }
              }
               return response()->json(['status_code' => 200, 'response' => 'success',
                   'message' => 'otp verify Success.',
                   'data' => $user
               ]);

             }else{
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => 'Otp Not Match.',
                   
                ]);
             }
        }catch(\Exception $e){
            $data = ['error_type'=>'After Forgot password otp match',
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
      //End

      /**
       * Create New Password
       */
      public function NewPassword(Request $request){      
        $data = $request->all();
        $validator = Validator::make($data, [
        'email' => 'required', 
        'password' => 'required',
        'confirm_password' => ['same:password','required'],
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
               $password = bcrypt($data['password']); 
               User::where('email',$request->email)->update(['password' => $password]);

               if(Auth::attempt(['email' => $request->email, 'password' => request('password')])){ 
                $user = Auth::user();
            
                $user['token'] =  $user->createToken('token')->accessToken; 
               
                foreach ($user as $key => $value) {
                    if($value == null){
                        $user[$key] = '';
                    }
                }
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'data' => $user,
                    'message' => ' Password Set Succesfull.'
                ]); 
            }
              
             }else{
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => 'User Not Found.',
                   
                ]);
             }
          }catch(\Exception $e){
            $data = ['error_type'=>'Password Change Time',
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

      public function updateLatLng(Request $request){
        $validator = Validator::make($request->all(), [ 
            'lat' => 'required',
            'lng' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
          }
            try{
                
//dd(auth()->user()->email);
                DB::table('users')
        ->where('email', auth()->user()->email) 

        ->update(array('lat' => $request->lat,'long'=>$request->lng));  
                
             
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'message' => 'User location update successfully'
                ]);
            }catch(\Exception $e){
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => $e->getLine().' - '.$e->getMessage()
                ]);
            }       
    
      }

      /**
       * User Profile Get
       */
      public function GetProfile(){
          try{
            //\Artisan::Call('migrate');
            $user = User::where('id',auth()->user()->id)->first();
            $card = StripeCard::where('user_id',auth()->user()->id)->get();
           if(count($card)>0){
             \Stripe\Stripe::setMaxNetworkRetries(4);
            \Stripe\Stripe::setApiKey('sk_test_51H7fccLVBFA034ip2OJyNrnCGaiycIi4iTv33KkXZKedabOiXSvOMqdCJsnymHIFKHcPUUfeWGK9bPJCmbOsA7du00QMvu0iD0');   
            $stripe = new \Stripe\StripeClient(
                'sk_test_51H7fccLVBFA034ip2OJyNrnCGaiycIi4iTv33KkXZKedabOiXSvOMqdCJsnymHIFKHcPUUfeWGK9bPJCmbOsA7du00QMvu0iD0'
            );
            $cardDetail  =array();
            foreach($card as $key => $val){
                //   $val->customer_detail =  \Stripe\Customer::retrieve($val->stripe_id);
                $cardDetail[$key] =  \Stripe\Customer::retrieveSource(
                    $val->stripe_id,$val->card_id
                );
            }
  
        }else{
            $cardDetail = [];
        }

       
            foreach ($card as $key => $value) {
                foreach ($cardDetail as $k => $v) {
                    if($value->card_id == $v->id){
                        $v->date = date_format($value->created_at,"d,M,Y");
                        $v->status = $value->status;
                    }
                }    
            }
            foreach ($user->toArray() as $key => $value) {
                if($value == null){
                    $user[$key] = '';
                }
            }
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'data' => ['user' => $user,'card_detail' => $cardDetail]
            ]); 
          }catch(\Exception $e){
             
            // dd($e->getError()->type);
            // $data = ['error_type'=>'Profile Get Time',
            // 'error_message'=>$e->getMessage(),
            // 'error_ref'=>$e->getFile(),
            // 'which_side' => 'App'];
            // errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
          }
      }
      /**End */

      /**
       * Update Profile
       */
      public function UpdateProfile(Request $request){
        $validator = Validator::make($request->all(), [ 
            'first_name' => 'required', 
            'last_name' => 'required',
            // 'mobile_code' => 'required', 
             'mobile' => 'required|unique:users,mobile,'.auth()->user()->id,
        ]
        ,[
            'mobile.unique' => 'Your phone number is already taken.'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try{
            if($request->address != ''){
                $checkAdd = UserAddress::where('user_id',auth()->user()->id)
                ->where('address',$request->address)
                ->first();
                if(empty($checkAdd)){
                    $userAddress = new UserAddress;
                    $userAddress->user_id = auth()->user()->id;
                    $userAddress->lat = $request->lat;
                    $userAddress->long = $request->long;
                    $userAddress->address = $request->address;
                    $userAddress->state = $request->state;
                    $userAddress->city = $request->city;
                    $userAddress->save();
                }
            }
            $input = $request->all();
            if($request->address == ''){
                unset($input['lat']);
                unset($input['long']);
                unset($input['address']);
            }
            unset($input['state']);
            if($request->device_token != ''){
                $input['device_token']   = $request->device_token;
            }
         $userInfo = User::where('id',auth()->user()->id)->first();
         $userInfo->first_name = $input['first_name'];
         $userInfo->last_name = $input['last_name'];
         $userInfo->lat = $input['lat'];
         $userInfo->address = $input['address'];
         $userInfo->long = $input['long'];
         $userInfo->mobile = $input['mobile'];
         $userInfo->mobile_code = $input['mobile_code'];
         $userInfo->country = $input['country'];
         if($request->hasFile('image')) {
            $avatar = $request->file('image');
            $avatarName = 'image'.time().'.'.$avatar->getClientOriginalExtension();
            $avatar->move('storage/user/',$avatarName);
            $path = url('/storage/user/'.$avatarName);
            $userInfo->image = $path;
        }
         $userInfo->save();
         $user = User::where('id',auth()->user()->id)->first();
         foreach ($user->toArray() as $key => $value) {
            if($value == null){
                $user[$key] = '';
            }
        }
        return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'data' => $user,
            'message' => 'Profile Update Success'
        ]); 
        }catch(\Exception $e){
            $data = ['error_type'=>'Profile Get Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $data
            ]);
        }
      }
      //End

      /**
       * Password Change
       */
      public function ChangePassword(Request $request){
        $validator = Validator::make($request->all(), [ 
            'old_password' => 'required', 
            'new_password' => 'required|min:6',
            'confirm_password' => ['same:new_password','required']
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }

        try{
            $user = User::where('id',auth()->user()->id)->first();
            if (Hash::check($request->old_password, $user->password)) { 
                $user->fill([
                 'password' => Hash::make($request->new_password)
                 ])->save();
             
                 foreach ($user->toArray() as $key => $value) {
                    if($value == null){
                        $user[$key] = '';
                    }
                }
                
                 return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'data' => $user,
                    'message' => 'Password Change Success'
                ]); 
             
             }else{
                return response()->json([
                    'status_code' => 400,
                    'response' => 'error',
                    'message' => 'Old/Current Password is not Correct'
                ]);  
             }
        }catch(\Exception $e){
            $data = ['error_type'=>'Password Change Time',
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
       * Card Save
       */
      public function CardSave(Request $request){
        $validator = Validator::make($request->all(), [ 
            'card_no' => 'required', 
            'ccExpiryMonth' => 'required',
            'ccExpiryYear' => 'required',
            'cvvNumber' => 'required',
            'card_holder_name' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
        try{
        
            \Stripe\Stripe::setApiKey('sk_test_51H7fccLVBFA034ip2OJyNrnCGaiycIi4iTv33KkXZKedabOiXSvOMqdCJsnymHIFKHcPUUfeWGK9bPJCmbOsA7du00QMvu0iD0');   
            
            //Save card detail
            try{
            $card = \Stripe\PaymentMethod::create([
                'type' => 'card',
                'card' => [
                    'number' => $request->get('card_no'),
                    'exp_month' => $request->get('ccExpiryMonth'),
                    'exp_year' => $request->get('ccExpiryYear'),
                    'cvc' => $request->get('cvvNumber')
                ],
            ]);
            }catch(\Exception $e){
                return response()->json([
                    'status_code' => 400,
                    'response' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
         
            $response = \Stripe\Token::create(array(
                "card" => array(
                    'number' => $request->get('card_no'),
                    'exp_month' => $request->get('ccExpiryMonth'),
                    'exp_year' => $request->get('ccExpiryYear'),
                    'cvc' => $request->get('cvvNumber'),
                    "name"  => $request->get('card_holder_name')
              )));
             
            $response_array = $response->toArray(true);
            //Credit card one time use token
            $token = $response_array['id'];
            $fingerprint = $response_array['card']['fingerprint'];
            $check =  StripeCard::where('user_id',auth()->user()->id)->where('fingerprint',$fingerprint)->first();
            if(!empty($check)){
                return response()->json([
                    'status_code' => 400,
                    'response' => 'error',
                    'message' => 'Card already save'
                ]);
            }
            else{
                $customer = Stripe\Customer::create([
                    'email' => auth()->user()->email,
                    "name"  => auth()->user()->first_name . " " . auth()->user()->last_name,
                    'source' => $token,
                ]);

           
                $customerId = $customer->id;
                //Attach a PaymentMethod to a Customer
                $card->attach([
                    'customer' => $customerId,
                    ]);
                    if($customerId){
                        $check = StripeCard::where('user_id',auth()->user()->id)->first();
                        if(empty($check)){
                            $data = ['user_id'=>auth()->user()->id,'stripe_id'=>$customerId,'card_id'=>$response['card']->id,'fingerprint'=>$fingerprint,'status' => '1'];
                        }else{
                            $data = ['user_id'=>auth()->user()->id,'stripe_id'=>$customerId,'card_id'=>$response['card']->id,'fingerprint'=>$fingerprint];
                        }
                        
                        
                        StripeCard::create($data); 
                        return response()->json([
                            'status_code' => 200,
                            'response' => 'success',
                            'message' => 'Card Save Success'
                        ]);
                    }else{
                        return response()->json([
                            'status_code' => 400,
                            'response' => 'error',
                            'message' => 'Your Fill Information Wrong'
                        ]);
                    }
            }
        }catch(\Stripe\Exception\CardException $e){
          
          //dd($e->getError()->type);
            $data = ['error_type'=>'Card Save Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            if($e->getError()->type == "card_error"){
                $message = "Invalid credit card details. Please enter valid information.";
            }else{
                $message = "something wrong !"; 
            }
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $message
            ]);
        }

      }
      //End Card Save

      /**
       * Update Card Detail
       */
      public function UpdateCard(Request $request){
        $validator = Validator::make($request->all(), [ 
            'card_no' => 'required', 
            'ccExpiryMonth' => 'required',
            'ccExpiryYear' => 'required',
            'cvvNumber' => 'required',
            'stripe_id' => 'required',
            'name'  => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
           try{
           
            \Stripe\Stripe::setApiKey('sk_test_51H7fccLVBFA034ip2OJyNrnCGaiycIi4iTv33KkXZKedabOiXSvOMqdCJsnymHIFKHcPUUfeWGK9bPJCmbOsA7du00QMvu0iD0');   
             
            $response = \Stripe\Token::create(array(
                "card" => array(
                    'number' => $request->get('card_no'),
                    'exp_month' => $request->get('ccExpiryMonth'),
                    'exp_year' => $request->get('ccExpiryYear'),
                    'cvc' => $request->get('cvvNumber'),
                    "name"  => auth()->user()->first_name . " " .auth()->user()->last_name
              )));
             
            $response_array = $response->toArray(true);
            //Credit card one time use token
            $token = $response_array['id'];

            $card = Stripe\Customer::update(
                $request->stripe_id,
                [
                    'source' => $token
                ]
            );
              return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'Card Update Success'
            ]);

           }catch(\Exception $e){
               
                $data = ['error_type'=>'Card Save Time',
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

      //Resion get 
      public function ReasonGet(){
          try{
           $data = Reason::get();
           return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'data' => $data,
            'message' => 'Success'
        ]);
          }catch(\Exception $e){
            $data = ['error_type'=>'Reason Get Time',
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

      //logout
      public function LogoutUser(){
          try{
            // if (Auth::check()) {
            //     Auth::user()->Token()->delete();
            //  }
            $token = auth()->user()->token();
            $token->revoke();
             return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'User Logout Success.'
            ]);
          }catch(\Exception $e){
            $data = ['error_type'=>'Logout Time',
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

      //Delete Customer Address
      public function DeleteAddress(Request $request){
        $validator = Validator::make($request->all(), [ 
            'id' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
        }
          try{
            $check = UserAddress::where('id',$request->id)->first();
            if(empty($check)){
                return response()->json([
                    'status_code' => 401,
                    'response' => 'error',
                    'message' => "Address Not Found"
                ]);
            }else{
                UserAddress::where('id',$request->id)->where('user_id',auth()->user()->id)->delete();
                $data = UserAddress::where('user_id',auth()->user()->id)->get();
                return response()->json([
                    'status_code' => 200,
                    'response' => 'Success',
                    'message' => "Address Delete Success",
                    'data' => $data
                ]);
            }
          }catch(\Exception $e){
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => 'something wrong !'
            ]);
          }
      }

      
}
