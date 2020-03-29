<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getNotifyList(){
        $notifications = AdminNotification::where('user_id', auth()->user()->id)->where('read', 0)->get();
        if(count($notifications) > 0){
            foreach($notifications as $not){
                $not->update([
                    'read' => 1
                ]);
            }
        }

        $list = auth()->user()->notifications;

        if(count($list) > 0){
            return response()->json([
                'data' => $list
            ], 200);
        }

        return response()->json([
            'data' => 'No notifications'
        ], 200);
    }

    public function count(){
        $notifications = AdminNotification::where('user_id', auth()->user()->id)->where('read', 0)->get();
        
        if(count($notifications) > 0){
            return response()->json([
                'data' => count($notifications)
            ], 200);
        }

        return response()->json([
            'data' => 0
        ], 200);
    }
}
