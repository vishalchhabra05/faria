<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Price;
use App\Service;
use App\Varient;
use App\Cities;
class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{
            //\Artisan::call("migrate");
            $data = Price::get();
            return view('admin::price.index',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>$e->getSeverity(),
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
        try{
            $varient = Varient::get();
            $service = Service::get();
            $city = Cities::get();
            return view('admin::price.create',compact('varient','service','city'));
        }catch(\Exception $e){
            $data = ['error_type'=>$e->getSeverity(),
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
       
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([ 
            'price' => 'required', 
            'service_id' => 'required',
            'varient'  => 'required'       
        ]);
       try{
           $data = $request->all();
           
           $check = Price::where('service_id',$request->service_id)->first();
         
           if(!empty($check)){
            return redirect('admin/price/price')
            ->with('success','Price already added.');
           }else{
            Price::create($data);
            return redirect('admin/price/price')
            ->with('success','Price added success.');
           }  
       }catch(\Exception $e){
        $data = ['error_type'=>$e->getSeverity(),
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'web'];
        errorAdd($data);
        return redirect()->back()->withError('Oops,something wrong !');
       }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        try{
            $data = Price::where('slug',$slug)->first();
            $varient = Varient::get();
            $service = Service::get();
             $city = Cities::get();
            return view('admin::price.edit',compact('varient','service','data','city'));
        }catch(\Exception $e){
            $data = ['error_type'=>$e->getSeverity(),
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
    public function update(Request $request, $slug)
    {
       try{
           $data = $request->all();
           unset($data['_token']);
        Price::where('slug',$slug)->update($data);
        return redirect('admin/price/price')
        ->with('success','Price updated success.');
       }catch(\Exception $e){
        $data = ['error_type'=>$e->getSeverity(),
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
    public function destroy($slug)
    {
        try{
        $price = Price::where('slug',$slug);
        $price->delete();
        return redirect('admin/price/price')
        ->with('success','Price deleted success.');
        }catch(\Exception $e){
            $data = ['error_type'=>$e->getSeverity(),
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }
}
