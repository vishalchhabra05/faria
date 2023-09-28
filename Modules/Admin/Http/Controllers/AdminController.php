<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\User;
use Auth;
use App\CounteryCodes;
class AdminController extends Controller
{
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
       // \Artisan::call('migrate');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $role_id = 2;
        $service = $this->user->whereHas("roles",function($query) use ($role_id){
          $query->where("role_id",$role_id);  
        })->count();
        $app_id = 3;
        $app = $this->user->whereHas("roles",function($query) use ($app_id){
          $query->where("role_id",$app_id);  
        })->count();
        return view('admin::index',compact('service','app'));
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
    public function show()
    {
       try{
          $data = User::where('id',auth()->user()->id)->first();
        return view('admin::profile.show',compact('data'));
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
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit()
    {
        try{
            $country_code = CounteryCodes::get();
            $value = User::where('id',auth()->user()->id)->first();
          return view('admin::profile.edit',compact('value','country_code'));
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
    public function update(Request $request)
    {
        request()->validate([
            'first_name' => 'required', 
            'last_name' => 'required',
            'mobile_code' => 'required',
            'mobile' => 'required|unique:users,mobile,'.Auth::user()->id,  
            'image' => 'required',
            'email' => 'email|unique:users,email,'.Auth::user()->id, 
        ]);
        try{
            $input = $request->all(); 
            if($request->hasFile('image')) {
                $image = $request->file('image');
                $imagename = 'service_image'.time().'.'.$image->getClientOriginalExtension();
                $image->move('storage/user/',$imagename);
                $path = url('/storage/user/'.$imagename);
                $input['image'] = $path;
            }else{
                $input['image'] = $request->image;
            }
         unset($input['_token']);
         User::where('id',auth()->user()->id)->update($input);
         return redirect('admin/profile/view') ->with('success','Profile Updated Success');
        }catch(\Exception $e){
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
