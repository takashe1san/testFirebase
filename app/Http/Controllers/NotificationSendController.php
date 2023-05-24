<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSendController extends Controller
{
    public function updateDeviceToken(Request $request)
    {
        Auth::user()->device_token =  $request->token;

        Auth::user()->save();

        return response()->json(['Token successfully stored.']);
    }

    public function sendNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();
            
        $serverKey = 'AAAAtmnRB4c:APA91bEy4NL0puR6opQqGHE03VocHjOF3TFHEf_0tYw-sITSyqgZUkkufbrOFAIeAOyR2vm-J8hH0q6POH4nmFhwUDP_v8NJ2r-0VgCxTQU7WYNPAAoxQbVVi5O_GTN3smXzG1efL0dY'; // ADD SERVER KEY HERE PROVIDED BY FCM
    
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);
    }

    public function bulksend(Request $req){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $dataArr = array(
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'id' => '1',
            'status'=>"done"
        );
        $notification = array(
            'title' =>'title', 
            'text' => 'body', 
            // 'image'=> 'img', 
            // 'sound' => 'default', 
            // 'badge' => '1',
        );
        $arrayToSend = array(
            // 'to' => "/topics/all",
            "registration_ids" => ["fyGkcAwYSqf9_Wk9CoHTYn:APA91bFvUNLwp-WMRkigFbVD136biO9q0G_RK1-yxTW9Yx4gaWq8WCyCltyHxl7jcq-Hor3Z8o7dIPbM0AsHrNRl4Mp2XhcBM8aP2977I8NSIONQWHukNhzS0XBTUS_MLILp51OrYQyb","fyGkcAwYSqf9_Wk9CoHTYn:APA91bFvUNLwp-WMRkigFbVD136biO9q0G_RK1-yxTW9Yx4gaWq8WCyCltyHxl7jcq-Hor3Z8o7dIPbM0AsHrNRl4Mp2XhcBM8aP2977I8NSIONQWHukNhzS0XBTUS_MLILp51OrYQyb"], 
            'notification' => $notification, 
            // 'data' => $dataArr, 
            // 'priority'=>'high'
        );
        $fields = json_encode ($arrayToSend);
        $headers = array (
            'Authorization:key=' . "AAAAtmnRB4c:APA91bEy4NL0puR6opQqGHE03VocHjOF3TFHEf_0tYw-sITSyqgZUkkufbrOFAIeAOyR2vm-J8hH0q6POH4nmFhwUDP_v8NJ2r-0VgCxTQU7WYNPAAoxQbVVi5O_GTN3smXzG1efL0dY",
            'Content-Type:application/json'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec ( $ch );
        var_dump($result);
        curl_close ( $ch );
        // return redirect()->back()->with('success', 'Notification Send successfully');
    }
}
