<?php
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\ErrorLog;
use App\Audit;
use App\Service;
use App\Invoice;
use Twilio\Rest\Client;

    function  count_user(){
        return User::whereHas('roles',function ($query) {
            $query->where('id','!=',1);
        })->count();
    }

    
    //START GET AVERAGE RATING VIEW  WITH HTML
    function getAvgRatiogView($totalReviewRating) {
        $star = '';
        for($i=1; $i<=$totalReviewRating; $i++) {
            $star .= '<i class="fa fa-star"></i>';
        }
        $unrat = 5 - $totalReviewRating;
        for($j=1; $j<=$unrat; $j++) {
            $star .= '<i class="fa fa-star-o"></i>';
        }
        return $star;
    }
    //END


    /**
     * Error Add
     */
    function errorAdd($data){
        $check = ErrorLog::create($data);
        $body = [
            'error_type' => $data['error_type'],  
            'error_message' => $data['error_message'],
            'error_ref'  => $data['error_ref'],
            'which_side' => $data['which_side']                 
        ];
    Mail::send('emails.error', $body, function ($message)
    {        
        $message->from(env('MAIL_FROM_ADDRESS'), 'Faria Application');        
        $message->to(env('ADMIN_EMAIL'));        
        $message->subject('Error Found');        
    });
       if(!$check){
           dd('Error');
       }
    }
    

    /**
     * Audit Add
     */
    function auditAdd($data){
        $check = Audit::create($data);
        if(!$check){
            dd('Error');
        }
    }

    /**
     * Get Service name
     */
    function GetService($id){
        $service_name = Service::where('id',$id)->first();
        return $service_name;
    }


    function totalAount($user_id){
       return Invoice::where('user_id',$user_id)->sum('sub_total');
    }

    function sendMessage($message, $recipients) {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($recipients, ['from' => $twilio_number, 'body' => $message]);
    }


    function Paypable($user_id){
        $data = Invoice::where('user_id',$user_id)->get();
        $fees = [];
        foreach($data as $key => $value){
          $value->tax = ($value->order->tax / 100) * $value->sub_total;
          $value->trust_fees = ($value->order->trust_fees / 100) * $value->sub_total;
            // $sum = $tax+$trust_fees;
            // $payable = $value->sub_total - $sum;
        }
            $tax = 0;
            foreach ($data as $item) {
              $tax += $item['tax'];
            }
            $trust_fees = 0;
            foreach ($data as $item) {
              $trust_fees += $item['trust_fees'];
            }
            return $trust_fees+$tax;
    }

    function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          $unit = strtoupper($unit);
      
          if ($unit == "K") {
            return ($miles * 1.609344);
          } else if ($unit == "N") {
            return ($miles * 0.8684);
          } else {
            return $miles;
          }
        }
      }