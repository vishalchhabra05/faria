<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\User;
class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($id)
    {
        $userData= User::where('id',$id)->first();
        //$userData  = User::findById($id);
        return view('admin::message.company_message',compact('userData','id'));
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
        return view('admin::edit');
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
        //
    }

   // get user details using ajax
   public function userProfile(Request $request){
   
    if(!empty($request->userData)){
        foreach ($request->userData as $key => $value) {
            $data[$value['id']]=$value['last_msg'];
            $userId[] = $value['id'];
            $userMessage[$value['id']] = $value['last_msgText'];
            $LastSeen[$value['id']] = $value['last_seen'];
        }
    }
    // get user data name and profile pic
    $userObj = User::whereIn('id',$userId)->get();
    $userData = [];
    $Timespend = [];
    foreach ($userObj as $key => $value) {
        $userObjData['id'] = $value->id;
        $userObjData['slug']= $value->slug;
        $userObjData['last_msgText'] = $userMessage[$value->id];
        $userObjData['last_seen'] = $LastSeen[$value->id];
        $userObjData['name']= $value->first_name .' '.$value->last_name;
        if($value->image){
            $profilePic = $value->image;
        }else{
            $profilePic = asset('images/default-avatar.jpg');
        }
        $userObjData['userProfile'] = $profilePic;
        $userObjData['ago'] = \Carbon\Carbon::createFromTimeStamp($data[$value->id]/1000)->diffForHumans();

        //get Online and Offline
        // $currentDateTime = \Carbon\Carbon::now();
        // $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $currentDateTime);
        // $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value->last_active);
        // $last_minutes = $to->diffInMinutes($from);
        // if($last_minutes >= 2){
        //     $userObjData['active'] = 'offline';
        // }else{
            $userObjData['active'] = 'online';
        // }
        $Timespend[$data[$value->id]] = $data[$value->id];
        $userData[$data[$value->id]] = $userObjData;
    }
    $Timespend = json_decode(json_encode($Timespend), true);

    rsort($Timespend);
    $result = [];
    foreach ($Timespend as $key => $value) {
        $result[] = $userData[$value];
    }
   
    return $result;

}

public function userDetail($slug,$id){
    try{
        $userData  = User::findBySlug($slug);
        return view('admin::message.company_message',compact('userData','id'));
    }catch(\Exception $e){
        dd($e);
    }
}
}
