<?php


namespace App\Http\Traits;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Infobip\Api\SendSmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Throwable;

trait BlockTrait{


    public function sendSMS(Request $request, $userRole)
    {
        $BASE_URL = "https://4m2wqm.api.infobip.com";
        $API_KEY = "7ea4f3d999a48d2e2a3299e94807d5e2-7800ec11-0f8e-4e17-9997-93228a9ddcba";

        $SENDER = "InfoSMS";
        $RECIPIENT = $request->phone_no;
        $MESSAGE_TEXT = "Registeration is Confirmed as $userRole";

        $configuration = (new Configuration())
            ->setHost($BASE_URL)
            ->setApiKeyPrefix('Authorization', 'App')
            ->setApiKey('Authorization', $API_KEY);

        $client = new Client();

        $sendSmsApi = new SendSMSApi($client, $configuration);
        $destination = (new SmsDestination())->setTo($RECIPIENT);
        $message = (new SmsTextualMessage())
            ->setFrom($SENDER)
            ->setText($MESSAGE_TEXT)
            ->setDestinations([$destination]);

        $request = (new SmsAdvancedTextualRequest())->setMessages([$message]);

        try {
            $smsResponse = $sendSmsApi->sendSmsMessage($request);
            echo ("Response body: " . $smsResponse);
        } catch (Throwable $apiException) {
            echo("HTTP Code: " . $apiException->getCode() . "\n");
            echo ("Response body: " . $apiException->getResponseBody() . "\n");
        }
    }

}
