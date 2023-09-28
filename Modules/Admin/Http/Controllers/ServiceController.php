<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Service;
use App\Category;
use App\HoursMaster;
use App\Cities;
use App\Varient;
use App\Price;
use Image;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
   
    public function checkWord($text){
        if(count(explode(' ', $text)) > 100)
            return 'more than 100 words';
    
    }

        public function index()
        {
        try{
            $data = Service::get();
            return view('admin::service.index',compact('data'));
        }catch(\Exception $e){
          
            $data = ['error_type'=>'file not found',
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
            $category = Category::where('status','1')->get();
            $hours = HoursMaster::get();
            return view('admin::service.create',compact('category','hours'));
        }catch(\Exception $e){
            $data = ['error_type'=>'file not found',
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
            'category_id' => 'required', 
            'service_name' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg',
            'icon' => 'required|mimes:jpeg,png,jpg', 
            'description' => 'required|max:255', 
            'price_note'  =>  'required|max:255'  
        ]);
        try{
            $input = $request->all(); 

            if($request->pickhours != null){
            $input['pickhours'] = implode(",",$input['pickhours']);
          }
                if($request->hasFile('image')) {

                    $image       = $request->file('image');
                    $filename    = 'service_image'.time().'.'.$image->getClientOriginalName();
                    $image_resize = Image::make($image->getRealPath());              
                    $image_resize->resize(600, 450);
                    $image_resize->save('storage/user/'.$filename);
                    $path = asset('storage/user/'.$filename); 
                    $input['image'] = $path;
                    

                }

                if($request->hasFile('icon')) {

                    $image       = $request->file('icon');
                    $filename    = 'service_image'.time().'.'.$image->getClientOriginalName();
                    $image_resize = Image::make($image->getRealPath());              
                    $image_resize->resize(600, 450);
                    $image_resize->save('storage/user/'.$filename);
                    $path = asset('storage/user/'.$filename); 
                    $input['icon'] = $path;


                }
             unset($input['_token']);
             Service::create($input);
             return redirect('admin/service/service')
             ->with('success','Service Added Success');
        }catch(\Exception $e){
            // $data = ['error_type'=>$e->getSeverity(),
            // 'error_message'=>$e->getMessage(),
            // 'error_ref'=>$e->getFile(),
            // 'which_side' => 'web'];
            // errorAdd($data);
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
            $value = Service::where('slug',$slug)->first();
            return view('admin::service.show',compact('value'));
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
            $data = Service::where('slug',$slug)->first();
          
            $category = Category::where('status','1')->get();
            $hours = HoursMaster::get();
            return view('admin::service.edit',compact('data','category','hours'));
          }catch(\Exception $e){
            //   $data = ['error_type'=>$e->getSeverity(),
            //   'error_message'=>$e->getMessage(),
            //   'error_ref'=>$e->getFile(),
            //   'which_side' => 'web'];
            //   errorAdd($data);
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
            'category_id' => 'required', 
            'service_name' => 'required',
            'image' => 'required',
            'icon' => 'required',
            'description' => 'required|max:255',
            'price_note'  =>  'required|max:255'    
        ]
    );
        try{
            $input = $request->all(); 
            if($request->pickhours != null){
                $input['pickhours'] = implode(",",$input['pickhours']);
              }
            if($request->hasFile('image')) {
                // $image = $request->file('image');
                // $imagename = 'service_image'.time().'.'.$image->getClientOriginalExtension();
                // $image->storeAs('public/user/',$imagename);
                // $path = url('/storage/user/'.$imagename);
                // $input['image'] = $path;
                $image       = $request->file('image');
                $filename    = 'service_image'.time().'.'.$image->getClientOriginalName();
                $image_resize = Image::make($image->getRealPath());              
                $image_resize->resize(600, 450);
                $image_resize->save('storage/user/'.$filename);
                $path = asset('storage/user/'.$filename); 
                $input['image'] = $path;
            }else{
                $input['image'] = $request->image;
            }

            if($request->hasFile('icon')) {
                // $icon = $request->file('icon');
                // $imagename = 'service_icon'.time().'.'.$icon->getClientOriginalExtension();
                // $icon->storeAs('public/user/',$imagename);
                // $path = url('/storage/user/'.$imagename);
                // $input['icon'] = $path;
                $image       = $request->file('icon');
                $filename    = 'service_image'.time().'.'.$image->getClientOriginalName();
                $image_resize = Image::make($image->getRealPath());              
                $image_resize->resize(600, 450);
                $image_resize->save('storage/user/'.$filename);
                $path = asset('storage/user/'.$filename); 
                $input['icon'] = $path;
            }else{
                $input['icon'] = $request->icon;
            }

         unset($input['_token']);
         Service::where('slug',$slug)->update($input);
         return redirect('admin/service/service')
         ->with('success','Service Updated Success');
        }catch(\Exception $e){
           
            //   $data = ['error_type'=>$e->getSeverity(),
            //   'error_message'=>$e->getMessage(),
            //   'error_ref'=>$e->getFile(),
            //   'which_side' => 'web'];
            //   errorAdd($data);
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
            $service = Service::where('slug',$slug);
            $service->delete();
            return redirect('admin/service/service')
            ->with('success','Service Deleted Success');
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
      $check = Service::where('slug',$slug)->first();
      if($check->appstatus == '1'){
          $appstatus = '0';
          Service::where('slug',$slug)->update(['appstatus'=>$appstatus]);
      }else{
          $appstatus = '1';
      }
      $check1 = Service::where('appstatus','1')->count();
     
       // if($check1 < '6'){
            Service::where('slug',$slug)->update(['appstatus'=>$appstatus]);
            return redirect('admin/service/service')
            ->with('success','Status Update Success');
        /*}else{
            return redirect('admin/service/service')
            ->with('error','Please deactive any service'); 
        }*/
      
        }catch(\Exception $e){
            $data = ['error_type'=>'Show App Home Screen Service Time',
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }

    //Add Price
    public function createPrice($id){
        try{
            $city = Cities::get();
            $varient = Varient::get();
            $check = Price::where('service_id',$id)->get();
          
            if(count($check) > 0){
                $total = count($check);
                $slected_varient = Price::where('service_id',$id)->first();
                return view('admin::price.editprice',compact('varient','city','id','check','slected_varient','total'));
            }else{
                return view('admin::price.addprice',compact('varient','city','id'));
            }
      
         
        }catch(\Exception $e){
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }

    public function storePrice(Request $request){
        request()->validate([
            'service_id' => 'required',
           
        ]);
        try{
            // Price::where('service_id',$request->service_id)->delete();
          foreach($request->city as $key => $value){ 
            if($request->city[$key]!= '') {   
                    $data = ['service_id'=>$request->service_id,'price'=>$request->price[$key],'extra_price'=>$request->extra_price[$key],'varient'=>$request->varient,'city'=>$request->city[$key],'state'=>$request->state[$key]];
                    Price::create($data); 
            } 
           }
        //    foreach($request->city as $key => $value){
        //       $keyName = 'price_'.$key;
        //         if($request->$keyName != null){
        //             $data = ['service_id'=>$request->service_id,'price'=>$request->$keyName,'varient'=>$request->varient,'city'=>$value];
        //             Price::create($data);  
        //        }   
        //    }
           return redirect('admin/service/service')
           ->with('success','service price added success.'); 
           
        }catch(\Exception $e){
            return redirect()->back()->withError('Oops,something wrong !');
        }

    }

     //Update Price
     public function UpdatePrice(Request $request){
        try{
          
              Price::where('service_id',$request->service_id)->delete();
             foreach($request->city as $key => $value){  
              if($request->city[$key]!= '') {
                $data = ['service_id'=>$request->service_id,'price'=>$request->price[$key],'extra_price'=>$request->extra_price[$key],'varient'=>$request->varient,'city'=>$request->city[$key],'state'=>$request->state[$key]];
                Price::create($data);  
              } 
            }

            // foreach($request->id as $key => $value){
            //     $keyName = 'price_'.$key;
            //       if($request->$keyName != null){
            //           $data = ['service_id'=>$request->service_id,'price'=>$request->$keyName,'varient'=>$request->varient,'city'=>$request->city[$key]];
            //           Price::where('id',$value)->update($data);  
            //      }   
            //  }
             return redirect('admin/service/service')
             ->with('success','service price Update success.'); 
        }catch(\Exception $e){
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }
}
