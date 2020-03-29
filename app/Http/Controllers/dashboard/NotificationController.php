<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Token;
use App\Models\Notification;
use App\User;

class NotificationController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('web');
    }

    public function index(){
        $all_data = Notification::orderBy('created_at', 'desc')->get(['id', 'msg_ar', 'created_at']);
        $notifications = $all_data->unique('msg_ar'); 
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create(){
        return view('admin.notifications.create');
    }

    public function store(Request $request){

        $this->validate($request, [
            'msg_ar' => 'required',
        ]);
        
        $users = User::where('is_admin', 0)->get('id');

        if(count($users) > 0){
            foreach($users as $user){
                $notifications = Notification::create([
                    'msg_ar' => $request->msg_ar,
                    'user_id' => $user->id,
                    'read' => 0
                ]);
            }
        }

        $notification = Token::pluck('token');

        // foreach ($notification as $key => $value) {
        //     $this->notification($value->token, $request);
        // }

        $this->notification($notification, $request);
        
        // $this->notification('eCygqTmZaTc:APA91bH7s310xfru_LvCj_J2bHyXHNbg5z2mvzJIDYxApm06sc-wD98P18jiFIiCeoVEdHMJp0XYR2-cZwT9MqEhABa2vCwILcrO_YrSbHiRpLxD0u5uvNUREiZZYpac8GYuvdzbUXEw', $request);
        

        session()->flash('message', trans('admin.notification_created'));
        return redirect()->route('notifications.index');
    }

    public function notification($tokenList, $request){

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        // $token=$token;
        
        $notification = [
            'body' => $request->msg_ar,
            'sound' => true,
        ];

        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

        $fcmNotification = [
            'registration_ids' => $tokenList, //multple token array
            // 'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=AAAA366N0Ug:APA91bFaF1RHMkEwF9ATUovRtuhMo7Psi_nhFHVqt0IQ3BNqLx3wDuecL9OMztor2QKlJpTpZyOq5VhavCLaiTC8QgCDYpRtCvceOrBpoD8ZSjtqsXo2_sHaVETRjdXKit1UqC1O4a3h',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

    }

    public function delete(Request $request){
        if($request->ajax()){
            $nots = Notification::where('msg_ar', Notification::find($request->id)->msg_ar)->get();
            if(count($nots) > 0){
                foreach($nots as $not){
                    $not->delete();
                }
            }
            // session()->flash('message', trans('condition_deleted'));
            return response()->json([
                'data' => 1,
            ], 200);
        }
    }
}
