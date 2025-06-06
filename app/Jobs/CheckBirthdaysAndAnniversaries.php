<?php

namespace App\Jobs;

use App\Member;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckBirthdaysAndAnniversaries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        checkanniversary();
    }

    protected $smstype = "bc";
    protected $kudisms = [
        "api_key" => "c5NB6jS82kwTDm7gqfte1ZyYCiRoVlKWXExHJLnQId0MPbFv39auGhrzOpAs4U",
        "sender" => "RCCG Christ",
        "appnamecode" => "7712857319",
        "smstemplatecode" => " ",
        "whatsapptemplatecode" => "2147483647"
    ];


    public function checkanniversary(){
        $today = Carbon::today();
        $reminderStart = $today->copy()->subDays(7);

        $members = Member::whereNotNull('dob')
            ->orWhereNotNull('wedding_anniversary')
            ->get();

        foreach ($members as $member) {
            // Handle Birthday
            if ($member->dob) {
                $dob = Carbon::parse($member->dob)->setYear($today->year);
                Log::info('Scheduled job ran');

                if ($dob->between($reminderStart, $today)) {
                    // Send reminder to admin
                    Mail::raw("Reminder: {$member->title} {$member->firstname} {$member->lastname}'s birthday is on {$dob->toFormattedDateString()}", function ($message) {
                        $message->to('odejinmiabraham@gmail.com')->subject('Birthday Reminder');
                        $message->to('akinlabisamson15@gmail.com')->subject('Birthday Reminder');
                    }
                    );
                }

                $message = "Happy Birthday {$member->title} {$member->firstname}!";
                if ($dob->isToday()) {
                    if ($member->email != null) {
                        // Send birthday email to member
                        Mail::raw($message, function ($message) use ($member) {
                            $message->to($member->email)->subject('Happy Birthday!');
                        });
                    }
                    if ($this->smstype == "bc") {
                        Log::error('Something went wrong! 12  '.$message. "  ". $this->smstype);
                        $this->sendkudibroadcast($this->kudisms, $member->phone, $message);
                    }else{
                        Log::error('Something went wrong! 1234  '.$message. "  ". $this->smstype);
// 2. Extract value after the comma
                        $parts = explode(',', $message);
                        $lastPart = trim(end($parts)); // "12345"
                        $this->sendkudiwhatsapp($this->kudisms, $member->phone, $lastPart);
                        $this->sendkudiotp($this->kudisms, $member->phone, $lastPart);
                    }
                }
            }

            // Handle Wedding Anniversary
            if ($member->wedding_anniversary) {
                $anniversary = Carbon::parse($member->wedding_anniversary)->setYear($today->year);
                if ($anniversary->between($reminderStart, $today)) {
                    // Send reminder to admin
                    Mail::raw("Reminder: {$member->title} {$member->firstname} {$member->lastname}'s anniversary is on {$anniversary->toFormattedDateString()}", function ($message) {
                        $message->to('odejinmiabraham@gmail.com')->subject('Anniversary Reminder');
                        $message->to('akinlabisamson15@gmail.com')->subject('Anniversary Reminder');
                    });
                }

                if ($anniversary->isToday()) {
                    $message = "Happy Wedding Anniversary {$member->title} {$member->firstname}!";
                    if ($member->email != null) {
                        // Send anniversary email to member
                        Mail::raw($message, function ($message) use ($member) {
                            $message->to($member->email)->subject('Happy Anniversary!');
                        });
                    }
                    if ($member->phone != null) {
                        if ($this->smstype == "bc") {
                            Log::error('Something went wrong! 12  ' . $message . "  " . $this->smstype);
                            $this->sendkudibroadcast($this->kudisms, $member->phone, $message);
                        } else {
                            Log::error('Something went wrong! 1234  ' . $message . "  " . $this->smstype);
// 2. Extract value after the comma
                            $parts = explode(',', $message);
                            $lastPart = trim(end($parts)); // "12345"
                            $this->sendkudiwhatsapp($this->kudisms, $member->phone, $lastPart);
                            $this->sendkudiotp($this->kudisms, $member->phone, $lastPart);
                        }
                    }
                }
            }
        }

        $this->info('Reminder check completed.');
    }

    public function sendkudiwhatsapp($kudisms, $mobile,$message)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://my.kudisms.net/api/whatsapp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'token='.$kudisms->api_key.
                '&recipient='.$mobile.
                '&template_code='.$kudisms->whatsapptemplatecode.
                '&parameters='.$message.
                '&button_parameters=xxxx%2Cxxxx%2Cxxx
                &header_parameters=xxxx%2Cxxxx',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            dd("cURL Error #:" . $err);
            echo "cURL Error #:" . $err;
            return [];
        }
        $reply = json_decode($response,true);
//        dd($reply);
        return $reply;
    }
    public function sendkudiotp($kudisms, $mobile,$message)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://my.kudisms.net/api/otp?token=xxx&senderID=xxx&recipients=xxx&otp=xxx&appnamecode=xxx&templatecode=xxx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "token": "'. $kudisms->api_key.'",
                "senderID": "'.$kudisms->sender.'",
                "recipients": "'.$mobile.'",
                "otp": "'.$message.'",
                "appnamecode": "'.$kudisms->appnamecode.'",
                "templatecode": "'.$kudisms->smstemplatecode.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/plain'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
//            dd("cURL Error #:" . $err);
            echo "cURL Error #:" . $err;
            return [];
        }
        $reply = json_decode($response,true);
//        dd($reply);
        return $reply;
    }
    public function sendkudibroadcast($kudisms, $mobile,$message)
    {
        Log::info('Sending to KudiSMS API', [
            'url' => 'https://my.kudisms.net/api/corporate',
            'data' => [
                'token' => $kudisms->api_key,
                'senderID' => $kudisms->sender,
                'recipients' => $mobile,
                'message' => $message,
                'gateway' => '2',
            ]
        ]);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://my.kudisms.net/api/corporate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'token' => $kudisms->api_key,
                'senderID' => $kudisms->sender,
                'recipients' => $mobile,
                'message' => $message,
                'gateway' => '2'),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);


        curl_close($curl);

        if ($err) {
            Log::error('Something went wrong!  '.$err);
            echo "cURL Error #:" . $err;
            return [];
        }
        Log::error('Something went wrong! tolu '. $response);
        $reply = json_decode($response,true);

        Log::error('Something went wrong!  '.$reply);
        return $reply;
    }
}
