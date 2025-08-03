<?php

namespace App\Http\Controllers;

use App\Member;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = $request->input('month', date('m'));
        // Get the current month
//        $currentMonth = date('m');

        // Fetch members with the same birth month
        $members = Member::whereMonth('dob', $currentMonth)->get();

        return view('birthday', compact('members'));
    }
}
