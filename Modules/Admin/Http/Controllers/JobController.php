<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Order;
use App\AssignOrder;
use App\Notification;
use App\QuotePrice;
class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{
             //\Artisan::call('migrate');
            //$order = Order::select('id')->where('status','0')->get();   
           // $data = AssignOrder::whereIn('order_id',$order)->get();
            $data = Order::orderBy('id','desc')->get();
            return view('admin::job.index',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'get all job request time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
       
    }


    public function acceptJob(Notification $notification,$id){
     try{
        $data = AssignOrder::where('id',$id)->first();
        //dd(['job_id'=>$data->order_id,'status'=>$data->status]);
        $check = AssignOrder::where('order_id',$data->order_id)->where('status','1')->get();
        if(count($check) > 0){
            return redirect()->back()->withError('Order Already accepted !'); 
        }else{
            AssignOrder::where('id',$id)->update(['status'=>'1']);
            Order::where('id',$data->order_id)->update(['status'=>'1']);
            $data = ['sender_id'=>$data->user_id,
            'receiver_id'=>$data->order->user_id,
            'from'=>'Notification',
            'message' => 'Your job has been accept',
            'status' => 'accept'];
            $notification->AcceptJobNotification($data);
            return redirect()->back()->withSuccess('Order Accept Success !'); 
        }
        
     
     }catch(\Exception $e){
        $data = ['error_type'=>'Job accept time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'web'];
        errorAdd($data);
        return redirect()->back()->withError('Oops,something wrong !'); 
     }
    }


    public function jobQuote(Request $request,$id){
        try{
           
            $data = AssignOrder::where('id',$id)->first();
            $value = ['sender_id'=>$data->user_id,
            'reciver_id'=>$data->order->user_id,
            'order_id'  => $data->order_id,
            'price'=>$request->price,
            'desc' => $request->desc,
            ];
            QuotePrice::create($value);
            Order::where('id',$data->order_id)->update(['min_price'=>$request->price]);
            return redirect()->back()->withSuccess('Quote send success !');
        }catch(\Exception $e){
            $data = ['error_type'=>'Job accept time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !'); 
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try{
            $data = Order::where('id',$id)->first();
            return view('admin::job.show',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'Job Detail Show Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !'); 
        }
      
    }


    public function reschedule(){
        try{

            $data = Order::where('status','7')->orderBy('id','desc')->get();
            return view('admin::job.reschedule',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'get reschedule job request time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }


    public function Track($id){
        try{
            $data = Order::where('id',$id)->first();
            $provider_lat = $data->assine->user->lat;
            $provider_long = $data->assine->user->long;
            $customer_lat = $data->user->lat;
            $customer_long = $data->user->long;
            return view('admin::job.track',compact('data','id','provider_lat','provider_long','custmer_long','customer_lat'));
        }catch(\Exception $e){
            $data = ['error_type'=>'Track User Page time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }

    public function reyaltime($id){
        try{
            $data = Order::where('id',$id)->first();
            $provider_lat = $data->assine->user->lat;
            $provider_long = $data->assine->user->long;
            $customer_lat = $data->user->lat;
            $customer_long = $data->user->long;
            return response()->json(['provider_lat'=>$provider_lat,
            'provider_long' => $provider_long,
            'customer_lat' => $customer_lat,
            'customer_long' => $customer_long]);
        }catch(\Exception $e){
            dd($e);
            $data = ['error_type'=>'Track Function reyal time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
       
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
