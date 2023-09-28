<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Cities;
use App\Province;
class CityController extends Controller
{

    public function __construct(Cities $cities,Province $province)
    {
       $this->cities = $cities;
       $this->state = $province;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = $this->cities->get();
        return view('admin::city.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $state = $this->state->get();
        return view('admin::city.create',compact('state'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'state_id' => 'required',
            'city' => 'required'  
        ]);
        try{
            foreach($request->city as $key => $value){
                
                if($value != null){
                    $data = ['state_id'=> $request->state_id,'city'=>$value];
                    $check = $this->cities->where('state_id',$request->state_id)->where('city',$value)->first();
                    if(empty($check)){
                        Cities::create($data);
                    }
                }                
            }
            return redirect('admin/city/city')
                 ->with('success','City Added successfully.');
        }catch(\Exception $e){
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
        $data = $this->cities->where('id',$id)->first();
        $state = $this->state->get();
        return view('admin::city.create',compact('data','state'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'state_id' => 'required',
            'city' => 'required'  
        ]);
       try{
          
           $input = $request->all();
           unset($input['_token']);
        $this->cities->where('id',$id)->update($input);
        return redirect('admin/city/city')
        ->with('success','City Update successfully.');
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
        try{
            $this->cities->where('id',$id)->delete();
            return redirect('admin/city/city')
            ->with('success','City Delete successfully.');
        }catch(\Exception $e){
            return redirect()->back()->withError('Oops,something wrong !'); 
        }
    }
}
