<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\AssignOrder;
use App\Notification;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      
        if(auth()->user()->roles[0]->name == "admin"){
            return redirect('admin');
        }else{
            auth()->logout();
            return redirect('login')->with(['warning'=>'You have no permission.']);
        }
    }

    // public function cronJob(Notification $notification){
    //     try{
         
    //         $order = Order::select('id')->where('status','0')->where('schedule','>',date('Y-m-d h:m:s'))->get();
    //         $assine = AssignOrder::wherein('order_id',$order)->get();
    //                foreach($assine as $val){
    //                         $val->from = "Notification";
    //                         $val->receiver_id = $val->user_id;
    //                         $val->sender_id = $val->order->user_id;
    //                         $val->message = "New order received !!";
    //                         $val->order_id = $val->order_id;
    //                         $val->order_status = "Pending";
    //                 }
    //                 $notification->sendNotificationCronJob($assine);
    //         return $data  = ["status" => 200,"message"=>"Order assigned successfully"];
    //     }catch(\Exception $e){
    //         return $data  = ["status" => 500,"message"=>"Something went wrong","data" => $e]; 
    //     }
    // }
}
