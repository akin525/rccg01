<?php

namespace App\Http\Controllers;

use App\Group;
use App\Member;
use App\messaging;
use App\GroupMember;
use App\Mail\MailMember;
use App\Mail\TickectEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MessagingController extends Controller
{
  public function indexEmail()
  {
    $user = Auth::user();
    // $groups = \App\Group::where('branch_id', $user->id)->get();
    $groups = Group::all();
    $members = \App\Member::where('branch_id', $user->id)->get(); //$user->isAdmin() ? \App\Member::all() :
    // $group = collect(new \App\Group);
    // $group->name = 'First Timers Group';
    // $group->id = 1000;
    $default_groups = [];
    // array_push($default_groups, $group);
    return view('messaging.email', compact('members', 'groups', 'default_groups'));
  }

  public function indexSMS()
  {
    $user = Auth::user();
    $groups = Group::all();
    foreach ($groups as $group){
      // dd($group->id);
    }
    
    // $groups = \App\Group::where('branch_id', $user->id)->get();
    $members = \App\Member::where('branch_id', $user->id)->get(); //$user->isAdmin() ? \App\Member::all() :
    // $group = collect(new \App\Group);
    // $group->name = 'First Timers Group';
    // $group->id = 1000;
    $default_groups = [];
    // get the sms api
    // $smsapi = \App\Options::getOneBranchOption('smsapi', $user);
    // array_push($default_groups, $group);
    return view('messaging.sms', compact('members', 'group', 'default_groups'));
  }

  public function sendEmail(Request $request)
  {
    $user = Auth::user();

    // return new \App\Mail\MailToMember($request, \Auth::user());
    foreach ($request->to as $to) {
      Mail::to($to) //$request->to)
        //->cc($request->cc)
        //->bcc($request->bcc)
        ->send(new \App\Mail\MailToMember($request, $user));
      // ->send(new MailMember($request));
    }
    return redirect()->back()->with('status', 'Mail Successfully Sent!');
  }

  public function sendSMS(Request $request)
  {
    $user = Auth::user();
    //print_r($_POST);exit();
    $result = array('pass' => array('status' => [], 'count' => 0), 'fail' => array('status' => [], 'count' => 0, 'numbers' => []), 'total' => sizeof($request->to));
    $sender = config('app.name');
    $smsapi = \App\Options::getOneBranchOption('smsapi', $user);
    // dd($smsapi);


    if ($smsapi) {
      foreach ($request->to as $to) {
        $message = urlencode($request->message);
        try {
          $response = file_get_contents($smsapi->value . "&recipient={$to}&message={$message}");
        } catch (\Exception $e) {
          return response()->json(['status' => false, 'text' => 'Sms Api Not Valid. Please set a valid api or contact for assistance']);
          break;
        }

        if (substr($response, 0, 2) == "OK") {
          array_push($result['fail']['status'], $response);
          $result['pass']['count']++;
        } else {
          array_push($result['fail']['status'], $response);
          array_push($result['fail']['numbers'], $to);
          $result['fail']['count']++;
        }
        //$response = $this->curl_get("http://api.smartsmssolutions.com/smsapi.php?username=iamblizzyy@gmail.com&password=revelation1&sender=ASAP&recipient={$to}&message={$message}",[],[]);
        // $response = file_get_contents("http://api.smartsmssolutions.com/smsapi.php?username=iamblizzyy@gmail.com&password=revelation1&sender={$sender}&recipient={$to}&message={$message}");
      }
      // if (substr($response,0,2) == "OK")
      // {
      //     return redirect()->back()->with('status','Message Successfullt Sent!');
      // }
      // else {
      //
      //     return redirect()->back()->with('status','FAILURE!! Could not Send Message.'.$response);
      // }
      return response()->json(['status' => true, 'text' => $result]);
    } else {
      return response()->json(['status' => false, 'text' => 'Sms Api Not Set From Admin Options']);
    }
  }

  public function inbox()
  {
    $users = Auth::user();
    $user = Auth::user();
    $members = \App\User::where('id', '!=', $users->id)->get();
    $msg_user = \App\User::selectRaw('SUM(case when messaging.seen = 0 then 1 else 0 end) as count, users.branchname, users.id')->leftjoin('messaging', 'messaging.msg_from', '=', 'users.id')->where('messaging.msg_to', '>', '0')->where('messaging.msg_to', '=', $users->id)->where('messaging.msg_from', '!=', $users->id)->groupby('users.branchname', 'users.id')->get();

    return view('messaging.inbox', compact('members', 'users', 'msg_user'));
  }

  public function sendMessage(Request $request)
  {

    $curl = curl_init();
    // Get values from the request
    $recipients = $request->to;
    $message = $request->message;

    //dd($recipients);
    // Assuming the country code for Nigeria is "+234"
    $recipients = '+234' . ltrim($recipients, '0');

    // Define the message parameters
    $messageParams = [
      "to" => $recipients,
      "from" => "N-Alert",
      "sms" => $message,
      "type" => "plain",
      "channel" => "generic",
      "api_key" => "TLxl3IyjUzfIrbB4TU4Drat5AfCWI7D3gZiyJvdVA1qbl20bNIAg1esx5IgitK",
    ];

    // Convert message parameters to JSON
    $jsonMessageParams = json_encode($messageParams);

    // Set cURL options
    curl_setopt_array($curl, [
      CURLOPT_URL => 'https://api.ng.termii.com/api/sms/send',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $jsonMessageParams,
      CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'api_key: TLxl3IyjUzfIrbB4TU4Drat5AfCWI7D3gZiyJvdVA1qbl20bNIAg1esx5IgitK',
      ],
    ]);

    // Execute cURL request
   
    // Close cURL session
    curl_close($curl);

    // Output the response
   // echo $response;
    messaging::create([
      'msg_to' => $recipients,
      'msg_from' => 'N-Alert',
      'msg' => $message,
      'subject' => $request->subject
    ]);

    // $to = $recipients;
    // $from = "N-Alert";
    // $message = $request->message;

    // foreach($to as $branch){
    //   $sql = "INSERT INTO messaging(msg_to,msg_from,msg) VALUES('$recipients', 'N-Alert','$message')";
    //   DB::insert($sql);
    // }
     return redirect()->back()->with('status', 'Message Sent Successfully');
  }

  // public function sendGroupMessage(Request $request)
  // {
  //   $curl = curl_init();
  //   // Get values from the request

  //   $message = $request->message;
  //   // Get the selected group IDs from the form
  //   $selectedGroupIds = $request->input('to');
  //  // dd($selectedGroupIds);
  //   // Retrieve members for the selected groups from the group_members table
  //   $groupMembers = GroupMember::whereIn('group_id', $selectedGroupIds)->get();

  //   // Extract unique member IDs
  //   $memberIds = $groupMembers->pluck('member_id')->unique()->toArray();

  //   // Retrieve phone numbers of members from the members table
  //   $members = Member::whereIn('id', $memberIds)->get();
  //  // dd($members);
  //   foreach ($members as $member) {
  //     $recipients = $member->phone;

  //    // Assuming the country code for Nigeria is "+234"
  //    $recipients = '+234' . ltrim($recipients, '0');
  //     // dd($recipients);
  //     // Define the message parameters
  //     $messageParams = [
  //       "to" => $recipients,
  //       "from" => "N-Alert",
  //       "sms" => $message,
  //       "type" => "plain",
  //       "channel" => "generic",
  //       "api_key" => "TLxl3IyjUzfIrbB4TU4Drat5AfCWI7D3gZiyJvdVA1qbl20bNIAg1esx5IgitK",
  //     ];

  //     // Convert message parameters to JSON
  //     $jsonMessageParams = json_encode($messageParams);

  //     // Set cURL options
  //     curl_setopt_array($curl, [
  //       CURLOPT_URL => 'https://api.ng.termii.com/api/sms/send',
  //       CURLOPT_RETURNTRANSFER => true,
  //       CURLOPT_ENCODING => '',
  //       CURLOPT_MAXREDIRS => 10,
  //       CURLOPT_TIMEOUT => 0,
  //       CURLOPT_FOLLOWLOCATION => true,
  //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  //       CURLOPT_CUSTOMREQUEST => 'POST',
  //       CURLOPT_POSTFIELDS => $jsonMessageParams,
  //       CURLOPT_HTTPHEADER => [
  //         'Content-Type: application/json',
  //         'api_key: TLxl3IyjUzfIrbB4TU4Drat5AfCWI7D3gZiyJvdVA1qbl20bNIAg1esx5IgitK',
  //       ],
  //     ]);

  //     // Execute cURL request
  //     $response = curl_exec($curl);

  //     // Close cURL session
  //     curl_close($curl);

  //     // Output the response
  //     //echo $response;
  //     messaging::create([
  //       'msg_to' => $recipients,
  //       'msg_from' => 'N-Alert',
  //       'msg' => $message,
  //       'subject' => $request->subject
  //     ]);
  //      return redirect()->back()->with('status', 'Message Sent Successfully');
  //   }
  // }
  public function sendGroupMessage(Request $request)
  {
      $curl = curl_init();
      // Get values from the request
      $message = $request->message;
      $selectedGroupIds = $request->input('group');
      $selectedMemberPhones = $request->input('to');
  
      // Validate or sanitize input as needed
  
      // Check if groups are selected
      if (!empty($selectedGroupIds)) {
        
          // Retrieve members for the selected groups from the group_members table
          $groupMembers = GroupMember::whereIn('group_id', $selectedGroupIds)->get();
         
          // Extract unique member IDs
          $memberIds = $groupMembers->pluck('member_id')->unique()->toArray();
         
          // Retrieve phone numbers of members from the members table
          $groupMembersPhones = Member::whereIn('id', $memberIds)->pluck('phone')->toArray();
          
          // Merge group members' phones with individually selected members' phones
          $recipients = array_unique(array_merge($selectedMemberPhones, $groupMembersPhones));
         
      } else {
          // If no groups are selected, use only individually selected members
          $recipients = array_unique($selectedMemberPhones);
      }
     
      // Assuming the country code for Nigeria is "+234"
      $recipients = array_map(function ($number) {
          return '+234' . ltrim($number, '0');
      }, $recipients);
      
      // Define the message parameters
      $messageParams = [
          "to" => $recipients,
          "from" => "N-Alert",
          "sms" => $message,
          "type" => "plain",
          "channel" => "dnd",
          "api_key" => "TLxl3IyjUzfIrbB4TU4Drat5AfCWI7D3gZiyJvdVA1qbl20bNIAg1esx5IgitK",
      ];
  
      // Convert message parameters to JSON
      $jsonMessageParams = json_encode($messageParams);
  
      // Set cURL options
      curl_setopt_array($curl, [
          CURLOPT_URL => 'https://api.ng.termii.com/api/sms/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $jsonMessageParams,
          CURLOPT_HTTPHEADER => [
              'Content-Type: application/json',
              'api_key: TLxl3IyjUzfIrbB4TU4Drat5AfCWI7D3gZiyJvdVA1qbl20bNIAg1esx5IgitK',
          ],
      ]);
  
      // Execute cURL request
      $response = curl_exec($curl);
  
      // Check for cURL errors
      if ($response === false) {
          $error = curl_error($curl);
          // Log or handle the error as needed
          dd("cURL Error: " . $error);
      }
     
      // Close cURL session
      curl_close($curl);
  
      // Output the response
      // echo $response;
  
      // Log the message in your database
      foreach ($recipients as $recipient) {
          messaging::create([
              'msg_to' => $recipient,
              'msg_from' => 'N-Alert',
              'msg' => $message,
              'subject' => $request->subject
          ]);
      }
  
      return redirect()->back()->with('status', 'Message Sent Successfully');
  }
  

