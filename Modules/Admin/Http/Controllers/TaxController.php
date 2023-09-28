<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\ServiceTex;
use App\Province;
class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{
            $data =  ServiceTex::get();
            return view('admin::taxes.index',compact('data'));
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
            $state =  Province::get();
            return view('admin::taxes.create',compact('state'));
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
            'state' => 'required', 
            'texes' => 'required'     
        ]);
        try{
         $input = $request->all();
         $check = ServiceTex::where('state',$request->state)->first();
         if(!empty($check)){
            return redirect('admin/tax/tax')
            ->with('success','Taxes allready added.');
         }else{
            ServiceTex::create($input);
            return redirect('admin/tax/tax')
               ->with('success','Taxes added success.');
         }
        
        }catch(\Exception $e){
            $data = ['error_type'=>'taxes add time',
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
    public function edit($id)
    {
        try{
            $data = ServiceTex::where('id',$id)->first();
            $state = Province::get();
            return view('admin::taxes.edit',compact('data','state'));
        }catch(\Exception $e){
            $data = ['error_type'=>'taxes edit time',
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
    public function update(Request $request, $id)
    {
        request()->validate([ 
            'state' => 'required', 
            'texes' => 'required'     
        ]);
        try{
         $input = $request->all();
         unset($input['_token']);
         ServiceTex::where('id',$id)->update($input);
         return redirect('admin/tax/tax')
         ->with('success','Taxes update success.');
        }catch(\Exception $e){
            $data = ['error_type'=>'taxes update time',
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
      try{
        $data = ServiceTex::where('id',$id);
        $data->delete();
        return redirect('admin/tax/tax')
        ->with('success','Taxes delete success.');
      }catch(\Exception $e){
        $data = ['error_type'=>'taxes delete time',
        'error_message'=>$e->getMessage(),
        'error_ref'=>$e->getFile(),
        'which_side' => 'web'];
        errorAdd($data);
        return redirect()->back()->withError('Oops,something wrong !');
      }
    }
}
