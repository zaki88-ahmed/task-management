<?php


namespace App\Http\Traits;

use App\Models\Notification;
use App\Models\User;

trait NotificationTrait{

    public function createNotification($destinations, $msg){
        foreach ($destinations as $destination){
            $notification = Notification::create([
                'destination' => $destination,
                'text' => $msg,
                'type' => 'notification',
            ]);

        }
    }
//
//
//
//    public function sendFriendRequest($destination, $msg){
//        $notification = Notification::create([
//            'destination' => $destination,
//            'text' => $msg,
//            'type' => 'friend_request',
//        ]);
//    }


    public function sendNotification($users, $title, $body){
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = config('app.SERVER_API_KEY');
//        dd($SERVER_API_KEY);

        $data = [
            "registration_ids" => explode( ',', $users->device_token),
            "notification" => [
                "title" => $title,
                "body" => $body,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
//        dd($firebaseToken);

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        $userIds = User::whereNotNull('device_token')->pluck('id')->all();
        if($users->device_token){
            $this->createNotification(explode( ',', $users->id), $title);
        }
        return ($response);
    }


}