public function whatsappsend(Request $request)
{
  $curl = curl_init();
  // Get values from the request
  $message = $request->message;
  $selectedGroupIds = $request->input('group');
  $selectedMemberPhones = $request->input('to');

  // Validate or sanitize input as needed

  // Check if groups are selected
  if (!empty($selectedGroupIds)) {
    
      // Retrieve members for the selected groups from the group_members table
      $groupMembers = GroupMember::whereIn('group_id', $selectedGroupIds)->get();
     
      // Extract unique member IDs
      $memberIds = $groupMembers->pluck('member_id')->unique()->toArray();
     
      // Retrieve phone numbers of members from the members table
      $groupMembersPhones = Member::whereIn('id', $memberIds)->pluck('phone')->toArray();
      
      // Merge group members' phones with individually selected members' phones
      $recipients = array_unique(array_merge($selectedMemberPhones, $groupMembersPhones));
     
  } else {
      // If no groups are selected, use only individually selected members
      $recipients = array_unique($selectedMemberPhones);
  }
 
  // Assuming the country code for Nigeria is "+234"
  $recipients = array_map(function ($number) {
      return '+234' . ltrim($number, '0');
  }, $recipients);
  
  // Define the message parameters
  $messageParams = [
      "to" => $recipients,
      "from" => "N-Alert",
      "sms" => $message,
      "type" => "plain",
      "channel" => "dnd",
      "api_key" => "TLxl3IyjUzfIrbB4TU4Drat5AfCWI7D3gZiyJvdVA1qbl20bNIAg1esx5IgitK",
  ];

  // Convert message parameters to JSON
  $jsonMessageParams = json_encode($messageParams);

  // Set cURL options
  curl_setopt_array($curl, [
      CURLOPT_URL => 'https://api.ng.termii.com/api/sms/send',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $jsonMessageParams,
      CURLOPT_HTTPHEADER => [
          'Content-Type: application/json',
          'api_key: TLxl3IyjUzfIrbB4TU4Drat5AfCWI7D3gZiyJvdVA1qbl20bNIAg1esx5IgitK',
      ],
  ]);

  // Execute cURL request
  $response = curl_exec($curl);

  // Check for cURL errors
  if ($response === false) {
      $error = curl_error($curl);
      // Log or handle the error as needed
      dd("cURL Error: " . $error);
  }
 
  // Close cURL session
  curl_close($curl);

  // Output the response
  // echo $response;

  // Log the message in your database
  foreach ($recipients as $recipient) {
      messaging::create([
          'msg_to' => $recipient,
          'msg_from' => 'N-Alert',
          'msg' => $message,
          'subject' => $request->subject
      ]);
  }

  return redirect()->back()->with('status', 'Message Sent Successfully');
}

