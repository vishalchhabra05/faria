<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Page;

class PageController extends Controller
{

    public function __construct(Page $page)
    {
		//\Artisan::call('migrate');
       // $this->middleware('auth');
        $this->page = $page;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
       try{
        $data = $this->page->get();
        return view('admin::cms.index',compact('data'));
        } catch(\Exception $e){
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
            return view('admin::cms.create');
        }
            catch(\Exception $e){
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request,Page $page)
    {
      
        request()->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        try{
            $input = $request->all(); 
            unset($input['_token']);
           Page::create($input);
            return redirect('admin/cms/cms')->with('success','Page Added Success.');
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


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($slug)
    {
        try{
            $data = Page::where('slug',$slug)->first();
            return view('admin::cms.view',compact('data'));
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
           $data = Page::where('slug',$slug)->first();
           return view('admin::cms.edit',compact('data'));
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
            'title' => 'required',
            'body' => 'required'
        ]);
        try{
            Page::where(['slug'=>$request->slug])->update(['title'=>$request->title,'body'=>$request->body,'status'=>1,'meta_tile' =>$request->meta_tile,'meta_keywords' =>$request->meta_keywords]);
            return redirect('admin/cms/cms')
            ->with('success','Page Update successfully.');
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
            Page::where('slug',$slug)->delete();
            return redirect('admin/cms/cms')
            ->with('success','Page Deleted successfully.');
            }catch(\Exception $e){
                $data = ['error_type'=>$e->getSeverity(),
            'error_message'=>$e->getMessage(),
            'error_ref'=>$e->getFile(),
            'which_side' => 'web'];
            errorAdd($data);
            return redirect()->back()->withError('Oops,something wrong !');
         }
    }

    /** Change Status */
    public function updateStatus($slug){
        try{
            $data = Page::where('slug',$slug)->first();
            if($data->status == '1'){
                $status = '0';
            }
            else{
                $status = '1'; 
            }
            Page::where('slug',$slug)->update(['status'=>$status]);
            return redirect('admin/cms/cms')->with('success','Status Change Success.');
         }catch(\Exception $e){
            return redirect()->back()->withError('Oops,something wrong !');
         }
    }

    /**Image Upload */
    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
        
            $request->file('upload')->move(public_path('images'), $fileName);
   
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/'.$fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }

    public function webpages($slug){
        try{
          $data = Page::where('slug',$slug)->first();
          return view('admin::cms.get',compact('data'));
        }catch(\Exception $e){
            return redirect()->back()->withError('Oops,something wrong !');
        }
    }


    public function Mobile($slug){
        try{
            $data = Page::where('slug',$slug)->first();
            return view('admin::cms.mobile',compact('data'));
        }catch(\Exception $e){
            return "Not Found";
        }
        
    }

}
