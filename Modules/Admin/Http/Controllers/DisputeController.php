<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Dispute;
use App\Invoice;

class DisputeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{
            $data = Dispute::get();
            return view('admin::dispute.index',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'Dispute Job Index Page.',
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
            $data = Dispute::where('id',$id)->first();
            return view('admin::dispute.view',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'Dispute View Page.',
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
    public function edit($order_id)
    {
        try{
            $data = Invoice::where('order_id',$order_id)->first();
           
            return view('admin::dispute.edit',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'Dispute View Page.',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !'); 
        }
       
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $order_id)
    {
        request()->validate([
            'type' => 'required', 
            'regular_hours' => 'required',
            'after_hours' => 'required',
            'extra_hours_cost' => 'required',
            'item' => 'required',
            'comment' => 'required',
            'sub_total' => 'required'    
        ]);

        try{
       $input = $request->all();
       unset($input['_token']);
       Invoice::where('order_id',$order_id)->update($input);
       return redirect('admin/dispute/manager')
       ->with('success','Invoice Update Success');
        }catch(\Exception $e){
            dd($e);
            $data = ['error_type'=>'Dispute Job Update Time.',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
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
