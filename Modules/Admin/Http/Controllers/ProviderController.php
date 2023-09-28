<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Service;
use App\Category;
use App\BusinessInformation;
use App\ProfessionalService;
//insurance add
use App\ProfessionalDetail;
use App\ReviewLink;
use App\Reference;
use App\SecondReference;
use App\BankAddress;
use App\AccountInformation;
use App\BankingInfo;
use App\User;
use App\ServiceProviderRequest;
use App\RoleUser;
use Mail;
use Auth;
use App\CounteryCodes;
use App\Notification;
use App\EditProfileRequest;
use App\Province;
use App\ProviderPhoto;
class ProviderController extends Controller
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
    public function index()
    {
        try{     
                $role_id = 2;
             $user = $this->user->whereHas("roles",function($query) use ($role_id){
                $query->where("role_id",$role_id);  
            })->get();
            return view('admin::provider.index',compact('user'));
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
    public function create($slug=null)
    {
        try{
            if($slug != null){
                $data = User::where('id',$slug)->first();
                $country_code = CounteryCodes::get();
                return view('admin::provider.create',compact('data','country_code'));
            }else{
                $data = '';
                $country_code = CounteryCodes::get();
                return view('admin::provider.create',compact('data','country_code'));
            }
          
        }catch(\Exception $e){
            $data = ['error_type'=>'Provider Create Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
       
    }

    public function postCreate(Request $request,$slug=null){
        request()->validate([
            'first_name' => 'required', 
            'last_name' => 'required',
            'mobile' => 'required|unique:users,mobile',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'mobile_code' => 'required'
        ]);
       
        try{
          
            $input = $request->all(); 
             unset($input['_token']);
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
            $input['otp_verify'] = 1;  

             //User Id And Password Send
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


            if($slug != null){
                $user = User::where('id',$slug)->update($input);
                
                //BusinessInformation::where('user_id',$id)->update($input);
                return redirect('admin/provider/provider/edit'.'/'.$slug)->with('success','Provider Update Success');
            } else{
                
                $user = User::create($input);
                $id = $user->id;
                $RoleUser = [
                    'role_id' => 2,
                    'user_id' => $id
                ]; 
                RoleUser::insert($RoleUser);
                ServiceProviderRequest::create(['user_id'=>$id,'service_provide_status'=>'0']);
                return redirect('admin/provider/step1'.'/'.$id)->with('success','Provider Added Success');
            } 
            
           
            
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
           
           
        }catch(\Exception $e){
            //dd($e);
            $data = ['error_type'=>'Add Provider User Time',
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
            $value = User::where('id',$id)->first();
            $service = Service::get();

            $user_service = ProfessionalService::where('user_id',$id)->first();
           if(!empty($user_service)){
                $user = $user_service;
                $myService = explode(",",$user->service); 
                $myServiceLicence = explode(",",$user->number);
           }else{
            $user = '';
            $myService = []; 
            $myServiceLicence = [];
           }

            return view('admin::provider.show',compact('value','service','user','myService','myServiceLicence'));
        }catch(\Exception $e){
           
            $data = ['error_type'=>'View Service Provider',
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
            return view('admin::provider.edit',compact('slug'));
        }catch(\Exception $e){
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
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $RoleUser = [
                'role_id' => 3,
                'user_id' => $id
            ]; 
            ServiceProviderRequest::where('user_id',$id)->update(['user_id'=>$id,'service_provide_status'=>0]);
      
       
        RoleUser::where('user_id',$id)->update($RoleUser);
            // $user = User::where('slug',$slug);
            // $user->delete();
            return redirect('admin/provider/provider')
            ->with('success','Service Provider Deleted Success');
        }catch(\Exception $e){
            dd($e);
            $data = ['error_type'=>'delete servide provider',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !'); 
        }
    }

    public function createStep1(Request $request,$id){
        //$bussiness_info = $request->session()->get('bussiness_info');
        $bussiness_info = BusinessInformation::where('user_id',$id)->first();
        $state = Province::get();
        $data = ProviderPhoto::where('user_id',$id)->first();
        return view('admin::provider.bussiness_info',compact('bussiness_info','id','state','data'));
    }

    public function postCreateStep1(Request $request,$id){
        request()->validate([
            'business_name' => 'required',
            'business_address' =>  'required',
            'unit' => 'required'   
        ]);
        try{
            $input = $request->all(); 
            unset($input['_token']);
            $input['user_id'] = $id;

            $data['user_id'] = $id;
            if($request->hasFile('passport_photo')) {
                $image = $request->file('passport_photo');
                $imagename = 'service_image'.time().'.'.$image->getClientOriginalExtension();
                $image->move('storage/user/',$imagename);
                $path = url('/storage/user/'.$imagename);
                $data['passport_photo'] = $path;
            }else{
                $data['passport_photo'] = $request->passport_photo;
            }

            if($request->hasFile('license_photo')) {
                $image = $request->file('license_photo');
                $imagename = 'service_image'.time().'.'.$image->getClientOriginalExtension();
                $image->move('storage/user/',$imagename);
                $path = url('/storage/user/'.$imagename);
                $data['license_photo'] = $path;
            }else{
                $data['license_photo'] = $request->license_photo;
            }

           

            $check = BusinessInformation::where('user_id',$id)->first();
            User::where('id',$id)->update(['lat'=>$request->lat,'long'=>$request->long]);
            unset($input['lat']);
            unset($input['long']);
            if(!empty($check)){
                ProviderPhoto::where('user_id',$id)->update($data);
                unset($input['passport_photo']);
                unset($input['license_photo']);
                ServiceProviderRequest::where('user_id',$id)->update(['user_id'=>$id]);
              BusinessInformation::where('user_id',$id)->update($input);
              return redirect('admin/provider/provider/edit'.'/'.$id)->with('success','Business Information Update Success'); 
            }else{
                ProviderPhoto::create($data);
                unset($input['passport_photo']);
                unset($input['license_photo']);
              BusinessInformation::create($input);
              ServiceProviderRequest::create(['user_id'=>$id,'service_provide_status'=>'0']);
              return redirect('admin/provider/step2'.'/'.$id)->with('success','Business Information Added Success'); 
            }
           
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



    public function createStep2(Request $request,$id){
        $service = Service::get();
        $user_service = ProfessionalService::where('user_id',$id)->first();
        if(!empty($user_service)){
            $edit = "Edit";
            $user = $user_service;
            $myService = explode(",",$user->service); 
            $myServiceLicence = explode(",",$user->number);
            return view('admin::provider.select_service',compact('service','id','user','edit','myService','myServiceLicence'));
        }else{
            $edit = "Add";
            return view('admin::provider.select_service',compact('service','id','edit'));
        }
        
    }

    public function postCreateStep2(Request $request,$id){
        request()->validate([
            'service' => 'required',
            'number' => 'required'
        ]);
        try{
            $input = $request->all(); 
         
            unset($input['_token']);
            $input['user_id'] = $id;
          
            $input['service'] = implode(",",$request->service);
            $input['number'] = implode(",",$request->number);
          
            $check = ProfessionalService::where('user_id',$id)->first();
            if(!empty($check)){
                ProfessionalService::where('user_id',$id)->update($input);
                return redirect('admin/provider/provider/edit'.'/'.$id)->with('success','Business Information Update Success'); 
            }else{
                ProfessionalService::create($input);
                return redirect('admin/provider/step3'.'/'.$id)->with('success','Business Information Added Success'); 
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

    public function createStep3(Request $request,$id){
        $detail = ProfessionalDetail::where('user_id',$id)->first();
        return view('admin::provider.insurance',compact('detail','id'));
    }

    public function postCreateStep3(Request $request,$id){
        request()->validate([
            'email' => 'required',
            'insurance_policy_number' => 'required',
            'insurance_company' => 'required' 
        ]);
        try{
            $input = $request->all(); 
           
            unset($input['_token']);
            $input['user_id'] = $id;
            
            $check = ProfessionalDetail::where('user_id',$id)->first();
            if(!empty($check)){
                ProfessionalDetail::where('user_id',$id)->update($input);
                return redirect('admin/provider/provider/edit'.'/'.$id)->with('success','Insurance Information Update Success');
            }else{
                ProfessionalDetail::create($input);
                return redirect('admin/provider/step4'.'/'.$id)->with('success','Insurance Information Added Success');
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



    public function createStep4(Request $request,$id){
        $detail = ReviewLink::where('user_id',$id)->first();
        return view('admin::provider.review_link',compact('detail','id'));
    }

    public function postCreateStep4(Request $request,$id){
        // request()->validate([
        //     'link' => 'required'
        // ]);
        try{
            $input = $request->all(); 
           
            unset($input['_token']);
            $input['user_id'] = $id;
            $check = ReviewLink::where('user_id',$id)->first();
            if(!empty($check)){
                ReviewLink::where('user_id',$id)->update($input);
                return redirect('admin/provider/provider/edit'.'/'.$id)->with('success','Review Link Update Success'); 
            }else{
                ReviewLink::create($input);
                return redirect('admin/provider/step5'.'/'.$id)->with('success','Review Link Added Success'); 
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


    public function createStep5(Request $request,$id){
        $detail = Reference::where('user_id',$id)->first();
        $country_code = CounteryCodes::get();
        return view('admin::provider.referance',compact('detail','id','country_code'));
    }

    public function postCreateStep5(Request $request,$id){
        request()->validate([
            'full_name' => 'required',
            'relationship' => 'required',
            'company' => 'required',
            'email' => 'required',
            'code' => 'required',
            'phone' => 'required',
            'description' => 'required',
        ]);
        try{
            $input = $request->all(); 
           
            unset($input['_token']);
            $input['user_id'] = $id;
            
            $check = Reference::where('user_id',$id)->first();
            if(!empty($check)){
                Reference::where('user_id',$id)->update($input);
                return redirect('admin/provider/provider/edit'.'/'.$id)->with('success','First Reference Update Success');
         //return redirect('admin/provider/step6'.'/'.$id)->with('success','First Reference Update Success');
            }else{
                Reference::create($input);
                return redirect('admin/provider/step6'.'/'.$id)->with('success','First Reference Added Success');
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

    public function createStep6(Request $request,$id){
        $country_code = CounteryCodes::get();
        $detail = SecondReference::where('user_id',$id)->first();
        return view('admin::provider.referance_second',compact('detail','id','country_code'));
    }

    public function postCreateStep6(Request $request,$id){
        request()->validate([
            'full_name' => 'required',
            'relationship' => 'required',
            'company' => 'required',
            'email' => 'required',
            'code' => 'required',
            'phone' => 'required',
            'description' => 'required',
        ]);
        try{
            $input = $request->all(); 
           
            unset($input['_token']);
            $input['user_id'] = $id;
            
            $check = SecondReference::where('user_id',$id)->first();
            if(!empty($check)){
                SecondReference::where('user_id',$id)->update($input);
                return redirect('admin/provider/provider/edit'.'/'.$id)->with('success','Second Reference Update Success'); 
            }else{
                SecondReference::create($input);
                //return redirect('admin/provider/step9'.'/'.$id)->with('success','Second Reference Added Success');
                return redirect('admin/provider/provider')->with('success','Service Provider Added Success');  
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

    public function createStep7(Request $request,$id){
        $detail = BankAddress::where('user_id',$id)->first();
        return view('admin::provider.bankaddress',compact('detail','id'));
    }

    public function postCreateStep7(Request $request,$id){
        request()->validate([
            'company_address' => 'required',
            'unit' => 'required',
            'city' => 'required',
            'state' => 'required'
        ]);
        try{
            $input = $request->all(); 
           
            unset($input['_token']);
            $input['user_id'] = $id;
            
            $check = BankAddress::where('user_id',$id)->first();
            if(!empty($check)){
                BankAddress::where('user_id',$id)->update($input);
                return redirect('admin/provider/provider/edit'.'/'.$id)->with('success','Bank Address Update Success'); 
            }else{
                BankAddress::create($input);
                return redirect('admin/provider/step8'.'/'.$id)->with('success','Bank Address Added Success'); 
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

    public function createStep8(Request $request,$id){
        $detail = AccountInformation::where('user_id',$id)->first();
        return view('admin::provider.account_info',compact('detail','id'));
    }

    public function postCreateStep8(Request $request,$id){
        request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
            'ssn_number' => 'required'
        ]);
        try{
            $input = $request->all(); 
           
            unset($input['_token']);
            $input['user_id'] = $id;
            
            $check = AccountInformation::where('user_id',$id)->first();
            if(!empty($check)){
                AccountInformation::where('user_id',$id)->update($input);
                return redirect('admin/provider/provider/edit'.'/'.$id)->with('success','Account Information Update Success');
            }else{
                AccountInformation::create($input);
                return redirect('admin/provider/step9'.'/'.$id)->with('success','Account Information Added Success');
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

    public function createStep9(Request $request,$id){
        $detail = BankingInfo::where('user_id',$id)->first();
        return view('admin::provider.bank_info',compact('detail','id'));
    }

    public function postCreateStep9(Request $request,$id){
        request()->validate([
            'tranist_number' => 'required',
            'institution_number' => 'required',
            'account_number' => 'required'
        ]);
        try{
            $input = $request->all(); 
           
            unset($input['_token']);
            $input['user_id'] = $id;
            
            $check = BankingInfo::where('user_id',$id)->first();
            if(!empty($check)){
                BankingInfo::where('user_id',$id)->update($input);
                // $RoleUser = [
                //     'role_id' => 2,
                //     'user_id' => $id
                // ]; 
                ServiceProviderRequest::where('user_id',$id)->update(['user_id'=>$id,'service_provide_status'=>1]);
                //RoleUser::where('user_id',$id)->update($RoleUser);
                return redirect('admin/provider/provider/edit'.'/'.$id)->with('success','Bank Information Update Success'); 
            }else{
                BankingInfo::create($input);
                // $RoleUser = [
                //     'role_id' => 2,
                //     'user_id' => $id
                // ]; 
                ServiceProviderRequest::where('user_id',$id)->update(['user_id'=>$id,'service_provide_status'=>1]);
                //RoleUser::create($RoleUser);
                return redirect('admin/provider/provider')->with('success','Service Provider Added Success'); 
            }
           
            // RoleUser::where('user_id',$id)->update($RoleUser);
            // RoleUser::create($RoleUser);
           
        }catch(\Exception $e){
           
            $data = ['error_type'=>'Add Service provider last step',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !'); 
        }
    }



    public function updateApprove($slug, Notification $notification){
        try{
            $data = ServiceProviderRequest::where('user_id',$slug)->first();
            $status = $data->service_provide_status;
            if($status == '1'){
                $status = '0';
            }else{
                $status = '1';
            }
            User::where(['id'=>$slug])->update(['approve'=>$status]);
            $values = ['sender_id'=>'1',
                'receiver_id'=>$slug,
                'from' =>'Notification',
                'message'=> 'Your account has been approved'];
                Notification::create($values);
                $notification->pushNotification($values);
            ServiceProviderRequest::where(['user_id'=>$slug])->update(['service_provide_status'=>$status]);
            return redirect()->back()
            ->with('success','Service Provider Status Updated Success.');
            }
            catch(\Exception $e){
                //dd($e);
                $data = ['error_type'=>'service provide status',
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
            ->with('success','Service Provide status Update Successfully.');
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
    

    //Edit Profile Status
    public function ProfileStatus($id){
        try{
           
        //$data = ServiceProviderRequest::where('user_id',$id)->first();
        $data = EditProfileRequest::where('id',$id)->first();
       
        $status = $data->status;
        if($status == '1'){
            $status = '0';
        }else{
            $status = '1';
        }
        EditProfileRequest::where(['id'=>$id])->update(['status'=>$status]);
        return redirect()->back()
        ->with('success','Service Provider Profile Status Updated Success.');
        }
        catch(\Exception $e){
          dd($e);
            $data = ['error_type'=>'service provide status',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }

    Public function UpdateProfileRequest(){
        try{
         $data = EditProfileRequest::get();
         return view('admin::provider.profilerequest',compact('data'));
        }catch(\Exception $e){
            $data = ['error_type'=>'service provide status',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }
}