public function whatsapp(Request $request)
{
  $user = Auth::user();
    $groups = Group::all();
    foreach ($groups as $group){
      // dd($group->id);
    }
    $members = \App\Member::where('branch_id', $user->id)->get(); //$user->isAdmin() ? \App\Member::all() :
    $default_groups = [];
  return view('messaging.whatsapp', compact('members', 'group', 'default_groups'));
}

public function indexaimessage()
{
  return view('messaging.aimessage');
}

  public function aimessage(Request $request)
  {
    $curl = curl_init();

    $message = [
      "message" => $request->message
    ];

    $encodemessage = json_encode($message);
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://resources.5starcompany.com.ng/api/aibot',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$encodemessage,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
   // echo $response;

    // Decode the JSON response
    $responseData = json_decode($response, true);

    $user = Auth::user();
    $groups = Group::all();
    foreach ($groups as $group){
      // dd($group->id);
    }
    
    $members = \App\Member::where('branch_id', $user->id)->get(); 
    $default_groups = [];

    // Pass the response data to the view
    return view('messaging.sms',compact('responseData','members','default_groups','group'));
    
  }

  public function getMsg(Request $request)
  {
    $from = $request->from;
    $to = $request->to;

    $chat = \App\User::selectRaw('messaging.*, users.branchname')->leftjoin('messaging', 'messaging.msg_from', '=', 'users.id')->where('messaging.msg_to', '=', $from)->where('messaging.msg_from', '=', $to)->orWhere('messaging.msg_from', '=', $from)->where('messaging.msg_to', '=', $to)->groupby('users.branchname', 'users.id', 'messaging.id', 'messaging.updated_at', 'messaging.created_at', 'messaging.subject', 'messaging.msg_to', 'messaging.msg_from', 'messaging.msg', 'messaging.date', 'messaging.seen')->orderby('messaging.date')->get();


    return response()->json(['success' => true, 'chats' => $chat]);
  }

  public function reply(Request $request)
  {
    $to = $request->to;
    $from = $request->from;
    $message = $request->message;
    $sql = "INSERT INTO messaging(msg_to,msg_from,msg) VALUES('$to', '$from','$message')";
    DB::insert($sql);
    return response()->json(['success' => true]);
  }

  public function get_inbox()
  {
    $users = Auth::user();
    //$members = \App\User::where('id', '!=', $users->id)->get();

    $msg_user = \App\User::selectRaw('count(messaging.id) as count, users.branchname, users.id')->leftjoin('messaging', 'messaging.msg_from', '=', 'users.id')->where('messaging.id', '>', '0')->where('messaging.msg_to', '=', $users->id)->where('messaging.msg_from', '!=', $users->id)->groupby('users.branchname', 'users.id')->get();

    return response()->json(['success' => true, 'chats' => $msg_user]);
    //view('messaging.inbox', compact('members', 'users', 'msg_user'));
  }

  public function get_users()
  {
    $user = Auth::user();
    $branches = \App\User::select('branchname', 'id')->where('id', '!=', $user->id)->get();
    return response()->json(['success' => true, 'chats' => $branches]);
  }

  public function indexticket()
  {
    return view('ticketing.ticket');
  }

  //kenny
  public function sendTickeeeet(Request $request)
  {
    // $this->validate($request, [ 'name' => 'required', 'email' => 'required|email', 'message' => 'required' ]);
    // ContactUS::create($request->all());
    $randid = mt_rand(13, rand(100, 99999990));
    $error_code = $request->error_code;
    $error_name = $request->error_name;
    $severity = $request->severity;
    $servicelevel = $request->servicelevel;
    $time = $request->time;
    $date = $request->date;
    $full_name = $request->full_name;
    $phone_number = $request->phone_number;
    $email = $request->email;
    $msg = $request->message;
    Mail::send(
      'email',
      array('TicketID' => $randid, 'ErrorCode' => $error_code, 'ErrorName' => $error_name, 'Severity' => $severity, 'ServiceLevel' => $servicelevel, 'Time' => $time, 'Date' => $date, 'Name' => $full_name, 'PhoneNumber' => $phone_number, 'Email' => $email, 'kmessage' => $msg),
      function ($message) use ($request) {
        $message->from($request->email, $request->full_name);
        $message->to('myckhel1@hotmail.com', 'Hoffenheim Technologies Test Single Email')->subject('From Support Ticket');
      }
    );

    return redirect()->back()->with('status', "Ticket Email Sent. Check your inbox.");
  }


  public function sendTicket(Request $request)
  {
    $this->validate($request, ['error_code' => 'required', 'error_name' => 'required', 'severity' => 'required', 'servicelevel' => 'required', 'time' => 'required', 'date' => 'required', 'full_name' => 'required', 'phone_number' => 'required', 'email' => 'required|email', 'message' => 'required']);
    //  foreach ($request->to as $to){
    Mail::to('ticket@cms.hoffenheimtechnologies.tech') //$request->to)
      //->cc($request->cc)
      //->bcc($request->bcc)
      ->send(new TickectEmail($request));
    //  }
    $message = 'Ticket  #' . $request->TicketID  . '   successfully Created!                                                                          Our Technical Support Team Will Get Back To You Soon Thank You. Please Refer To The Above Ticket  #   For Your Future Reference.';

    return redirect()
      ->back()
      ->with('status', $message);
  }

  public function demo(Request $request)
  {
    $to = $request->to;
    $randid = mt_rand(13, rand(100, 99999990));
    $error_code = $request->error_code;
    $error_name = $request->error_name;
    $severity = $request->severity;
    $servicelevel = $request->servicelevels;
    $time = $request->time;
    $date = $request->date;
    $full_name = $request->full_name;
    $phone_number = $request->phone_number;
    $email = $request->email;
    $message = $request->message;
    Mail::to($email) //$request->to)
      //->cc($request->cc)
      //->bcc($request->bcc)
      ->send(new TickectEmail($request));

    return redirect()
      ->back()
      ->with('status', 'Mail Successfully Sent!');
    /*$objDemo = new \stdClass();
       $objDemo->demo_one = 'Demo One Value';
       $objDemo->demo_two = 'Demo Two Value';
       $objDemo->sender = 'myckhel1@gmail.com';
       $objDemo->receiver = 'ReceiverUserName';
       echo view('mails.ticket');

       Mail::to("kakpan@hoffenheimtechnologies.com")->send(new TickectEmail($objDemo));*/
  }
}
