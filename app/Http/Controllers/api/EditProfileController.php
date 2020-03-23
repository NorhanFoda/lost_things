<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Lang;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PostResourceCollection;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;

class EditProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getUserProfile($id){
        // return response()->json([
        //     'data' => User::with('posts')->where('id', $id)->first()
        // ], 200);
        return response()->json([
            'data' => new UserResource(User::find($id))
        ], 200);
    }

    public function getOldEmail(){
        return response()->json([
            'data' => auth()->user()->email
        ], 200);
    }

    //Change user name
    public function changeName(Request $request, $id){
        if(auth()->user()->id == $id){
            if($request->name != null){
                User::find($id)->update(['name' => $request->name]);
                return response()->json([
                    'data' => 'Name changed'
                ], 200);
            }
            else{
                return response()->json([
                    'error' => 'New name is required'
                ], 400);        
            }
        }
        return response()->json([
            'error' => 'User is UN AUTHORIZED to performe this action'
        ], 400);
    }

    //Change user location
    public function changeLocation(Request $request, $id){
        if(auth()->user()->id == $id){
            if($request->location != null){
                $user = User::find($id);
                $user->location = $request->location;
                $user->save();
                return response()->json([
                    'data' => 'Location changed'
                ], 200);
            }
            return response()->json([
                'error' => 'New location is required'
            ], 400);    
        }
        return response()->json([
            'error' => 'User is UN AUTHORIZED to performe this action'
        ], 400);
    }

    //Change user birth date
    public function changeBirthDate(Request $request, $id){
        if(auth()->user()->id == $id){
            if($request->birth_date != null){
                $user = User::find($id);
                $user->birth_date = $request->birth_date;
                $user->save();
                return response()->json([
                    'data' => 'Date of birth changed'
                ], 200);
            }
            return response()->json([
                'error' => 'New Date of birth is required'
            ], 400);    
        }
        return response()->json([
            'error' => 'User is UN AUTHORIZED to performe this action'
        ], 400);
    }

    //Change user image
    public function changeImage(Request $request, $id){
        if(auth()->user()->id == $id){
            if($request->image != null){
                //Validate image
                $imageRules = array(
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                );
                $image_to_validate = array('image' => $request->image);
                $imageValidator = Validator::make($image_to_validate, $imageRules);
                if ($imageValidator->fails()) {
                    return $imageValidator->messages();
                }

                //Make image name unique
                $full_file_name = $request->image;
                $file_name = pathinfo($full_file_name, PATHINFO_FILENAME);
                $extension = $request->image->getClientOriginalExtension();
                $file_name_to_store = $file_name.'_'.time().'.'.$extension;
                
                //Upload image
                $path = $request->image->move(public_path('/'), $file_name_to_store);
                $url = url('/'.$file_name_to_store);

                $user = User::find($id);
                $user->image = $url;
                $user->save();
                return response()->json([
                    'data' => 'Image changed'
                ], 200);
            }
            return response()->json([
                'error' => 'New image is required'
            ], 400);    
        }
        return response()->json([
            'error' => 'User is UN AUTHORIZED to performe this action'
        ], 400);
    }

    //Activate user location
    public function activateLocation(Request $request, $id){
        if(auth()->user()->id == $id){
            if($request->location_active != null){
                $user = User::find($id);
                $user->location_active = $request->location_active;
                $user->save();

                if($request->location_active == 1){
                    return response()->json([
                        'data' => 'Location activated'
                    ], 200);
                }
                if($request->location_active == 0){
                    return response()->json([
                        'data' => 'Location deactivated'
                    ], 200);
                }
            }
            return response()->json([
                'error' => 'Location cativation required, 0 not active, 1 active'
            ], 400);
        }
        return response()->json([
            'error' => 'User is UN AUTHORIZED to performe this action'
        ], 400);
    }

    //Activate user notifications
    public function activateNotification(Request $request, $id){
        if(auth()->user()->id == $id){
            if($request->notification_active != null){
                $user = User::find($id);
                $user->notification_active = $request->notification_active;
                $user->save();

                if($request->notification_active == 1){
                    return response()->json([
                        'data' => 'Notifications activated'
                    ], 200);
                }
                if($request->notification_active == 0){
                    return response()->json([
                        'data' => 'Notifications deactivated'
                    ], 200);
                }
            }
            return response()->json([
                'error' => 'Notification ativation required, 0 not active, 1 active'
            ], 400);
        }
        return response()->json([
            'error' => 'User is UN AUTHORIZED to performe this action'
        ], 400);
    }

    //Change language
    public function changeLang(Request $request, $id){
        if(auth()->user()->id == $id){
            if($request->lang != null){
                foreach(Lang::all() as $lang){
                    $lang->is_selected = 0;
                    $lang->save();
                }
                
                $lang = Lang::where('lang', $request->lang)->first();
                $lang->is_selected = 1;
                $lang->save();
                $user = User::find($id);
                $user->lang = $request->lang;
                $user->save();
                return response()->json([
                    'data' => 'Language changed'
                ], 200);
            }
            return response()->json([
                'error' => 'lang is required, 0 for Arabic, 1 for English'
            ], 400);
        }
        return response()->json([
            'error' => 'User is UN AUTHORIZED to performe this action'
        ], 400);
    }
    
    public function editProfile(Request $request){
        if(auth()->user()->id == $request->id){
            
            //update user data
            $user = User::find($request->id)->update($request->all());
            
            //update image
            if($request->image != null){
                //Validate image
                $imageRules = array(
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                );
                $image_to_validate = array('image' => $request->image);
                $imageValidator = Validator::make($image_to_validate, $imageRules);
                if ($imageValidator->fails()) {
                    return $imageValidator->messages();
                }

                //Make image name unique
                $full_file_name = $request->image;
                $file_name = pathinfo($full_file_name, PATHINFO_FILENAME);
                $extension = $request->image->getClientOriginalExtension();
                $file_name_to_store = $file_name.'_'.time().'.'.$extension;
                
                //Upload image
                $path = $request->image->move(public_path('/'), $file_name_to_store);
                $url = url('/'.$file_name_to_store);

                $user = User::find($request->id);
                $user->image = $url;
                $user->save();
            }
            
            
            return response()->json([
                'data' => $user
            ], 200);
        }
        return response()->json([
            'error' => 'User is UN AUTHORIZED to performe this action'
        ], 400);
    }

}
