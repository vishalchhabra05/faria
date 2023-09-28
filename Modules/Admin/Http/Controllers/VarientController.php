<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Varient;
class VarientController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{
            $data = Varient::get();
            return view('admin::varient.index',compact('data'));
        }catch(\Exception $e){

        }
        
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        try{
            return view('admin::varient.create');
        }catch(\Exception $e){

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
            'name' => 'required',        
        ]);
        try{
         $data = $request->all();
         Varient::create($data);
         return redirect('admin/varient/varient')
         ->with('success','variant added success.');
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
            $data = Varient::where('slug',$slug)->first();
            return view('admin::varient.edit',compact('data'));
        }catch(\Exception $e){

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
          Varient::where('slug',$slug)->update($data);
          return redirect('admin/varient/varient')
          ->with('success','variant update success.');
       }catch(\Exception $e){
           dd($e);
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
        $varient = Varient::where('slug',$slug);
        $varient->delete();
        return redirect('admin/varient/varient')
        ->with('success','variant deleted success');
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
