<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Discount;
use DB;
use DataTables;
class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        try{
             //\Artisan::call("migrate");
            if ($request->ajax()) {

                DB::statement(DB::raw('set @rownum=0'));

                $data = Discount::get(['discounts.*', 
                        DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                return Datatables::of($data)
                    ->removeColumn('id')
                    ->addColumn('action', function($row) {
                            $delete = "'".url("/admin/discount/delete",$row->slug)."'";
                            return '
                            <a class="icon-btn delete"  href="javascript:void(0);" onclick="deleteRecord('.$delete.')"><i class="fal fa-times"></i></a>
                            <a  data-toggle="tooltip" title="Update" type="button" href="/admin/discount/edit/'. $row->slug .'" class="icon-btn preview"><i class="fal fa-edit"></i></a>';
                        })
                    ->make(true);
                    
                //$data = Discount::select('*');  
                // return Datatables::eloquent(Discount::query())
                // ->addColumn('action', function($row) {
                //     $delete = "'".url("/admin/discount/delete",$row->slug)."'";
                //     return '
                //     <a class="icon-btn delete"  href="javascript:void(0);" onclick="deleteRecord('.$delete.')"><i class="fal fa-times"></i></a>
                //     <a  data-toggle="tooltip" title="Update" type="button" href="/admin/discount/edit/'. $row->slug .'" class="icon-btn preview"><i class="fal fa-edit"></i></a>';
                // })
                // ->rawColumns(['action' => 'action'])
                // ->make(true);
            }
            
            return view('admin::discount.index');
            // $data = Discount::orderBy('id','DESC')->get();
            // return view('admin::discount.index',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'Discount Index Function Time',
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
            return view('admin::discount.create');
        }catch(\Exception $e){
            $data = ['error_type'=>'Discount create Function Time',
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
            'coupon' => 'required',
            'expirey_date' => 'required',
            'price' => 'required'   
        ]);
        try{
            $input = $request->all();
            Discount::create($input);
            return redirect('admin/discount/all')
            ->with('success','Coupon Added Success');
        }catch(\Exception $e){
            $data = ['error_type'=>'Discount Store Function Time',
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
            $data = Discount::where('slug',$slug)->first();
            return view('admin::discount.edit',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'Discount Edit Function Time',
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
            'coupon' => 'required',
            'expirey_date' => 'required',
            'price' => 'required'   
        ]);
        try{
          $input = $request->all();
          unset($input['_token']);
          Discount::where('slug',$slug)->update($input);
          return redirect('admin/discount/all')
          ->with('success','Coupon Update Success');
        }catch(\Exception $e){
            $data = ['error_type'=>'Discount Update Function Time',
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
        Discount::where('slug',$slug)->delete();
        return redirect('admin/discount/all')
        ->with('success','Coupon Delete Success');
        }catch(\Exception $e){
            $data = ['error_type'=>'Discount Distory Function Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }


   
}
