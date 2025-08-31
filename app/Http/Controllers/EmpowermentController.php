<?php

namespace App\Http\Controllers;

use App\Empowerment;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpowermentController extends Controller
{
    // ...

    public function create()
    {
        $courseslist = [
            [
                'branch' => '1',
                'courses' => [
                    'Photography',
                    'Sound system operation',
                    'baking',
                    'fashion design',
                    'content creation',
                    'car ac repair'
                ]
            ],
            [
                'branch' => '4',
                'courses' => [
                    'Tailoring',
                    'Beads making',
                    'phone repair'
                ]
            ]
        ];
        $branches = User::select('branchname','id')->distinct()->get();
        return view('empowerment.register', compact('branches','courseslist'));
    }

    public function create1(Request $request)
    {
        $referrerId = null;
        $branchname = null;

        if ($request->has('ref')) {
            try {
                $referrerId = decrypt($request->query('ref'));
                $branchname = User::where('branchcode', $referrerId)->get(['branchname'])->first()["branchname"];
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                // Handle invalid/modified referral codes
            }
        }
        $courseslist = [
            [
                'branch' => '1',
                'courses' => [
                    'Photography',
                    'Sound system operation',
                    'baking',
                    'fashion design',
                    'content creation',
                    'car ac repair'
                ]
            ],
            [
                'branch' => '4',
                'courses' => [
                    'Tailoring',
                    'Beads making',
                    'phone repair'
                ]
            ]
        ];
        $branches = User::select('branchname')->distinct()->get();
        return view('empowerment.register', compact('referrerId','branchname','branches','courseslist'));//, compact('classes', 'sections'));
    }

    // In CourseController.php
    public function getCourses(Request $request)
    {
        dd($request->input('branchId')); // Dump the branchId value
        $branchId = $request->input('branchId');
        dd($branchId); // Dump the branchId value again
        $branchName = $branches[$branchId - 1]['branchname'];
        dd($branchName); // Dump the branchName value
        $courses = $courseslist[$branchName]['courses'];
        dd($courses); // Dump the courses value
        return response()->json($courses);
    }
    public function store(Request $request)
    {
        // Validate the form data
        $this->validate($request, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'title' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string',
            'age' => 'required|integer',
            'religion' => 'required|string',
            'address' => 'required|string',
            'employment_status' => 'required|string',
            'course' => 'required|string',
            'sex' => 'required|string',
            'marital_status' => 'required|string',
            'intro' => 'required|string',
        ]);

        $user = Auth::user();

        if ($user == null){
            $branchname = User::where('id', $request->get('referralId'))->first();
            $branch_id = $branchname["id"];
        }else{
            $branch_id = $user->id;
        }

        // Send the email
        $email_body = 'Title: ' . $request->input('title') . "\n";
        $email_body .= 'Name: ' . $request->input('firstname') . "\n" . $request->input('lastname') . "\n";
        $email_body .= 'Address: ' . $request->input('address') . "\n";
        $email_body .= 'Gender: ' . $request->input('sex') . "\n";
        $email_body .= 'Age: ' . $request->input('age') . "\n";
        $email_body .= 'Religion: ' . $request->input('religion') . "\n";
        $email_body .= 'Marital Status: ' . $request->input('marital_status') . "\n";
        $email_body .= 'Course: ' . $request->input('course') . "\n";
        $email_body .= 'Intro: ' . $request->input('intro') . "\n";
        $email_body .= 'Employment Status: ' . $request->input('employment-status') . "\n";

        $headers = 'From: Application Form <application_form@example.com>' . "\r\n";
        $headers .= 'Reply-To: ' . $request->input('email') . "\r\n";

//        mail($request->get('email'), 'Application Form Submission', $email_body, $headers);

        $member = new Empowerment(array(
            'branch_id' => $branch_id,
            'id' => $request->get('id'),
            'title' => $request->get('title'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'age' => $request->get('age'),
            'religion' => $request->get('religion'),
            'intro' => $request->get('intro'),
            'employment_status' => $request->get('employment_status'),
            'course' => $request->get('course'),
            'address' => $request->get('address'),
            'sex' => $request->get('sex'),
            'marital_status' => $request->get('marital_status'),
        ));
        $member->save();
        // return response()->json(['status' => true, 'text' => "Member Successfully registered"]);
        if ($user == null) {
            $referralCode = encrypt($branchname["branchcode"]);
            return redirect()->route('empowerment.registration.form', ['ref' => $referralCode])->with('status', 'Empowerment Member Successfully registered');
        }else{
            return redirect()->route('empowerment.register.form')->with('status', 'Empowerment Member Successfully registered');
        }
    }

    // ...
}
