<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Banner;
use App\Service;
use Image;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{
            //\Artisan::call('migrate');
            
            $data = Banner::orderBy('id','desc')->get();
            return view('admin::banner.index',compact('data'));
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
            $service = Service::get();
            return view('admin::banner.create',compact('service'));
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
            'image' => 'required|dimensions:width=600,height=300', 
            'service_id' => 'required'     
        ],
           [
            'image.dimensions'=> 'Please upload image size 600*300', // custom 
           ]
);
        try{
            $input = $request->all(); 
            if($request->hasFile('image')) {
                $image       = $request->file('image');
                $filename    = 'banner_image'.time().'.'.$image->getClientOriginalName();
                $image_resize = Image::make($image->getRealPath());              
                $image_resize->resize(600, 300);
                $image_resize->save('storage/user/'.$filename);
                $path = asset('storage/user/'.$filename); 
                $input['image'] = $path;
            }
             Banner::create($input);
             return redirect('admin/banner/list')
             ->with('success','Banner Added Success');
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
            $service = Service::get();
            $data = Banner::where('slug',$slug)->first();
            return view('admin::banner.edit',compact('data','service'));
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
            'image' => 'required|dimensions:width=600,height=300', 
            'service_id' => 'required'     
        ],
        [
            'image.dimensions'=> 'Please upload image size 600*300', // custom 
           ]
    
    );
        try{
            $input = $request->all();
            
        //     $data = $request->image;
        // list($type, $data) = explode(';', $data);
        // list(, $data)      = explode(',', $data);
        // $data = base64_decode($data);
        // $image_name= time().'.png';
        // $path = public_path() . "/images/" . $image_name;
        // file_put_contents($path, $data);
        // $path1 = asset('images/'.$image_name);
        // $input['image'] = $path1;
        // dd($path1);
       

            if($request->hasFile('image')) {

                $image       = $request->file('image');
                $filename    = 'banner_image'.time().'.'.$image->getClientOriginalName();
                $image_resize = Image::make($image->getRealPath());              
                $image_resize->resize(600, 300);

                // $image_resize->crop($request->input('w'), $request->input('h'), $request->input('x1'), $request->input('y1'));
                // $image_resize->save('storage/user/'.$filename);

                $image_resize->save('storage/user/'.$filename);
                $path = asset('storage/user/'.$filename); 
                $input['image'] = $path;
               
            }else{
                $input['image'] = $request->image;
            }

             
            unset($input['_token']);
             Banner::where('slug',$slug)->update($input);
            //  return response()->json(['success'=>'done']);
             return redirect('admin/banner/list')
             ->with('success','Banner Update Success');
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
          $data = Banner::where('slug',$slug);
          $data->delete();
          return redirect('admin/banner/list')
          ->with('success','Banner Deleted Success');
        }catch(\Exception $e){
            $data = ['error_type'=>$e->getSeverity(),
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !'); 
        }
    }

    /**App Status */
    public function appstatus($slug){
        try{
      $check = Banner::where('slug',$slug)->first();
      if($check->status == '1'){
          $status = '0';
          Banner::where('slug',$slug)->update(['status'=>$status]);
      }else{
          $status = '1';
      }
      $check1 = Banner::where('status','1')->count();
     
        if($check1 < '6'){
            Banner::where('slug',$slug)->update(['status'=>$status]);
            return redirect('admin/banner/list')
            ->with('success','Status Update Success');
        }else{
            return redirect('admin/banner/list')
            ->with('error','Please deactive any banner'); 
        }
      
        }catch(\Exception $e){
            $data = ['error_type'=>'Show App Home Screen banner Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }
}
