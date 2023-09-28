<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Invoice;
use DB;
class FinancesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        try{
           if($request->from_date){ 
             $from = \Carbon\Carbon::parse($request->from_date)->format('Y-m-d'); 
             $to = \Carbon\Carbon::parse($request->to_date)->format('Y-m-d');
                $data= Invoice::whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to)->groupBy('user_id')->selectRaw('*')->get();
           }else{
                $data= Invoice::groupBy('user_id')->selectRaw('*')->get();
           }
           
            return view('admin::finances.index',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'finance Controller index function',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
      
    }


    public function listing($user_id){
        try{
            $data= Invoice::where('user_id',$user_id)->get();
            return view('admin::finances.listing',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'finance Controller listing function',
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
            $data = Invoice::where('id',$id)->first();
            return view('admin::finances.show',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'finance Controller show function',
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

    //payment
    public function Paymet($user_id){
        try{ 
     


            $stripe = new \Stripe\StripeClient(
                'sk_test_51H7fccLVBFA034ip2OJyNrnCGaiycIi4iTv33KkXZKedabOiXSvOMqdCJsnymHIFKHcPUUfeWGK9bPJCmbOsA7du00QMvu0iD0'
              );
              \Stripe\Stripe::setApiKey('sk_test_51H7fccLVBFA034ip2OJyNrnCGaiycIi4iTv33KkXZKedabOiXSvOMqdCJsnymHIFKHcPUUfeWGK9bPJCmbOsA7du00QMvu0iD0');   
            
              $stripe->accounts->delete(
               
                []
              );

              $data1 =  $stripe->tokens->create([
                'bank_account' => [
                    'country' => 'US',
                    'currency' => 'usd',
                    'account_holder_name' => 'Mohan Soni',
                    'account_holder_type' => 'individual',
                    'routing_number' => '110000000',
                    'account_number' => '000123456789',
                  ],
              ]);

            
              $customer = \Stripe\Customer::create([
                'email' => 'mohan.user@example.com',
                'source' => $data1->id,
              ]);
              
             
             $customer3 = $stripe->customers->verifySource(
                $customer->id,
                $customer->default_source,
                ['amounts' => [32, 45]]
              );
              $data =   $stripe->accounts->create([
                'type' => 'custom',
                'country' => 'US',
                'email' => 'mohan.user@example.com',
                'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers' => ['requested' => true],
                ],
                ]);
             
          //  $customer4 = $stripe->transfers->create([
          //       'amount' => 4,
          //       'currency' => 'usd',
          //       'destination' => $data->id
          //     ]);

              $customer4 = $stripe->transfers->create([
                'amount' => 10.00,
                'currency' => 'usd',
                'destination' => $data->id,
              ]);
          return $customer4;
        }catch(\Stripe\Exception\CardException $e) {
            dd($e);
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            echo 'Status is:' . $e->getHttpStatus() . '\n';
            echo 'Type is:' . $e->getError()->type . '\n';
            echo 'Code is:' . $e->getError()->code . '\n';
            // param is '' in this case
            echo 'Param is:' . $e->getError()->param . '\n';
            echo 'Message is:' . $e->getError()->message . '\n';
          } catch (\Stripe\Exception\RateLimitException $e) {
            dd($e);
            // Too many requests made to the API too quickly
          } catch (\Stripe\Exception\InvalidRequestException $e) {
            dd($e);
            // Invalid parameters were supplied to Stripe's API
          } catch (\Stripe\Exception\AuthenticationException $e) {
            dd($e);
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
          } catch (\Stripe\Exception\ApiConnectionException $e) {
            dd($e);
            // Network communication with Stripe failed
          } catch (\Stripe\Exception\ApiErrorException $e) {
            dd($e);
            // Display a very generic error to the user, and maybe send
            // yourself an email
          } catch (Exception $e) {
              dd($e);
            // Something else happened, completely unrelated to Stripe
          }
    }
}
