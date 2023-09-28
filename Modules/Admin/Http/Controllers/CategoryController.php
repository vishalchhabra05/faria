<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{
            $data = Category::get();
            return view('admin::category.index',compact('data'));
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
            return view('admin::category.create');
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
            'category_name' => 'required',   
        ]);
        try{
            $input = $request->all(); 
            unset($input['_token']);
             Category::create($input);
            return redirect('admin/category/category')
            ->with('success','Category Added Success');
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
          $data = Category::where('slug',$slug)->first();
          return view('admin::category.edit',compact('data'));
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
        request()->validate([
            'category_name' => 'required',   
        ]);
        try{
            $input = $request->all(); 
            unset($input['_token']);
             Category::where('slug',$slug)->update($input);
            return redirect('admin/category/category')
            ->with('success','Category Update Success');
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
            $category = Category::where('slug',$slug);
            $category->delete();
            return redirect('admin/category/category')
            ->with('success','Category Deleted Success');
        }catch(\Exception $e){
			
            $data = ['error_type'=>$e->getSeverity(),
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !'); 
        }
    }




    public function userstatus($slug){
        try{
            $data = Category::findBySlug($slug);
            $status = $data->status;
            if($status == '1'){
                $status = '0';
            }else{
                $status = '1';
            }
            Category::where(['slug'=>$slug])->update(['status'=>$status]);
            return redirect()->back()
            ->with('success','Category Status Updated Success.');
            }
            catch(\Exception $e){
                $data = ['error_type'=>$e->getSeverity(),
                'error_message'=>$e->getMessage(),
                'error_ref'=>$e->getFile(),
                'which_side' => 'web'];
                errorAdd($data);
                return redirect()->back()->withError('Oops,something wrong !');
            }
    }

}
