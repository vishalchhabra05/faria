<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Walkthrough;
class WalkthroughController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{
          //\Artisan::call('migrate');
            $data = Walkthrough::get();
            return view('admin::walkthrough.index',compact('data'));
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
            return view('admin::walkthrough.create');
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
    public function store(Request $request,Walkthrough $walk)
    {
        request()->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        try{
            $walk->title = $request->title;
            $walk->body = $request->body;
            $walk->save();
           
            return redirect('admin/walkthrough/walkthrough')->with('success','walkthrough Added Success.');
        }
        catch(\Exception $e){
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
    public function edit($id)
    {
        try{
            $data = Walkthrough::where('id',$id)->first();
    
            return view('admin::walkthrough.edit',compact('data'));
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
    public function update(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'body' => 'required',
            
        ]);
        try{
            
          
            Walkthrough::where(['id'=>$request->id])->update(['title'=>$request->title,'body'=>$request->body]);
          
            return redirect('admin/walkthrough/walkthrough')
            ->with('success','Walkthrough Update successfully.');
            }catch(\Exception $e){
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
            Walkthrough::where('slug',$slug)->delete();
            return redirect('admin/walkthrough/walkthrough')
            ->with('success','Walkthrough Deleted successfully.');
            }catch(\Exception $e){
                return redirect()->back()->withError('Oops,something wrong !');
         }
    }
}
