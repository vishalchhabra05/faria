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
use App\Walkthrough;
use App\Invoice;
use App\OrderImage;
use App\Page;
use App\ServiceProviderRequest;
use App\LoginData;
use App\Setting;
use App\StripeCard;
use App\OrderQuotation;
// use Stripe\Error\Card;
// use Cartalyst\Stripe\Stripe;
use Stripe;
use App\Service;
use App\CountryCode;
use App\RideBooking;
use App\Category;
use App\ProfessionalService;
use App\Price;
use App\ServiceTex;
use App\Order;
use App\AssignOrder;
use App\UserAddress;
use App\CancelJob;
use App\CancelPriceMaster;
use App\Review;
use App\Dispute;
use Carbon\Carbon;
use App\Notification;
use App\ContactUs;
use App\QuotePrice;
use App\Banner;
use App\Discount;
use Log;
class CustomerController extends Controller
{
    /**Home Screen Api */
    public function HomeScreen(){
        try{
          $data = Service::where('appstatus','1')->get();
          $offer = Banner::where('status','1')->get();
          foreach($data as $val){
              $val->category_name = $val->category_get->category_name;
              $val->type = $val->category_get->type;
          }
          User::where('id', auth()->user()->id)->update(array('notification_count' => '0'));
          return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'data' => $data,
            'offer' => $offer
        ]); 
        }catch(\Exception $e){
            $data = ['error_type'=>'Home Screen Service Time',
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
    /**
     * All Service get with category name
     */
    public function ServiceGet(){
        try{
            $data = Service::get();
            foreach($data as $val){
                $val->category_name = $val->category_get->category_name;
                $val->type = $val->category_get->type;
                unset($val->category_get);
            }
           
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'data' => $data
            ]); 
        }catch(\Exception $e){
            $data = ['error_type'=>'Get Service Time',
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
    /**End Service */

    /**
     * Get All Contery code with name
     */
    public function GetCountery(){
        try{
            $data = CountryCode::get();
            foreach($data as $val){
                $val->phonecode = '+'.''.$val->phonecode;
            }
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'data' => $data,
                'message' => 'success'
            ]); 
        }catch(\Exception $e){
            $data = ['error_type'=>'Get Phone Code Time',
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
    /**End Country */

    /**
     * Category Get
     */
    public function CategoryGet(){
        try{
            $data = Category::where('status','1')->get();
           
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'data' => $data,
                'message' => 'success'
            ]); 
        }catch(\Exception $e){
            $data = ['error_type'=>'Get Category Time',
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
    /**End Category */

    public function getServicecheck(Request $request){
       try{
        if($request->slug == '0' && $request->name != ''){    
           // $data = Service::get();
            $data = Service::where('service_name','like',$request->name.'%')->get();
           }
        else if($request->slug == '0'){
            $data = Service::orderBy('service_name', 'ASC')->get();
            //$data = [];
        }
        else if($request->slug != '0' && $request->name == ''){
            $data = Service::where('category_id',$request->slug)->orderBy('service_name', 'ASC')->get();
        }
        else{           
            // $data = Service::where('category_id',$request->slug);
            //     if($request->name != ''){
            //         $data = $data->where('service_name','like',$request->name.'%');
            //     } 
            //     $data= $data->get();  
                if($request->slug != '0' && $request->name != ''){
                    $data = Service::where('category_id',$request->slug)->where('service_name','like',$request->name.'%')->get(); 
                }else{
                    $data = [];
                }
           }
           
            $result = [];
            foreach($data as $key=> $val){
                //$val->category_data = Category::where('status','1')->where('id',$val->category_id)->get();
                //$val->category = $val->category;
               
                if($val->category){
                    $val->category_name = $val->category->category_name;
                    $val->type = $val->category->type;
                      $result[] = $val;
                }
            }
        $service['all_service'] = $result;

        // $category = Category::where('status','1')->get();
        // foreach($category as $val){
        //     $service[$val->category_name] = Service::where('category_id',$val->id)->get();
        // }
        $service['all_category'] = Category::where('status','1')->get();

        return response()->json([
            'status_code' => 200,
            'response' => 'success',
            'data' => $service,
            'message' => 'success'
        ]); 

       }catch(\Exception $e){
         
        $data = ['error_type'=>'Testing Time',
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

    /**
     * Check My Area Service
     */
    public function CheckService(Request $request){
        //Log::debug($request);die;
        $data = $request->all();
        $validator = Validator::make($data, [
            'lat' => 'required',
            'long' => 'required',
            'address' => 'required',
            // 'unit' => 'required',
            //'city'  => 'required',
            'service_id' => 'required',
            'is_save_address' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
            if($request->is_save_address == '1'){
                $checkAdd = UserAddress::where('user_id',auth()->user()->id)
                                        ->where('address',$request->address)
                                        ->first();
                if(empty($checkAdd)){
                    $userAddress = new UserAddress;
                    $userAddress->user_id = auth()->user()->id;
                    $userAddress->lat =$request->lat;
                    $userAddress->long = $request->long;
                    $userAddress->address = $request->address;
                    $userAddress->state = $request->state;
                    $userAddress->city = $request->city;
                    $userAddress->save();
                    //UserAddress::create(['user_id'=>auth()->user()->id,'lat'=>$request->lat,'long'=>$request->long,
                   // 'address' => $request->address,'state'=>$request->state,'city'=>$request->city]);
                }
            }
            User::where('id',auth()->user()->id)->update(['lat'=>$request->lat,'long'=>$request->long]);
            $data = ProfessionalService::with('user.bussiness')->where(function ($query) use($request){
                $query->orWhereRaw('FIND_IN_SET(?,service)',$request->service_id);
            })->get();
            $users = array();
            foreach ($data as $key => $value) {
                //$users[$key] = $value->bussiness;
                $users[$key] = $value->user->bussiness;
            }

            $seviceUser = array();
            $current_lat = $request->lat; 
            $current_long = $request->long;
            $service_id = $request->service_id;
            $state = $request->state;
            
            if(!empty($users)){
                foreach($users as $key => $item){
                    //if($item->lat != "" && $item->long != ""){
                    if(!empty($item->lat) && !empty($item->long)){
                        $km = $this->getDistance($item,$current_lat,$current_long);
                        if($km != 'false'){
                            $price = $this->Minimum();
                            //$price = $this->Minimum($service_id,$request->city);
                            if(!empty($price)){
                                //+ $price->service->category_get->trust + $price->service->commission
                                $item->minimum_price = $price;
                                $item->trust_fees = "0".'%';
                                $item->tax = "0".'%';
                                //$item->minimum_price = $price->price;
                                //$item->trust_fees = $price->service->category_get->trust.'%' ?? '';
                                //$item->tax = $price->service->commission.'%' ?? '';
                            }else{
                                $item->minimum_price = 0;
                                $item->trust_fees = "0".'%';
                                $item->tax = "0".'%';
                            }
                            $item->extra_price = '';
                            $item->extra_hours = '';
                            //$item->extra_price = $price->extra_price ?? '';
                            //$item->extra_hours = $price->price ?? '';
                            //$item->service_name = $price->service->service_name ?? '';
                            $item->service_name = $this->serviceName($service_id) ?? '';
                            $item->service_id = $service_id;
                            $seviceUser[] = json_decode($km,true);
                            //$seviceUser[] = $item;
                            //$item->tax = $this->Gettax($state).'%';
                            
                        }
                    }
                }
            }
            if(count($seviceUser) > 0){
                // if(!empty($seviceUser)){
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'data' => $seviceUser[0]
                ]);
            }else{
                return response()->json([
                    'status_code' => 400,
                    'response' => 'success',
                   'message' => 'user not found'
                ]);
            }
        }catch(\Exception $e){
            $data = ['error_type'=>'Check My Area Service Time',
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

   

    /**
     * Assine Service   'unit' => 'required',
     */
    public function AssineService(Request $request,Notification $notification){
        $data = $request->all();
        $validator = Validator::make($data, [
            'lat' => 'required',
            'long' => 'required',
            'address' => 'required',
            // 'city'  => 'required',
            'service_id' => 'required',
            // 'card_no' => 'required', 
            // 'ccExpiryMonth' => 'required',
            // 'ccExpiryYear' => 'required',
            // 'cvvNumber' => 'required',
            // 'card_holder_name' => 'required',
            // 'card_save'    => 'required',
            // 'card_id'   => 'required'
        ]);
        if($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{

            // if($request->card_id != "0"){
            //     StripeCard::where('user_id',auth()->user()->id)->update(['status'=>'0']);
            //     StripeCard::where('card_id',$request->card_id)->update(['status'=>'1']);
            // }else{
            // \Stripe\Stripe::setApiKey('sk_test_51H7fccLVBFA034ip2OJyNrnCGaiycIi4iTv33KkXZKedabOiXSvOMqdCJsnymHIFKHcPUUfeWGK9bPJCmbOsA7du00QMvu0iD0');   
            
            // //Save card detail
            // try{
            // $card = \Stripe\PaymentMethod::create([
            //     'type' => 'card',
            //     'card' => [
            //         'number' => $request->get('card_no'),
            //         'exp_month' => $request->get('ccExpiryMonth'),
            //         'exp_year' => $request->get('ccExpiryYear'),
            //         'cvc' => $request->get('cvvNumber')
            //     ],
            // ]);
            // }catch(\Exception $e){
            //     return response()->json([
            //         'status_code' => 400,
            //         'response' => 'error',
            //         'message' => $e->getMessage()
            //     ]);
            // }
         
            // $response = \Stripe\Token::create(array(
            //     "card" => array(
            //         'number' => $request->get('card_no'),
            //         'exp_month' => $request->get('ccExpiryMonth'),
            //         'exp_year' => $request->get('ccExpiryYear'),
            //         'cvc' => $request->get('cvvNumber'),
            //         "name"  => $request->get('card_holder_name')
            //   )));
             
            // $response_array = $response->toArray(true);
            // //Credit card one time use token
            // $token = $response_array['id'];
            //  $fingerprint = $response_array['card']['fingerprint'];
            // // $check =  StripeCard::where('id',auth()->user()->id)->where('fingerprint',$fingerprint)->first();
           
            //     $customer = Stripe\Customer::create([
            //         'email' => auth()->user()->email,
            //         "name"  => auth()->user()->first_name . " " . auth()->user()->last_name,
            //         'source' => $token,
            //     ]);

           
            //     $customerId = $customer->id;
            //     //Attach a PaymentMethod to a Customer
            //     $card->attach([
            //         'customer' => $customerId,
            //         ]);
            //         if($customerId){
            //             if($request->card_save == '1'){
            //                 $save_card = '1';
            //             }else{
            //                 $save_card = '2';

            //             }
            //             $data = ['user_id'=>auth()->user()->id,'stripe_id'=>$customerId,
            //             'card_id'=>$response['card']->id,'fingerprint'=>$fingerprint,'card_save' => $save_card,
            //         'status' => '1'];
                        
            //             StripeCard::create($data); 
            //             // return response()->json([
            //             //     'status_code' => 200,
            //             //     'response' => 'success',
            //             //     'message' => 'Card Save Success'
            //             // ]);
            //         }else{
            //             return response()->json([
            //                 'status_code' => 400,
            //                 'response' => 'error',
            //                 'message' => 'Your Fill Information Wrong'
            //             ]);
            //         }
            // }

            $data = ProfessionalService::with('user.bussiness')->where(function ($query) use($request){
                $query->orWhereRaw('FIND_IN_SET(?,service)',$request->service_id);
            })->get();
            $users = array();
            foreach ($data as $key => $value) {
                //$users[$key] = $value->user;
                $users[$key] = $value->user->bussiness;
            }
            $seviceUser = array();
            $current_lat = $request->lat; 
            $current_long = $request->long;
            $service_id = $request->service_id;
            $state = $request->state;
            foreach($users as $key => $item){
                if(!empty($item->lat) && !empty($item->long)){
                //if($item->lat != "" && $item->long != ""){
                    $km = $this->getDistance($item,$current_lat,$current_long);
                    if($km != 'false'){
                        //$seviceUser[$key] = $km;
                        if(!empty($price)){
                            //$price = $this->Minimum($service_id,$request->city);
                            $price = $this->Minimum();
                            $item->minimum_price = $price;
                            $item->extra_hours = '';
                            $item->trust_fees = '';
                            //$item->minimum_price = $price->price;
                            //$item->extra_hours = $price->extra_price;
                            // $item->trust_fees = $price->service->category_get->trust;
                            //$item->service_name = $price->service->service_name;
                            $item->service_name = $this->serviceName($service_id) ?? '';
                        }else{
                            $item->minimum_price = 0;
                            $item->extra_hours = '';
                            $item->trust_fees = '';
                            $item->service_name = $this->serviceName($service_id) ?? '';
                        }
                        $item->tax = $this->Gettax($state);
                        //$provider_id = $km->user_id;
                        $seviceUser[] = json_decode($km,true);
                    }
                }
            }

            // start booking
            //$price1 = $this->Minimum($service_id,$request->city);
            $price1 = $this->Minimum();
            $input = $request->all();
            //$input['user_id'] = $provider_id;
            $input['user_id'] = auth()->user()->id;
            // $input['trust_fees'] = $price1->service->category_get->trust ?? '';
            $input['trust_fees'] = $this->commicen($request->service_id);
            // $this->Gettax($state)
           
            $check_service = Service::where('id',$request->service_id)->first();
            $input['tax'] = strval($check_service->category_get->trust) ?? '5';
            if(!empty($check_service) && !empty($check_service->category_get) && $check_service->category_get->type == '0'){
                $input['min_price'] = 0;
            }else{
                $input['min_price'] = $price1 ?? 0;
                $input['extra_price'] = 0;
                //$input['min_price'] = $price1->price ?? 0;
                //$input['extra_price'] = $price1->extra_price ?? 0;
            }
          
            //Check Promo Code
            if($request->promo != ''){
                $check_promo = Discount::where('coupon',$request->promo)->first();
                if(!empty($check_promo)){
                    $input['promo']  = $request->promo;
                }
                else{
                    return response()->json([
                        'status_code' => 500,
                        'response' => 'error',
                        'message' => 'Invalid Promo'
                    ]);
                }
            }else{
                $input['promo']  = '';
            }
            if($request->quick_service == ''){
                $input['quick_service'] =0;
            }else{
                $input['quick_service'] =1;
            }
            $data =  Order::create($input);

            if(!empty($request->images)){
                $images = $request->images;
                //echo "<pre>"; print_r($images); die;
                foreach ($images as $key => $image) {
                    $avatar = $image;
                    $fileName = explode(".",$avatar->getClientOriginalName());
                    $avatarName = $fileName[0].'.'.$avatar->getClientOriginalExtension();
                    $avatar->move('storage/orderimage/',$avatarName);
                    $path = url('/storage/orderimage/'.$avatarName);
                    $orderImage = new OrderImage;
                    $orderImage->order_id = $data->id;
                    $orderImage->image = $path;
                    $orderImage->save();
                }
            }
            
            $checkAdd = UserAddress::where('user_id',auth()->user()->id)
                                    ->where('address',$request->address)
                                    ->first();
            if(empty($checkAdd)){
                UserAddress::create(['user_id'=>auth()->user()->id,'lat'=>$request->lat,'long'=>$request->long,
                'address' => $request->address,'state'=>$request->state,'city'=>$request->city]);
            }else{
                UserAddress::where('id',$checkAdd->id)->update(['city'=>$request->city]); 
            }

            // UserAddress::create($input);
            $test = [];


           /* $users =  DB::table("business_information");
            //$users =  DB::table("users");
            $users =  $users->select("*", DB::raw("6371 * acos(cos(radians(" . $request->lat . "))
                            * cos(radians(lat)) * cos(radians(`long` ) - radians(" . $request->long . "))
                            + sin(radians(" .$request->lat. ")) * sin(radians(lat))) AS distance"));
            $users = $users->having('distance', '<', 20);
            $users = $users->orderBy('distance', 'asc');
            //$users = $users->pluck('id')->toArray();
            $users = $users->pluck('user_id')->toArray();
            $professionData = ProfessionalService::whereRaw("FIND_IN_SET($request->service_id,service)")->pluck('user_id')->toArray();
            if(!empty($professionData)){
                $driverUserAry = array_intersect($professionData,$users);
                foreach ($driverUserAry as $key => $value) {
                        $userInfo =User::where('id',$value)->first();*/

                        if(!empty($seviceUser)){
                            foreach($seviceUser as $provider){
                            $userInfo =User::where('id',$provider['user_id'])->first();
                                if($userInfo->approve == 1){
                                    //$test['user_id'] = $value;
                                    $test['user_id'] = $provider['user_id'];
                                    $test['order_id'] = $data->id;
                                    AssignOrder::create($test);
                                    $values = ['sender_id'=>auth()->user()->id,
                                    'receiver_id'=>$test['user_id'],
                                    'from' =>'Notification',
                                    'tag' =>'Job Request',
                                    'order_id' =>@$data->id,
                                    'status' =>@$data->status,
                                    'message'=> 'You Have a New Job Request'];
                                    Notification::create($values);
                                    //                            if(!empty($service->id)){
                                    //     $data       =          ['sender_id'=>auth()->user()->id,
                                    //                            'receiver_id'=>$service->id,
                                    //                            'from' =>'Notification',
                                    //                            'message'=> 'You Have a New Job Request'];
                                    //    }
                                    $notification->pushNotification($values);
                                }
                            }    
                        }
                      
             //  } // foreach
            //} // if

            // End Booking
   
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'Your service request has been successfully submitted. We will notify you as soon as your Job gets picked by a vetted faira Pro along with their details.'
            ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'Check My Area Service Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
             return response([
                 'status_code' => 500,
                 'response' => 'error',
                 'message' => $e->getMessage().'-'.$e->getLine(),
             ]);
        }
    }


    public function commicen($id){
        $data = Service::select('commission')->where('id',$id)->first();
        if($data->commission != null){
            return $data->commission;
        }else{
            return 0;
        }
       
    }
    public function getDistance($user,$current_lat,$current_long)
    {
        $lat1 = $current_lat;
        $long1 = $current_long;
        $lat2 = $user['lat'];
        $long2 =  $user['long'];
        
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
            $km = $r * $c; 
            //echo ' '.$km; 
            if($km <= 50){
                return $user;
            }else{
                return 'false';
            }
           

        //AIzaSyBbNNTmEa00bk_Bl5nuddSy7vSW41DzBRs
        // $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL&key=AIzaSyDkTqhcXmaE1rFi-Prdm6flnWX3pNUuPRI";
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // return $response = curl_exec($ch);
      
        // curl_close($ch);
        // $response_a = json_decode($response, true);
        // $dist = $response_a['rows'][0]['elements'][0]['distance']['value'];   
        // $time = $response_a['rows'][0]['elements'][0]['duration']['value'];
        // $km  = $dist / 1000; 
        // $rkm = $dist % 1000;
        // $m  = floor($rkm );
        // $cm = $rkm % 100; 

        // $distance = "";
       /* if($km <= 50){
            return $user;
        }else{
            return 'false';
        }*/
       
    }

    /**Service price get */

    //public function Minimum($id,$city){
    public function Minimum(){
       try{
       //$price = Price::where('service_id',$id)->where('city',$city)->first();
       $price = Setting::where('slug','base_price')->pluck('value')->first();
      
       
        return $price ?? 0;

      
       }catch(\Exception $e){
        $data = ['error_type'=>'Check My Area Service get price Time',
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

    /**Get Service Tax */
    public function Gettax($state)
    {
        try{
          $data = ServiceTex::where('state',$state)->first();
          if(empty($data)){
            return 0;
          }
         return $data->texes;
        }catch(\Exception $e){
            $data = ['error_type'=>'Tax Get Time',
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

    //     public function cronJob()
//     {
//         try {
//             $orderData = $this->order->where("pickup_date_time",date("Y-m-d 00:00:00"))->get();
//         	$userData = $this->user->where("online",1)->whereHas('roles',function($query){
//                     $query->where("id",3);
//                 })->whereHas('getVehicleDetails',function($query){
//                     $query->where("status",1);
//                 })->get();
//             foreach ($orderData as $key => $order) {
//                 foreach ($userData as $value) {
//                    $assignOrder = $this->assignOrder->where('user_id',$value->id)->where('order_id',$order->id)->count();
//                    if($assignOrder == 0){
//                         $this->assignOrder->create([
//                             "user_id"=>$value->id,
//                             "order_id"=>$order->id
//                         ]);
//                         /**
//                          * Send notification
//                          */
//                         $ReceiverUser = $this->user->find($value->id);
//                         $data['from'] = "Notification";
//                         $data['receiver_id'] = $value->id;
//                         $data['sender_id'] = $order->user_id;
//                         $data['message'] = "New order received !!";
//                         $data['order_id'] = $order->id;
//                         $data['order_status'] = "Pending";
//                         $this->notification->sendNotificationCronJob($data);
//                    }
//                 }

//             }
//             return $data  = ["status" => 200,"message"=>"Order assigned successfully"];
//         } catch (\Exception $e) {
//             return $data  = ["status" => 500,"message"=>"Something went wrong","data" => $e];
//         }
//     }

//Cron Job Function Start//
    public function cronJob(Notification $notification){
        try{
                $date = date('Y-m-d h:m:s');
                $carbon_date = \Carbon\Carbon::parse($date);
                $carbon_date->subHours(3);
            $order = Order::select('id')->where('status','0')->where('created_at','<',$carbon_date)->get();
            $assine = AssignOrder::wherein('order_id',$order)->get();
                foreach($assine as $val){
                            $val->from = "Notification";
                            $val->receiver_id = $val->user_id;
                            $val->sender_id = $val->order->user_id;
                            $val->message = "New order received !!";
                            $val->order_id = $val->order_id;
                            $val->order_status = "Pending";
                    }
                    $notification->sendNotificationCronJob($assine);
            return $data  = ["status_code" => 200,"message"=>"Order assigned successfully"];
        }catch(\Exception $e){
            return $data  = ["status_code" => 500,"message"=>"Something went wrong","data" => $e]; 
        }
    }

     /**Auth use Address Get */

    public function GetAddress(){
        try{
           $data = UserAddress::where('user_id',auth()->user()->id)->get();
           foreach($data as $val){
            $val->state = $val->state ?? '';
            $val->lat  = $val->lat ?? '';
            $val->long = $val->long ?? '';
            }
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'message' => 'success',
                'data' => $data
            ]);
           
        }catch(\Exception $e){
            return $data  = ["status_code" => 500,"message"=>"Something went wrong","data" => $e];
        }
    }

    /**
     * Pending Job Get
     */
    public function PendingJob(){
        try{
         $data = Order::where('user_id',auth()->user()->id)->wherein('status',['0','10'])->get();
         foreach($data as $val){
             $val->service_name = $val->service->service_name;
             $val->description = $val->service->description;
             $val->icon = $val->service->icon;

             $val->trust_feess = $val->trust_fees;
             $val->tax_fees =  ($val->tax / 100) * $val->min_price;
             $val->total_price = $val->trust_feess + $val->tax_fees + $val->min_price;
             $val->job_status = $val->status;
             $val->schedule = $val->schedule ?? '';
             $val->hours = $val->hours ?? '';
             if($val->status == '0'){
                 $val->status = 'Pending';
             }
             else if($val->status == '1'){
                $val->status = 'Quote Recevied';
             }
             unset($val->service);
         }

        //  foreach ($data as $key => $value) {  
        //    foreach($value as $key => $val){
        //         if($val == null){
        //             $value[$key] = " ";
        //         }
        //    }
        // }
         return ["status_code" => 200,"response"=>"Success","message"=>"pending job","data" => $data];
        }catch(\Exception $e){
            return $data  = ["status_code" => 500,"message"=>"Something went wrong","data" => $e];
        }
    }

    /**
     * Active Job
     */
    public function ActiveJob(){
        try{
            $data = Order::where('user_id',auth()->user()->id)->where('status','2')->get();
           
            foreach($data as $val){
                $val->service_name = $val->service->service_name;
                if($val->status == '2'){
                    $val->status = 'Active';
                }
                unset($val->service);
            }
            return ["status_code" => 200,"response"=>"Success","message"=>"active job","data" => $data];
      }catch(\Exception $e){
        return $data  = ["status_code" => 500,"message"=>"Something went wrong","data" => $e];
      }
    }

    /**
     * Complite job
     */
    public function CompliteJob(){
        try{
            $data = Order::where('user_id',auth()->user()->id)->where('status','4')->get();
           
            foreach($data as $val){
                $val->service_name = $val->service->service_name;
                if($val->status == '2'){
                    $val->status = 'Active';
                }
                unset($val->service);
            }
            return ["status_code" => 200,"response"=>"Success","message"=>"complite job","data" => $data];
        }catch(\Exception $e){
            return $data  = ["status_code" => 500,"message"=>"Something went wrong","data" => $e]; 
        }
    }

    /**Cancel Job */
    public function CancelJob(Request $request,Notification $notification){
        $data = $request->all();
        $validator = Validator::make($data, [
            'order_id' => 'required',
            'job_status' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
            //$reciver = AssignOrder::where('order_id',$request->order_id)->first();
            $reciver = AssignOrder::where('order_id',$request->order_id)->get();
            $input = $request->all();
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
           $hours = CancelPriceMaster::where('type','customer')->where('hours','>=',$difference)->first();
           if(!empty($hours)){
            $input['charge'] = $hours->price;

            // $customer = StripeCard::where('user_id',auth()->user()->id)->where('status','1')->first();
            // $customer_id = $customer->stripe_id;
          
            // //Payment
            // try{
            //     \Stripe\Stripe::setApiKey('sk_test_51H7fccLVBFA034ip2OJyNrnCGaiycIi4iTv33KkXZKedabOiXSvOMqdCJsnymHIFKHcPUUfeWGK9bPJCmbOsA7du00QMvu0iD0');
            //     $intent = \Stripe\PaymentIntent::create([
            //       'amount' => $hours->price*100,
            //       'currency' => 'inr',
            //       'customer' => $customer_id,
            //       'off_session' => true,
            //       'confirm' => true
            //     ]);getDistance
            // }catch(\Stripe\Exception\CardException $e){
            //     //dd('Error code is:' . $e->getError()->code);
            //     return response()->json([
            //         'status_code' => 401,
            //         'response' => 'error',
            //         'message' =>  $e->getError()->code
            //     ]);
            //     $payment_intent_id = $e->getError()->payment_intent->id;
            //     $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
            // }

           }else{
               $input['charge'] = NULL;
           }
          $input['user_id'] = auth()->user()->id;
          $cancelJob = new CancelJob;
          $cancelJob->user_id =  $input['user_id'];
          $cancelJob->order_id =  $input['order_id'];
          $cancelJob->job_status =  $input['job_status'];
          $cancelJob->save();
          $cancel_job_time = date('Y/m/d H:i:s');
          AssignOrder::where('order_id',$request->order_id)->update(['status'=>'3','cancel_job_time'=>$cancel_job_time]);
          Order::where('id',$request->order_id)->update(['status'=>'3']);

          if(!empty($reciver)){
            foreach($reciver as $reciver_user){
                $values = ['sender_id'=>auth()->user()->id,'receiver_id'=>$reciver_user->user_id,'from' =>'Notification','message'=> 'User cancel the job'];
                $notification= new Notification;
                $notification->sender_id = auth()->user()->id;
                $notification->receiver_id =$reciver_user->user_id;
                $notification->from ='Notification';
                $notification->message ='User cancel the job';
                $notification->save();
                Notification::create($values);
                $notification->pushNotification($values);
            }
          }
          return ["status_code" => 200,"response"=>"Success","message" => 'Job cancel success'];
        }catch(\Exception $e){
            $data = ['error_type'=>'Job Cancel Time',
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

    /**
     * Update User Lat Long
     */
    public function UpdateLatLong(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'lat' => 'required',
            'long' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
            User::where('id',$request->user_id)->update(['lat'=>$request->lat,'long'=>$request->long]);
            return ["status_code" => 200,"response"=>"Success","message" => 'User Lat Long Update success'];
        }catch(\Exception $e){
            $data = ['error_type'=>'User Lat Long Update Time',
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

    public function clientAcceptOrder(Request $request, Notification $notification){
        $data = $request->all();
        $validator = Validator::make($data, [
            'provider_id' => 'required',
            'order_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
            $check = AssignOrder::where('user_id',$request->provider_id)->where('order_id',$request->order_id)->first();
            if($check->order->service->category_get->type == '0'){
                $status = '11';
            }else{
                $status = '1';
            }
            AssignOrder::where('user_id',$request->provider_id)->where('order_id',$request->order_id)->where('status','0')->update(['status'=>  $status ]);
            Order::where('id',$request->order_id)->update(['status'=>$status]);
            $values = [ 'sender_id'=>auth()->user()->id,
                        'receiver_id'=>$request->provider_id,
                        'from' =>'Notification',
                         'tag'=>'active screen',
                        'order_id'=>'',
                        'message'=> 'Your quote is accepted by the user'

                    ];
            Notification::create($values);
            $notification->pushNotification($values);
            return ["status_code" => 200,"response"=>"Success","message" => 'Quote Accepted successfully.'];
        }catch(\Exception $e){
            $data = ['error_type'=>'client Accept Order Update Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response(['status_code' => 500, 'response' => 'error','message' => $e->getMessage()]);
        }
    }

    public function getQuotation(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'order_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{ 
            $userId = auth()->user()->id;
            $orderQuatations = OrderQuotation::where('customer_id',$userId)
                                ->where('order_id',$request->order_id)
                                ->with('quotationProviderInfo')
                                ->get();
            $data=[];
            foreach ($orderQuatations as $key => $orderQuatation) {
                $orderId =$orderQuatation->order_id;
                $orderInfo = Order::where('id',$orderId)->first();
                if(!empty($orderInfo->schedule)){
                    $schedule = $orderInfo->schedule;
                }else {
                    $schedule = $orderInfo->rider_dateTime;
                }
                $data[$key]['id'] = $orderQuatation->id;
                $data[$key]['order_id'] = $orderId;
                $data[$key]['schedule'] = $schedule;
                $average =  Review::where('user_id',$orderQuatation->quotationProviderInfo->id)->avg('rating');
                $data[$key]['price'] = $orderQuatation->price;
                $data[$key]['time'] = $orderQuatation->time;
                $data[$key]['hours'] = $orderInfo->hours;
                $data[$key]['schedule'] = $orderInfo->schedule;
                $data[$key]['description'] = $orderQuatation->description;
                $data[$key]['providerInfo'] = $orderQuatation->quotationProviderInfo;
                $data[$key]['providerInfo']['overall_rating']= $average ?? 0;
            }
            return ["status_code" => 200,"response"=>"Success","message" => 'Order Quotation list.',"data"=>$data];
        }catch(\Exception $e){
            $data = ['error_type'=>'client get quatation list Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response(['status_code' => 500, 'response' => 'error','message' => $e->getMessage()]);
        }
    }

    /**
     * Job Detail Get
     */
    public function GetJobDetail(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{

       $data = Order::with('assine','assine.user')->where('id',$request->order_id)->get();
       if(!empty($data)){
           foreach($data as $val){
                $review = Review::where('user_id',$val->assine->user->id)->limit(20)->get();
                if($review == null){
                   $val->review_list = new \stdClass();
                }else{
                   $val->review_list = $review;
                }
                $dispute = Dispute::where('order_id',$request->order_id)->first();
                if($dispute == null){
                    $val->dispute_list = new \stdClass();
                }else{
                    $val->dispute_list = $dispute;
                }
                $val->unit = $val->unit ?? '';
                $val->assine_user_first_name = $val->assine->user->first_name ?? '';
                $val->quotationPrice = '';
                $average =  Review::where('user_id',$val->assine->user->id)->avg('rating');
                $val->provider_id = $val->assine->user->id;
                $val->overall_rating = $average ?? 0;
                if(!empty($val->assine->user->id)){
                    $providerId = $val->assine->user->id;
                    $orderId = $request->order_id;
                    $quatationInfo = OrderQuotation::where('provider_id',$providerId)->with('quotationProviderInfo')->where('order_id',$orderId)->first();
                if(!empty($quatationInfo)){
                    $val->quotationPrice =$quatationInfo->price;
                }
             }
             $val->assine_user_last_name = $val->assine->user->last_name ?? '';
             $val->assine_user_image = $val->assine->user->image ?? '';
             $val->assine_user_mobile = $val->assine->user->mobile ?? '';
             $val->service_category = $val->service->category->category_name ?? '';
                /*distance*/
            if ($val->status==2) {
                if ($val->service_category=='Ride') {
                    $provider_lat  = $val->assine->user->lat??'';
                    $provider_long  = $val->assine->user->long??'';
                    $lat1     = $val->rider_pickuplat;
                    $long1    = $val->rider_pickuplng;
                    $lat2     = $provider_lat;
                    $long2    = $provider_long;
                }else{
                    $provider_lat  = $val->assine->user->lat??'';
                    $provider_long  = $val->assine->user->long??'';

                    $lat2       = $provider_lat;
                    $long2      = $provider_long;
                    $location   = $val->address??'';
                    $latlong    =   $this->get_lat_long($location); 
                    $riderlatlng    =   explode(',' ,$latlong);
                    $lat1           =   $riderlatlng[0];
                    $long1          =   $riderlatlng[1]; 
                }
                $distance = $this->distance($lat1,$long1,$lat2,$long2);
            }

            $val->distance_km = $distance['distance']??'';
            $val->distance_time = $distance['time']??'';
            $val->category_id = $val->service->category->id;
            $invoiceData = Invoice::where('order_id',$request->order_id)->first();
            $val->regular_hours = $invoiceData->regular_hours ?? '';
            $val->after_hours = $invoiceData->after_hours ?? '';
            $val->extra_hours_cost = $invoiceData->extra_hours_cost ?? '';
            $val->sub_total = $invoiceData->sub_total ?? '';
                   if($val->service->category->type == '1'){
                       if(!empty($val->sub_total)){
                        $val->trust_feess = strval(($val->trust_fees / 100) * $val->sub_total);
                        $val->tax_fees =  strval(($val->tax / 100) * $val->sub_total);
                        $total_price = $val->trust_feess + $val->tax_fees + $val->sub_total;
                       }else {
                        $val->trust_feess = strval(($val->trust_fees / 100) * $val->min_price);
                        $val->tax_fees =  strval(($val->tax / 100) * $val->min_price);
                        $total_price = $val->trust_feess + $val->tax_fees + $val->min_price;
                       }
                    
                   
                     $val->total_price =  $total_price;
                  // $val->total_price = $val->priceget->extra_price ?? 0;
                    $val->trust_fees = $val->trust_fees;
                    $val->extra_price = $val->extra_price ?? '';
                    $val->min_price = $val->min_price;
                    $val->tax = $val->tax;

                    // added this after
                    $val->rider_pickupAddress=$val->rider_pickupAddress ?? '';
                    $val->rider_pickuplat=$val->pickup_lat ?? '0';
                    $val->rider_pickuplng=$val->pickup_lng ?? '0';
                    $val->rider_dropLat=$val->drop_lat ?? '0';
                    $val->rider_dropLng=$val->drop_lng ?? '0';
                    $val->rider_dateTime=$val->schedule ?? '';
                    $val->rider_price=$val->total_price ?? '0';
                    $val->rider_km=$val->rider_km ?? '0';


                 }else{
                    $val->tax = $val->tax;
                    $val->trust_feess = ($val->trust_fees / 100) * $val->quotepriceget->price;
                    $val->tax_fees =  ($val->tax / 100) * $val->quotepriceget->price;
                    $total_price = $val->quotepriceget->price ?? 0;
                     $val->total_price = round($total_price + $val->trust_feess + $val->tax_fees) ?? 0;
                    $val->trust_fees = $val->trust_fees;
                    $val->min_price = $val->quotepriceget->price ?? '0';
                    $val->extra_price = $val->extra_price ?? '';

                    // added this after
                    $val->rider_pickupAddress=$val->rider_pickupAddress ?? '';
                    $val->rider_pickuplat=$val->pickup_lat ?? '0';
                    $val->rider_pickuplng=$val->pickup_lng ?? '0';
                    $val->rider_dropLat=$val->drop_lat ?? '0';
                    $val->rider_dropLng=$val->drop_lng ?? '0';
                    $val->rider_dateTime=$val->schedule ?? '';
                    $val->rider_price=$val->total_price ?? '0';
                    $val->rider_km=$val->rider_km ?? '0';
                 }
             $val->rating = $val->ratingGet->rating ?? 0;
             $val->dispute = $val->disputeGet->rating ?? 0;
             $val->tax = $val->tax;
             if($val->quick_service == '0'){
            $val->job_sched = "At-Ease Service";
             }else{
                $val->job_sched = "Quick Service";
             }
             $val->time = $val->hours ?? '';
             $val->hours = $val->hours ?? '';
             $val->schedule = $val->schedule ?? '';
             $val->state = $val->state ?? '';
            //  $val->extra_price = $val->service->priceGet->extra_price ?? '';
             $val->date = \Carbon\Carbon::parse($val->schedule)->format('D,M,d,Y');
             unset($val->service);
             unset($val->priceget);
             unset($val->ratingGet);
             unset($val->disputeGet);
             unset($val->quotepriceget);
           }
        }
        unset($data[0]->assine);
        return ["status_code" => 200,
               "response"=>"Success",
               "data" => $data[0]];
        }catch(\Exception $e){
            //Log::debug($e->getMessage());die;
            $data = ['error_type'=>'User Lat Long Update Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response([
                'status_code' => 500,
                'response' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // function to get  the address
function get_lat_long($address){

    $address = str_replace(" ", "", $address);



    $json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$address&mode=driving&language=pl-PL&key=AIzaSyDkTqhcXmaE1rFi-Prdm6flnWX3pNUuPRI");
    $json = json_decode($json);
    if(!empty($json->status) && $json->status=="OVER_QUERY_LIMIT"){
        return "";
    }
    $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
    
    return $lat.','.$long;
}

    /**Review Rating */

    public function Review(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'user_id' =>'required',
            'order_id' => 'required',
            'rating' => 'required|numeric',
            'commant' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
          $input =  $request->all();
          //$input['user_id'] = auth()->user()->id;
          $value = Review::create($input);
         $data = Review::where('id',$value->id)->first();
          return ["status_code" => 200,
               "response"=>"Success",
               "data" => $data,
               "message" => 'Review submit success'];
        }catch(\Exception $e){
            $data = ['error_type'=>'Submit Review Time',
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

    /**Dispute */
    public function Dispute(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'order_id' => 'required',
            'reason' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
          $input = $request->all();
          $input['user_id'] = auth()->user()->id;
          Dispute::create($input);
          $dispute_time = date('Y/m/d H:i:s');
          Order::where('id',$request->order_id)->update(['status'=>'5']);
          AssignOrder::where('order_id',$request->order_id)->where('status','!=','0')->update(['status'=>'5','dispute_time'=>$dispute_time]);
          return ["status_code" => 200,
          "response"=>"Success",
          "message" => 'Dispute submit success'];
        }catch(\Exception $e){
            $data = ['error_type'=>'Submit Dispute Time',
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

    /**
     * Get User and Serviceprovider Diff
     */

     public function getDif(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
         try{
          $check = Order::where('id',$request->order_id)->get();
          foreach($check as $val){
              $val->customer_lat = $val->user->lat ?? '0';
               $val->customer_long = $val->user->long ?? '0';
             $val->provider_lat = $val->assine->user->lat ?? '0';
            $val->provider_long = $val->assine->user->long ?? '0';
          }
       
         $customer_lat = $check[0]->customer_lat;
         $customer_long = $check[0]->customer_long;
         $provider_lat = $check[0]->provider_lat;
         $provider_long = $check[0]->provider_long;
        $km = $this->distanceone($customer_lat,$customer_long,$provider_lat,$provider_long);
            return ["status_code" => 200,
            "response"=>"Success",getDistance,
            "data" => $km];
         }catch(\Exception $e){
            
            $data = ['error_type'=>'Get Difference Time',
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

     //get distance
     function distanceone($lat1, $lon1, $lat2, $lon2) { 
        $pi80 = M_PI / 180; 
        $lat1 *= $pi80; 
        $lon1 *= $pi80; 
        $lat2 *= $pi80; 
        $lon2 *= $pi80; 
        $r = 6372.797; // mean radius of Earth in km 
        $dlat = $lat2 - $lat1; 
        $dlon = $lon2 - $lon1; 
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2); 
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c; 
        //echo ' '.$km; 
        return $km; 
        }

        
    public function distance($lat1,$long1,$lat2,$long2)
    {
   // dd($lat1.'--'.$long1.'--'.$lat2.'--'.$long2);
   if (!empty($lat1)&&!empty($long1)&&!empty($lat2)&&!empty($long2)) {

       # code...
   
        //dd($lat1.'--'.$long1.'--'.$lat2.'--'.$long2);
        //AIzaSyBbNNTmEa00bk_Bl5nuddSy7vSW41DzBRs
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL&key=AIzaSyDkTqhcXmaE1rFi-Prdm6flnWX3pNUuPRI";
       //dd($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);

        curl_close($ch);

        $response_a = json_decode($response, true);
        if($response_a["status"]=="OVER_QUERY_LIMIT"){
            return $resultData = [
                        'distance' => '',
                        'time' => '',
                    ];
        }
      // dd($response_a);
        $dist = !empty($response_a['rows'][0]['elements'][0]['distance'])?$response_a['rows'][0]['elements'][0]['distance']['value']:0;
        $time = !empty($response_a['rows'][0]['elements'][0]['duration'])?$response_a['rows'][0]['elements'][0]['duration']['value']:0;
        $km  = $dist / 1000; 
        $rkm = $dist % 1000;
        $m  = floor($rkm );
        $cm = $rkm % 100; 
        $distance = "";
        if($km >= 1){
            $distance = $km.' km';
        }else if($m > 0){
            $distance = $m.' meter';
        }else if($km = 0 && $m = 0 ){
            $distance = "done";
        }

        $date = Carbon::now()->addSeconds($time)->format('Y-m-d H:i:s');
        $datetime1 = new DateTime();
        $datetime2 = new DateTime($date);
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('%a day %h hr %i min');
        $leftTime = explode(' ',$elapsed);
        
        $LiveDateTime = "";
        if($leftTime[0] > 0){
            $LiveDateTime = $leftTime[0].' day';
        }
        if($leftTime[2] > 0){
            $LiveDateTime .= $leftTime[2].' hr';
        }
        if($leftTime[4] > 0){
            $LiveDateTime .= $leftTime[4].' min';
        }
        
        if($leftTime[0] == 0 && $leftTime[2] == 0 && $leftTime[4] == 0){
            $LiveDateTime = " just wait";
        }
      }  
        $resultData = [
            'distance' => $distance??'',
            'time' => $LiveDateTime??'',
        ];
        return $resultData;
     }

    /**
     * Get Notification
     */
    public function GetNotification(){
        try{
            $data = Notification::where('receiver_id',auth()->user()->id)->orderby('id','desc')->get();
            foreach($data as $val){
            	$name='';
                $lname='';
                if(!empty(auth()->user()->first_name)){
                    $name = auth()->user()->first_name;
                }
                if(!empty(auth()->user()->last_name)){
                    $lname = auth()->user()->last_name;
                }
                $val->provider_name = $name.' '. $lname;
                $val->created_at_timeStamp = $val->created_at;
            }
            User::where('id', auth()->user()->id)->update(array('notification_count' => '0'));
            return ["status_code" => 200, "response" => "Success", "data" => $data];
        }catch(\Exception $e){
            $data = ['error_type'=>'Get Notification Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'App'];
            errorAdd($data);
            return response(['status_code' => 500, 'response' => 'error','message' => $e->getMessage().' - '.$e->getLine()]);
        }
    }

    /**Contact Us */
    public function ContactUs(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'reason' => 'required',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status_code' => 500,'response' => 'error',
                'message' => $validator->messages()->first()]);
        }
        try{
           // \Artisan::call("migrate");
            $input = $request->all();
            $input['user_id'] = auth()->user()->id;
            ContactUs::create($input);
            $body = [               
                'first_name' => $request->first_name,  
                'last_name' => $request->last_name,
                'email' => $request->email,
                'reason' => $request->reason,
                'mess' => $request->message                 
            ];
            Mail::send('emails.contact_us', $body, function ($message) use ($input)
            {        
                $message->from(env('MAIL_FROM_ADDRESS'), 'Faria Application');        
                $message->to(env('ADMIN_EMAIL'));        
                $message->subject('Contact Us.');        
            });
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
               'message' => 'Your request has been send success.'
            ]);
        }catch(\Exception $e){
            
            $data = ['error_type'=>'Send Contact Us Request Time',
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

    //Testing For Payment
    public function paymentFull(){
        try{
            \Stripe\Stripe::setApiKey('sk_test_51H7fccLVBFA034ip2OJyNrnCGaiycIi4iTv33KkXZKedabOiXSvOMqdCJsnymHIFKHcPUUfeWGK9bPJCmbOsA7du00QMvu0iD0');
            $intent = \Stripe\PaymentIntent::create([
              'amount' => 180000,
              'currency' => 'inr',
              'customer' => 'cus_HhRKSQtEqtBQHy',
              'off_session' => true,
              'confirm' => true
            ]);
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
               'message' => 'Payment Success.',
               'data' => $intent
            ]);
        }catch(\Stripe\Exception\CardException $e){
            dd('Error code is:' . $e->getError()->code);
            $payment_intent_id = $e->getError()->payment_intent->id;
            $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
        }
    }

    //Delete Card

    public function DeleteCard(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'stripe_id' => 'required',
            'card_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
            // $stripe = new \Stripe\StripeClient(
            //     'sk_test_51H7fccLVBFA034ip2OJyNrnCGaiycIi4iTv33KkXZKedabOiXSvOMqdCJsnymHIFKHcPUUfeWGK9bPJCmbOsA7du00QMvu0iD0'
            //   );
            //   $stripe->customers->deleteSource(
            //     'cus_HhmyTQ9jT5tXx9',
            //     'card_1H8NOILVBFA034ipSaJUseOX',
            //     []
            //   );

            $check = StripeCard::where('stripe_id',$request->stripe_id)->where('status','1')->first();
            if(!empty($check)){
                return response()->json([
                    'status_code' => 400,
                    'response' => 'error',
                    'message' => 'You don`t remove this card is primary.'
                ]); 
            }else{
                StripeCard::where('stripe_id',$request->stripe_id)->delete();
                return response()->json([
                  'status_code' => 200,
                  'response' => 'success',
                 'message' => 'Card Remove Success.'
              ]);
            }
              
        }catch(\Exception $e){ 
            $data = ['error_type'=>'Card Remove Time',
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

    /** Add to Primary card */
    public function PrimaryCard(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'stripe_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
            StripeCard::where('user_id',auth()->user()->id)->update(['status'=>'0']);
            StripeCard::where('stripe_id',$request->stripe_id)->update(['status'=>'1']);
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
               'message' => 'Card save to primary success.'
            ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'Card Add To Primary Time',
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

    /**Dispute Job List */
    public function DisputeJobList(){
        try{
           $data = Order::where('user_id',auth()->user()->id)->where('status','5')->get();
           foreach($data as $val){
            $val->service_name = $val->service->service_name;
            unset($val->service);
           }
           return response()->json([
            'status_code' => 200,
            'response' => 'success',
           'data' => $data
        ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'Dispute job list Time',
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

    //Quote code get
    public function Quoteget(){
        try{
            $data = QuotePrice::where('reciver_id',auth()->user()->id)->where('status','0')->get();
           foreach($data as $val){
               $val->service = $val->order->service->service_name;
               $val->hours = $val->order->hours;
               $val->address = $val->order->address;
               $val->schedule = $val->order->schedule;
               $val->service_desc = $val->order->service->description;
           }
            return response()->json([
                'status_code' => 200,
                'response' => 'success',
                'data' => $data
            ]);
        }catch(\Exception $e){
            $data = ['error_type'=>'Quote code get time',
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

    //code Accept
    public function Acceptcode(Request $request,Notification $notification){
        $data = $request->all();
        $validator = Validator::make($data, [
            'status' => 'required',
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
           $data = QuotePrice::where('reciver_id',auth()->user()->id)->where('order_id',$request->order_id)->first();
        
            if($request->status == '1'){
                Order::where('id',$request->order_id)->update(['status'=>'1']);
                QuotePrice::where('reciver_id',auth()->user()->id)->where('order_id',$request->order_id)->where('status','0')->update(['status'=>'1']);
                AssignOrder::where('user_id',$data->sender_id)->where('order_id',$request->order_id)->update(['status'=>'1']);
                $values = ['sender_id'=>auth()->user()->id,
                'receiver_id'=>$data->sender_id,
                'from' =>'Notification',
                'message'=> 'User has accept your quote'];
                Notification::create($values);
                $notification->pushNotification($values);
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'message' => 'Quode accept success'
                ]);
            }else{
                QuotePrice::where('reciver_id',auth()->user()->id)->where('order_id',$request->order_id)->where('status','0')->delete();
                Order::where('id',$request->order_id)->update(['status'=>'0']);
                AssignOrder::where('order_id',$request->order_id)->where('user_id',$data->sender_id)->delete();

                $values = ['sender_id'=>auth()->user()->id,
                'receiver_id'=>$data->sender_id,
                'from' =>'Notification',
                'message'=> 'User has decline your quote'];
                  Notification::create($values);
                   $notification->pushNotification($values);

                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'message' => 'code cancel success'
                ]);
            }
        }catch(\Exception $e){
            $data = ['error_type'=>'Quote code get time',
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

    //status wise get job
    public function Statusget(Request $request){
        // dd('fs');
        $data = $request->all();
        $validator = Validator::make($data, [
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        try{
            if($request->status == 'pending'){
                // $data = Order::where('user_id',auth()->user()->id)->where('status','0')->get();
                $data = Order::with('assine')->where('user_id',auth()->user()->id)->whereIn('status',['0','11','10'])->orderBy('id', 'DESC')->get();
            }
            else if($request->status == 'active'){
                  $data = Order::with('assine')->where('user_id',auth()->user()->id)->wherein('status',['1','2','6','7','8','9','15'])->orderBy('id', 'DESC')->get();
            }else if($request->status == 'complete'){
                $data = Order::with('assine')->where('user_id',auth()->user()->id)->wherein('status',['4','5'])->orderBy('id', 'DESC')->get();
            }
            foreach($data as $val){
                $imageAry = OrderImage::where('order_id',$val->id)->get();
                $val->service_name = $val->service->service_name;
                $val->category_id = $val->service->category_get->id;
                if(!empty($val->service) && !empty($val->service->category_get) && $val->service->category_get->type == '0'){
                    $check = QuotePrice::where('order_id',$val->id)->first();
                    if(!empty($check)){
                        $val->quote_recive = '1';
                    }else{
                        $val->quote_recive = '0';
                    }
                }else{
                    $val->quote_recive = '1'; 
                }
                $val->description = $val->service->description;
                $val->icon = $val->service->icon;
                if($request->status == 'active' || $request->status == 'complete'){
                    $status = $request->status;
                    $val->assine_user_first_name = $val->assine->user->first_name ?? '';
                    $val->assine_user_last_name = $val->assine->user->last_name ?? '';
                    $val->assine_user_image = $val->assine->user->image ?? '';
                    $val->assine_user_mobile = $val->assine->user->mobile ?? '';
                }
                if($val->service->category_get->type == '1'){
                    $val->trust_feess = ($val->trust_fees / 100) * $val->min_price;
                    $val->tax_fees =  ($val->tax / 100) * $val->min_price;
                    $val->total_price = round($val->trust_feess + $val->tax_fees + $val->min_price);
                    $val->extra_price = $val->extra_price ?? '';
                    // $val->tax_fees =   $val->min_price;
                    // $val->total_price =  $val->min_price;
                }else{
                    $val->tax = $val->tax;
                    $price = $val->quotepriceget->price ?? 0;
                    $val->trust_feess = ($val->trust_fees / 100) * $price;
                    $val->tax_fees =  ($val->tax / 100) * $price;
                    $total_price = round($val->trust_fees + $val->tax_fees + $price);
                    $val->total_price =  $total_price;
                    $val->min_price = $val->quotepriceget->price ?? '0';
                    $val->extra_price = $val->extra_price ?? '';
                }
                //$val->extra_price =  $val->service->priceGet->extra_price ?? '';
                $val->unit = $val->unit ?? '';
                $val->imageAry = $imageAry ?? '';
                $val->receivedQuoteCount = OrderQuotation::where('order_id',$val->id)->count();
                //  if($request->status == 'active' ){
                //  $val->jobStatus = '1';
                //  }
                $val->statusName='';
                if($val->status=='2'){
                    if(!empty($val->service) && !empty($val->service->category_get) && $val->service->category_get->category_name=='Ride') {
                        $provider_lat  = $val->assine->user->lat??'';
                        $provider_long  = $val->assine->user->long??'';
                        // dd($val->assine->user->mobile_code);
                        if ($provider_lat=='0') {
                            $provider_lat ='';
                        }
                        if ($provider_long=='0') {
                            $provider_long ='';
                        }
                        $lat1     = $val->rider_pickuplat;
                        $long1    = $val->rider_pickuplng;
                        $lat2     = $provider_lat;
                        $long2    = $provider_long;
                        //dd($lat1.'-+'.$long1.'-+'.$lat2.'-+'.$long2);
                    }else{
                        $provider_lat  = $val->assine->user->lat??'';
                        $provider_long  = $val->assine->user->long??'';

                        $lat2       = $provider_lat;
                        $long2      = $provider_long;
                        $location   = $val->address??'';
                        $latlong    =   $this->get_lat_long($location); 
                        $riderlatlng    =   explode(',' ,$latlong);
                        $lat1 = "";
                        $long1 = "";
                        if(isset($riderlatlng[0]) && isset($riderlatlng[1])){
                            $lat1           =   $riderlatlng[0];
                            $long1          =   $riderlatlng[1];
                        }
                    }
                    //dd($lat1.'+'.$long1.'+'.$lat2.'+'.$long2);
                    if (!empty($lat1)&&!empty($long1)&&!empty($lat2)&&!empty($long2)) {
                        $distance = $this->distance($lat1,$long1,$lat2,$long2);
                    }
                    //dd($distance);
                    $val->statusName = 'On My Way';
                }elseif($val->status ==4){
                    $val->statusName = 'Service Completion';
                }elseif($val->status ==6){
                    $val->statusName = 'Arrived on Site';
                }elseif($val->status ==7){
                    $val->statusName = 'Reschedule after confirmation with customer (New ETA)';
                }elseif($val->status ==9){
                    $val->statusName = 'Job Resume (When Need another visit selected and arrived onsite for Day 2)';
                }elseif($val->status ==15){
                    $val->statusName = 'Ride Start';
                }elseif($val->status ==1){
                    $val->statusName = 'In Progress';
                }elseif($val->status ==8){
                    $val->statusName = 'Need Another Visit';
                }
            
                $val->promo = $val->promo ?? '';
                $val->job_status = $val->status;
                //$val->schedule = $val->schedule ?? '';
                //$val->hours = $val->hours ?? '';
                $val->time = $val->hours ?? '';
                $val->hours = $val->hours ?? '';
                $schedule = '';
                if(!empty($val->schedule)){
                    $schedule =$val->schedule;
                }else{
                    $schedule =$val->rider_dateTime;
                }
                $val->schedule = $schedule;
                //$val->schedule = $val->schedule ?? '';
                if($val->status == '0'){
                    $val->status = 'Pending';
                }
                else if($val->status == '1'){
                    $val->status = 'In Process';
                }
                else if($val->status == '2'){
                    $val->status = 'active';
                }
                else if($val->status == '3'){
                    $val->status = 'cancel';
                }
                else if($val->status == '4'){
                    $val->status = 'complite';
                }
                else if($val->status == '5'){
                    $val->status = 'dispute';
                }
                else if($val->status == '10'){
                    $val->status = 'Quote Recevied';
                }
                else if($val->status == '15'){
                    $val->status = 'Ride Start';
                }
                else if($val->status == '11'){
                    $val->status = 'Quote Pending';
                }
                $val->distance_km = @$distance['distance']??'';
                $val->distance_time = @$distance['time']??'';
                /* if ($val->status!='2') {
                    $val->distance_km = '';
                    $val->distance_time = '';
                }*/
                
                unset($val->service);
                unset($val->quotepriceget);
                @$distance['distance']= '';
                @$distance['time']=''; 
            }
            return ["status_code" => 200, "response"=>"Success", "message"=>"Success", "data" => $data];
        }catch(\Exception $e){
            dd($e);
            return response(['status_code' => 500,'response' => 'error','message' => 'something wrong'.$e->getMessage().$e->getLine()]);   
        }
    }

    //cron job active job
    public function CronjobActivejob(){
        try{
            $data = Order::where('status','1')->where('created_at','<=',date('Y-m-d H:m:s'))->get();
            foreach($data as $val){
                Order::where('id',$val->id)->update(['status'=>'2']);
            }
            return ["status_code" => 200, "response"=>"Success", "message"=>"Success"];
        }catch(\Exception $e){
            return response(['status_code' => 500,'response' => 'error','message' => 'something wrong']); 
        }
    }

    public function disableServices(Request $request){
        try{
            $validator = Validator::make($request->all(), [ 'approve' => 'required' ]);
            if ($validator->fails()) { 
                return response()->json([
                    'status_code' => 401,
                    'response' => 'error',
                    'message' => $validator->messages()->first(),
                ]);  
            }else {
                $data = User::where('id',auth()->user()->id)->first();
                $data->approve = $request->approve;
                $data->save();
                $message= "You are off now";
                if($request->approve == 1){
                    $message= "You are on now";
                }
                return ["status_code" => 200, "response"=>"Success", "message"=>$message];
            }
        }catch(\Exception $e){
            return response(['status_code' => 500,'response' => 'error','message' => $e->getMessage()]); 
        }
    }

    public function serviceName($id){
        try{
            $data = Service::select('service_name')->where('id',$id)->first();
            return $data->service_name;
        }catch(\Exception $e){
            return response(['status_code' => 500,'response' => 'error', 'message' => 'something wrong' ]);
        }
    }

    //Check Coupon Detail
    public function CheckCoupon(Request $request){
        $validator = Validator::make($request->all(), [ 
            'coupon' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
          }
            try{
                $check = Discount::where('coupon',$request->coupon)->where('expirey_date', '>=', date("m/d/Y"))->first();
                if(empty($check)){
                    return response()->json([
                        'status_code' => 401,
                        'response' => 'error',
                        'message' => 'Coupon code not valid',
                    ]); 
                }else{
                    return response()->json([
                        'status_code' => 200,
                        'response' => 'success',
                        'data' => $check,
                        'message' => 'success'
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

    public function calculateDistance($from, $to) {
        $from = urlencode($from);
        $to = urlencode($to);
        $data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false&key=AIzaSyAtZwEKg-PUY-0NvCsPS3JLbedqO2EFxGs");
        $data = json_decode($data); 
        $time = 0;
        $distance = 0;
        foreach($data->rows[0]->elements as $road) {
            $time += $road->duration->value;
            $distance += $road->distance->value;
        }
        $km=$distance/1000;
        $result['distance'] = $km;
        $result['time'] = gmdate("H:i:s", $time);
        return $result;
    }


    public function distanceCalculation(Request $request){
        $validator = Validator::make($request->all(), [
            'startAddress' => 'required',
            'endAddress' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
          }
            try{
                $distance = $this->calculateDistance($request->startAddress,$request->endAddress);
                //dd();
                $baseFair = Setting::where('slug','base_price')->first();
                $data['baseFare']= $baseFair->value;
                $data['distance']= number_format($distance['distance'] ,2);
                $data['fareAmount'] = number_format($distance['distance'] * $baseFair->value ,2);
                $data['time'] = $distance['time'];
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'message' => 'Total distance and fare amount',
                    'data' => $data,
                    
                ]);
            }catch(\Exception $e){
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => $e->getLine().' - '.$e->getMessage()
                ]);
            }
    }
    
    public function rideBooking(Request $request, Notification $notification){
        $validator = Validator::make($request->all(), [ 
            'service_id' => 'required',
            'pickup_address' => 'required',
            'drop_address' => 'required',
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'drop_lat' => 'required',
            'drop_lng' => 'required',
            'date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 401,
                'response' => 'error',
                'message' => $validator->messages()->first(),
            ]);  
          }
            try{
                $user_id = auth()->user()->id;
                $rideBooking = new RideBooking();
                $rideBooking->service_id = $request->service_id;
                $rideBooking->user_id = $user_id;
                $rideBooking->pickup_address = $request->pickup_address;
                $rideBooking->drop_address = $request->drop_address;
                $rideBooking->pickup_lat = $request->pickup_lat;
                $rideBooking->pickup_lng = $request->pickup_lng;
                $rideBooking->drop_lat = $request->drop_lat;
                $rideBooking->drop_lng = $request->drop_lng;
                $rideBooking->date = $request->date;
                //$rideBooking->save();
                $users =  DB::table("users");
                $users =  $users->select("*", DB::raw("6371 * acos(cos(radians(" . $request->pickup_lat . "))
                                * cos(radians(lat)) * cos(radians(`long` ) - radians(" . $request->pickup_lng . "))
                                + sin(radians(" .$request->pickup_lat. ")) * sin(radians(lat))) AS distance"));
                $users = $users->having('distance', '<', 20);
                $user = $users->where('id', '!=' , $user_id);
                $users = $users->orderBy('distance', 'asc');
                $users = $users->pluck('id')->toArray();
                if(!empty($users)){
                    //echo json_encode($users); die;
                    $professionData = ProfessionalService::whereRaw("FIND_IN_SET($request->service_id,service)")->pluck('user_id')->toArray();
                    if(!empty($professionData)){
                        $driverAry = array_intersect($professionData,$users);
                        $order = new Order();
                        $order->user_id = auth()->user()->id;
                        $order->service_id = $request->service_id;
                        $order->address = $request->drop_address;
                        $order->unit = NULL;
                        $order->desc  = NULL;
                        $order->hours = NULL;
                        $order->schedule = NULL;
                        $order->trust_fees = '5';
                        $check_service = Service::where('id',$request->service_id)->first();
                        $order->tax = strval($check_service->category_get->trust) ?? '5';
                        $order->min_price = 0;
                        $order->quick_service  = '0';
                        $order->status  = '0';
                        $order->rider_pickupAddress=$request->pickup_address;
                        $order->rider_pickuplat=$request->pickup_lat;
                        $order->rider_pickuplng=$request->pickup_lng;
                        $order->rider_dropLat=$request->drop_lat;
                        $order->rider_dropLng=$request->drop_lng;
                        $order->rider_dateTime=$request->date;
                        $order->rider_price=$request->rider_price;
                        $order->rider_km=$request->rider_km;
                        //dd($request->rider_km);
                        $order->save();
                        foreach($driverAry as $dirverId){
                            $userInfo =User::where('id',$dirverId)->first();
                            if($userInfo->approve == '1'){
                            $test['user_id'] = $dirverId;
                            $test['order_id'] = $order->id;
                            AssignOrder::create($test);
                            $values =   ['sender_id'=>auth()->user()->id,
                                            'receiver_id'=>$dirverId,
                                            'from' =>'Notification',
                                             'tag' =>'Job Request',
                                              'order_id' =>@$order->id,
                                               'status' =>@$order->status,
                                            'message'=> 'You Have a New Job Request for Ride'];
                            Notification::create($values);
                            $notification->pushNotification($values);
                            }
                        }
                    return response()->json([
                        'status_code' => 200,
                        'response' => 'success',
                        'message' => 'Your service request has been successfully send. We will notify you as soon as your Job gets picked by a vetted faira Pro along with their details.'
                    ]);
                    }else {
                        return response()->json([
                            'status_code' => 400,
                            'response' => 'unsuccessful',
                            'message' => 'Sorry No Driver Found in your area'
                        ]);
                    }
                    
                }else {
                    return response()->json([
                        'status_code' => 400,
                        'response' => 'unsuccessful',
                        'message' => 'Sorry No Driver Found in your area'
                    ]);
                }
                
                //echo json_encode($users); die;
                return response()->json([
                    'status_code' => 200,
                    'response' => 'success',
                    'message' => 'Booking successfully.'
                ]);
            }catch(\Exception $e){
                return response()->json([
                    'status_code' => 500,
                    'response' => 'error',
                    'message' => $e->getLine().' - '.$e->getMessage()
                ]);
            }       
    
    }
 }

