<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    public function UserDetail(){
        return $this->belongsTo('App\User','sender_id','id');
    }

    function sendNotificationCronJob($data){
        try{
            foreach($data as $val){
                Notification::create(['sender_id'=>$val->sender_id,
                'receiver_id'=>$val->receiver_id,
                'from'=>$val->from,
                'message' => $val->message,
                'status' => $val->order_status]);
                $userCount = User::where('id',$val->receiver_id)->first();
                if($val->from == "chat"){
                $updateData = ['chat_count' => $userCount->chat_count+1];
                $top = "You have a message from ".Auth::user()->name;
                }elseif($val->from == "like"){
                $updateData = ['notification_count' => $userCount->notification_count+1];
                $top = Auth::user()->name." liked your post.";
                }elseif($val->from == "comment"){
                $updateData = ['notification_count' => $userCount->notification_count+1];
                $top = Auth::user()->name." commented on your post.";
                }else{
                $updateData = ['notification_count' => $userCount->notification_count+1];
                $top = $val->message;
                }
                User::where('id',$val->receiver_id)->update($updateData);
                
                $userData = User::where('id',$val->receiver_id)->first();
                
                $badge = $userData->chat_count + $userData->notification_count;
                
                if(!empty($userData->device_token)){
                
                if (!defined('Notifiction_API_ACCESS_KEY')){
                define( 'Notifiction_API_ACCESS_KEY', 'AAAAnWLICtc:APA91bEYaGmLcsPdPzF8NVGn3nqBb_-vc1Op6uQ8eGyrByNu4pnoyedt7jX5J0A9cRaYnNEYLjBGqSerJBlNqbSB30uB9lDBb2z0dR2MO8licUwu16h3RLeU2YiOUtROtur7L8tBY2e-');
                }
                
                $notification = array
                (
                "body" => $top,
                "title" => "New Message",
                "vibrate" => 1,
                "sound" => 1,
                "badge" => (int)$badge,
                "color" => "#3364ac",
                "user_id" => $val->user_id,
                "type" => $val->from,
                );
                $msg = array
                (
                "user_id" => $val->user_id
                );
                $registrationIds = $userData->device_token;
                $fields = array
                (
                "to" => $registrationIds,
                "priority" => "high",
                "notification" => $notification,
                "data" => $notification,
                "type" => $val->from
                );
                
                
                $headers = array(
                "Authorization:key = AAAAnWLICtc:APA91bEYaGmLcsPdPzF8NVGn3nqBb_-vc1Op6uQ8eGyrByNu4pnoyedt7jX5J0A9cRaYnNEYLjBGqSerJBlNqbSB30uB9lDBb2z0dR2MO8licUwu16h3RLeU2YiOUtROtur7L8tBY2e-",
                "Content-Type: application/json"
                );
                
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch );
                if (curl_error($ch)) {
                return curl_error($ch);
                }
                curl_close($ch);
                }
            }

        }catch(\Exception $e){
            dd($e);
        }
    }

    //job accept function 
    function AcceptJobNotification($data){
        try{
            Notification::create(['sender_id'=>$data['sender_id'],
            'receiver_id'=>$data['receiver_id'],
            'from'=>$data['from'],
            'message' => $data['message'],
            'status' => $data['status']]);
            $userCount = User::where('id',$data['receiver_id'])->first();
            if($data['from'] == "chat"){
            $updateData = ['chat_count' => $userCount->chat_count+1];
            $top = "You have a message from ".Auth::user()->name;
            }elseif($data['from'] == "like"){
            $updateData = ['notification_count' => $userCount->notification_count+1];
            $top = Auth::user()->name." liked your post.";
            }elseif($data['from'] == "comment"){
            $updateData = ['notification_count' => $userCount->notification_count+1];
            $top = Auth::user()->name." commented on your post.";
            }else{
            $updateData = ['notification_count' => $userCount->notification_count+1];
            $top = $data['message'];
            }
            User::where('id',$data['receiver_id'])->update($updateData);
            
            $userData = User::where('id',$data['receiver_id'])->first();
            
            $badge = $userData->chat_count + $userData->notification_count;
            
            if(!empty($userData->device_token)){
            
            if (!defined('Notifiction_API_ACCESS_KEY')){
            define( 'Notifiction_API_ACCESS_KEY', 'AAAAnWLICtc:APA91bEYaGmLcsPdPzF8NVGn3nqBb_-vc1Op6uQ8eGyrByNu4pnoyedt7jX5J0A9cRaYnNEYLjBGqSerJBlNqbSB30uB9lDBb2z0dR2MO8licUwu16h3RLeU2YiOUtROtur7L8tBY2e-');
            }
            
            $notification = array
            (
            "body" => $top,
            "title" => "New Message",
            "vibrate" => 1,
            "sound" => 1,
            "badge" => (int)$badge,
            "color" => "#3364ac",
            "user_id" => $data['receiver_id'],
            "type" => $data['from'],
            );
            $msg = array
            (
            "user_id" => $data['receiver_id']
            );
            $registrationIds = $userData->device_token;
            $fields = array
            (
            "to" => $registrationIds,
            "priority" => "high",
            "notification" => $notification,
            "data" => $notification,
            "type" => $data['from']
            );
            
            
            $headers = array(
            "Authorization:key = AAAAnWLICtc:APA91bEYaGmLcsPdPzF8NVGn3nqBb_-vc1Op6uQ8eGyrByNu4pnoyedt7jX5J0A9cRaYnNEYLjBGqSerJBlNqbSB30uB9lDBb2z0dR2MO8licUwu16h3RLeU2YiOUtROtur7L8tBY2e-",
            "Content-Type: application/json"
            );
            
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch );
            if (curl_error($ch)) {
            return curl_error($ch);
            }
            curl_close($ch);
            }
        }catch(\Exception $e){
          dd($e);
        }
    }

    public function pushNotification($data){
        try{
       
    //   dd(['sender' => $data['sender_id'], 'reciver' =>$data['receiver_id'],'from' => $data['from'],'message' => $data['message']]);
    
      $userCount = User::where('id',$data['receiver_id'])->first();
      
      $updateData = ['notification_count' => $userCount->notification_count+1];
      $top = $data['message'];
     
      User::where('id',$data['receiver_id'])->update($updateData);
      
      $userData = User::where('id',$data['receiver_id'])->first();
      
      $badge = $userData->chat_count + $userData->notification_count;
      //dd($userData->id);
      if(!empty($userData->device_token)){
      
      if (!defined('Notifiction_API_ACCESS_KEY')){
        define( 'Notifiction_API_ACCESS_KEY', 'AAAAnWLICtc:APA91bEYaGmLcsPdPzF8NVGn3nqBb_-vc1Op6uQ8eGyrByNu4pnoyedt7jX5J0A9cRaYnNEYLjBGqSerJBlNqbSB30uB9lDBb2z0dR2MO8licUwu16h3RLeU2YiOUtROtur7L8tBY2e-');
      }
      
      $notification = array
      (
      "body" => $top,
      "title" => "New Message",
      "vibrate" => 1,
      "sound" => 1,
      "badge" => (int)$badge,
      "color" => "#3364ac",
      "user_id" => $data['receiver_id'],
      "type" => $data['from'],
      "tag" => @$data['tag']??'',
      "order_id" => @$data['order_id']??'',
      "status" => @$data['status']??'',
      );
      $msg = array
      (
      "user_id" => $data['receiver_id']
      );
      $registrationIds = $userData->device_token;
      $fields = array
      (
      "to" => $registrationIds,
      "priority" => "high",
      "notification" => $notification,
      "data" => $notification,
      "type" => $data['from']
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
     /* echo "<pre>";
      print_r($result); die;
       dd($result);*/
      if (curl_error($ch)) {
      return curl_error($ch);
      }
      curl_close($ch);
      }

        }catch(\Exception $e){
            dd($e);
        }
    }
}
