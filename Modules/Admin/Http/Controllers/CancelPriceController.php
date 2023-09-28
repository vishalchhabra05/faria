<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\CancelPriceMaster;
use App\Setting;
class CancelPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{
            $data = CancelPriceMaster::get();
            return view('admin::cancel.index',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'Show Cancel index page',
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
            return view('admin::cancel.create');
        }catch(\Exception $e){
            $data = ['error_type'=>'Cancel price create page',
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
            'type' => 'required', 
            'price' => 'required',
            'hours' => 'required'    
        ]);
        try{
            $input = $request->all(); 
            CancelPriceMaster::create($input);
             return redirect('admin/cancel-master/master')
             ->with('success','Price Added Success');
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
            $data = CancelPriceMaster::where('slug',$slug)->first();
            return view('admin::cancel.edit',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'Cancel price edit page',
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
        request()->validate([
            'type' => 'required', 
            'price' => 'required',
            'hours' => 'required'    
        ]);
        try{
            $input = $request->all(); 
            unset($input['_token']);
            CancelPriceMaster::where('slug',$slug)->update($input);
            return redirect('admin/cancel-master/master')->with('success','Price Update Success');
        }catch(\Exception $e){
            $data = ['error_type'=>'Cancel price Update time',
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
            CancelPriceMaster::where('slug',$slug)->delete();
            return redirect('admin/cancel-master/master')
            ->with('success','Price delete Success');
        }catch(\Exception $e){
            $data = ['error_type'=>$e->getSeverity(),
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !'); 
        }
    }

    public function basePrice(){
        try{
            $basePrice = Setting::where('slug','base_price')->first();
            return view('admin::cancel.baseprice',compact('basePrice'));
        }catch(\Exception $e){
            $data = ['error_type'=>'Cancel price create page',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }

    public function updateBaseFare(Request $request){
        request()->validate([
            'value' => 'required',   
        ]);
        try{
            $input = $request->all(); 
            unset($input['_token']);
            Setting::where('slug','base_price')->update($input);
            return redirect('admin/base-fare/base-fare')
            ->with('success','Price Update Success');
        }catch(\Exception $e){
            $data = ['error_type'=>'Base price Update time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }    
    }
}
