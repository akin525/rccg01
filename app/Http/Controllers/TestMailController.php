<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TestMailController
{
    function testmail()
    {

        try {
           $request['subject'] = 'Test Mail';
                Mail::to("akinlabisamson15@gmail.com")->send(new TestMail($request));
        } catch (\Exception $e) {
            Log::error('TestMail Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

    }

}