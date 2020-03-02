<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\UserRequest;
use Auth;

class UserController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =  User::where('is_admin', '!=', '1')->get();
        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->all());
        if($request->image != null){
            //Make image name unique
            $full_file_name = $request->image;
            $file_name = pathinfo($full_file_name, PATHINFO_FILENAME);
            $extension = $request->image->getClientOriginalExtension();
            $file_name_to_store = $file_name.'_'.time().'.'.$extension;
            
            //Upload image
            $path = $request->image->move(public_path('/images'), $file_name_to_store);
            $url = url('/images/'.$file_name_to_store);
            $user->update(['image' => $url]);
        }
        $user->update(['password' => bcrypt($request->password)]);
        session()->flash('message', trans('admin.user_created'));
        return redirect('users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.users.edit')->with('user', User::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());

        if($request->image != null){
            //Make image name unique
            $full_file_name = $request->image;
            $file_name = pathinfo($full_file_name, PATHINFO_FILENAME);
            $extension = $request->image->getClientOriginalExtension();
            $file_name_to_store = $file_name.'_'.time().'.'.$extension;
            
            //Upload image
            $path = $request->image->move(public_path('/images'), $file_name_to_store);
            $url = url('/images/'.$file_name_to_store);
            $user->update(['image' => $url]);
        }

        if($request->password != null){
            $user->update(['password' => bcrypt($request->password)]);
        }

        session()->flash('message', trans('admin.user_updated'));
        return redirect('users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }

    public function delete(Request $request){
        if($request->ajax()){
            User::find($request->id)->delete();
            return response()->json([
                'data' => 1,
            ], 200);
        }
    }

    public function blockUser(Request $request){
        if($request->ajax()){
            //block uder
            if($request->status == 0){
                User::find($request->id)->update(['is_blocked' => 1]);
                return response()->json([
                    'data' => 1,
                ], 200);
            }
            //unblock user
            else if($request->status == 1){
                User::find($request->id)->update(['is_blocked' => 0]);
                return response()->json([
                    'data' => 0,
                ], 200);
            }
        }
        return response()->json([
            'error' => 'error',
        ],404);
    }

    public function getBlockedUsers(){
        return view('admin.blocked_users.blocked')->with('users', User::where('is_blocked', 1)->get());
    }

    // public function getVerifiedUsers(){
    //     return view('admin.unblocked_users.verified')->with('users', User::where('is_blocked', 0)->get());
    // }
}
