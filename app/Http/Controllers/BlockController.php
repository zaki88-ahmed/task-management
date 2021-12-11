<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiDesignTrait;
use App\Http\Traits\UploadImageTrait;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Infobip\Api\SendSmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Throwable;

class BlockController extends Controller
{
    //
    use ApiDesignTrait;
    use UploadImageTrait;

    public function sendSMS(){


//        $client = new Client(['base_url' => 'http://127.0.0.1:8002']);
//        $response = $client->request('get', 'api/customers');
//        return $response->getBody();

        //without token & Un Authenticated User
//        $client = new Client();
//        $response = $client->request('get', 'http://127.0.0.1:8002/api/customers');
//        return $response->getBody();


        //with token & Authenticated User
        $client = new Client();
        $response = $client->request('GET', 'http://127.0.0.1:8002/api/customers', [ 'headers' => [ 'Authorization' => 'Bearer 23|zgh0RDqJVA5FKwOs6bsuZ04u3nABIWsBCOiP1HR1' ] ]);
//        return $response->getBody();
        $data = json_decode($response->getBody());
        return $this->ApiResponse(200, 'ALL Data', null, $data);
//
    }


    public function addUser(Request $request){
        $client = new Client();
        $response = $client->request('post', 'http://127.0.0.1:8002/api/customers/register', [
            'form_params' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_no' => $request->phone_no,
                'user_type' => $request->user_type_id,
            ]
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_no' => $request->phone_no,
            'user_type' => $request->user_type_id,
        ]);
        $contents = (string)$response->getBody();
        return $this->ApiResponse(200, 'ALL Data', null, $contents);
    }



    public function addImage(Request $request){
//        dd('zaki');
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:doc,docx,pdf,txt,csv,png,jpg,jpeg|max:2048',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()]);
        }

        $file = $request->file;
//        dd($file);
//        return $this->uploadImage($request);
        return $this->ApiResponse(200, 'FileUploaded Successfully', null, $this->uploadImage($request));
    }
}
