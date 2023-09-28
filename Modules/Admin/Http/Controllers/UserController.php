<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\RoleUser;
use Mail;
use Auth;
use App\CounteryCodes;
class UserController extends Controller
{


    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
       
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($slug)
    {
        try{
            if($slug == 'user'){
                $role_id = 3;
            }
             $user = $this->user->whereHas("roles",function($query) use ($role_id){
                $query->where("role_id",$role_id);  
            })->get();
            return view('admin::customer.index',compact('user','slug'));
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
            $country_code = CounteryCodes::get();
            return view('admin::customer.create',compact('country_code'));
        }catch(\Exception $e){
            //dd($e);
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
            'first_name' => 'required', 
            'last_name' => 'required',
            'mobile' => 'required|unique:users',
            'email' => 'required|email|unique:users', 
            'password' => 'required',
            'mobile_code' => 'required'
        ]);

        try{
            $input = $request->all(); 
            if($request->hasFile('image')) {
                $image = $request->file('image');
                $imagename = 'service_image'.time().'.'.$image->getClientOriginalExtension();
                $image->move('storage/user/',$imagename);
                $path = url('/storage/user/'.$imagename);
                $input['image'] = $path;
            }

            $input['password'] = bcrypt($input['password']); 
            $input['otp_verify'] = 1;    
            $user = User::create($input); 
            //otp send 
            $body = [
               
                'password' => $request->password,  
                'email' => $input['email'],                 
            ];

            Mail::send('emails.sendmail', $body, function ($message) use ($input)
            {        
                $message->from(env('MAIL_FROM_ADDRESS'), 'Faria Application');        
                $message->to($input['email']);        
                $message->subject('Faria Account Register.');        
            });

            
               $RoleUser = [
                   'role_id' => 3,
                   'user_id' => $user->id
               ]; 
          
            RoleUser::insert($RoleUser);
            return redirect('admin/user')
            ->with('success','User Added Success');
           
        }catch(\Exception $e){
            //dd($e);
            $data = ['error_type'=>'',
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
    public function show($slug)
    {
        try{
            $value = User::where('slug',$slug)->first();
            if($value){
                return view('admin::customer.show',compact('value'));
            }else{
                return redirect()->back()->withError('Oops,something wrong !'); 
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
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        try{
            $value = User::where('slug',$slug)->first();
            if($value){
                $country_code = CounteryCodes::get();
            return view('admin::customer.edit',compact('value','country_code'));
            }else{
                return redirect()->back()->withError('Oops,something wrong !');   
            }
        }catch(\Exception $e){
            $data = ['error_type'=>'User Edit Time',
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
            'first_name' => 'required', 
            'last_name' => 'required',
           
            'mobile_code' => 'required',
            'mobile' => 'required', 
            'email' => 'required'
        ]);
        try{
            

            $old_value = User::where('slug',$slug)->first();
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
            $input['password'] = bcrypt($input['password']); 
            //$input['otp_verify'] = 1;    
            unset($input['_token']);
            $user = User::where('slug',$slug)->update($input); 

            $new_value = User::where('slug',$slug)->first();
            //otp send 
            $body = [
               
                'password' => $request->password,  
                'email' => $input['email'],                 
            ];

            Mail::send('emails.sendmail', $body, function ($message) use ($input)
            {        
                $message->from(env('MAIL_FROM_ADDRESS'), 'Faria Application');        
                $message->to($input['email']);        
                $message->subject('Faria Account Register.');        
            });

            
            //    $RoleUser = [
            //        'role_id' => 3,
            //        'user_id' => $user->id
            //    ]; 
          
            // RoleUser::insert($RoleUser);

            //Audit Save record
           $old = array('first_name'=>$old_value['first_name'],
            'last_name'=>$old_value['email_name'],
            'mobile'=>$old_value['mobile'],
            'email'=>$old_value['email'],
            'password'=>$old_value['password']);

            $new = array('first_name'=>$new_value['first_name'],
            'last_name'=>$new_value['email_name'],
            'mobile'=>$new_value['mobile'],
            'email'=>$new_value['email'],
            'password'=>$new_value['password']);

            $data = ['section'=>'update user','app_type'=>'web',
            'old_value'=>json_encode($old),
            'new_value'=>json_encode($new)];
            auditAdd($data);
            return redirect('admin/user')
            ->with('success','User Updated Success');
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
            $user = User::where('slug',$slug);
            $user->delete();
            return redirect('admin/user')
            ->with('success','User Deleted Success');
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
            $data = User::findBySlug($slug);
            $status = $data->status;
            if($status == '1'){
                $status = '0';
            }else{
                $status = '1';
            }
            User::where(['slug'=>$slug])->update(['status'=>$status]);
            return redirect()->back()
            ->with('success','Customer user Status updated successfully.');
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
