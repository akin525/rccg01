<?php

namespace App\Http\Controllers;

use App\Member;
use App\User;
use Carbon\Carbon;
use DataTables;
use Auth as auths;

use App\Department;
use App\CollectionsType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables as datatable;

class MemberController extends Controller
{
    private $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $user = Auth::user();
      if ($request->draw) {
        return datatable::of($user->members)->make(true);
      } else {
        $member = Member::all();
        return view('members.all', compact('member'));//, compact('members'));
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
      $depts = Department::all();
      return view('members.register', compact('depts'));//, compact('classes', 'sections'));
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

        return view('members.register', compact('referrerId','branchname'));//, compact('classes', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
      // validate email
//        $memberByEmail = Member::where('email', $request->email)->first();
//        if ($memberByEmail) {
//            return $this->errorResponse("The email ({$request->email}) already exists for a member.", $request);
//        }

//        $memberByPhone = Member::where('phone', $request->phone)->first();
//        if ($memberByPhone) {
//            return $this->errorResponse("The phone ({$request->phone}) already exists for a member.", $request);
//        }


        $user = Auth::user();

        $relatives = null;

        // filter $_POST data for keys starting with 'relative'
        // if there's any, then one or more relatives have been assigned
        $array_of_relations_id = array_filter($_POST,
                                function ($key) {
                                    return substr($key,0,8) == 'relative' ? true : false;
                                },
                                ARRAY_FILTER_USE_KEY
                                );
        if (count($array_of_relations_id) > 0) {
            $relatives = [];
            foreach ($array_of_relations_id as $relative_id){

                $relationship = $_POST["relationship_{$relative_id}"];
                array_push($relatives, ['id'=>$relative_id, 'relationship'=>$relationship]);

            }
            $relatives = json_encode($relatives);
        }

        $this->validate($request, [
            'firstname' => 'bail|required|string|max:255',
            'lastname' => 'required|string|max:255',
            'profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'dob' => 'required|string|max:255',
            'email' => 'required|string|max:255',
        ]);

        if ($user == null){
            $branchname = User::where('branchcode', $request->get('referralId'))->first();
            $branch_id = $branchname["id"];
        }else{
            $branch_id = $user->id;
        }
          // default profile image
        if ($request->hasFile('myprofile'))
        {
          // dd("yes");
            $image = $request->file('myprofile');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $image_name = $filename;
            // dd($filename);
        }else{
          $image_name = "profile.png";
        }
        $birthdate = date('Y-m-d',strtotime($request->get('dob')));
        $dob = Carbon::parse($birthdate);
        $age = $dob->age;

        $status = $age < 12 ? 'Child' : 'Adult';
        $member = new Member(array(
            'branch_id' => $branch_id,
            'id' => $request->get('id'),
            'title' => $request->get('title'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'email' => $request->get('email'),
            'dob' => $birthdate,
            'category' => $status,
            'phone' => $request->get('phone'),
//            'talent' => $request->get('talent'),
            'interest' => $request->get('interest'),
//            'formal_worship' => $request->get('formal_worship'),
//            'another_member' => $request->get('another_member'),
            'occupation' => $request->get('occupation'),
//            'position' => $request->get('position'),
            'address' => $request->get('address'),
//            'address2' => $request->get('address2'),
            'postal' => $request->get('postal'),
            'city' => $request->get('city'),
            'state' => $request->get('state'),
            'country' => $request->get('country'),
            'sex' => $request->get('sex'),
            'marital_status' => $request->get('marital_status'),
//            'member_since' => date('Y-m-d',strtotime($request->get('member_since'))),
//            'wedding_anniversary' => date('Y-m-d',strtotime($request->get('wedding_anniversary'))),
            'photo' => $image_name,
//            'relative' => $relatives,
//            'member_status' => $request->member_status
        ));
        if ($request->get('wedding_anniversary') != null){
            $member->wedding_anniversary = date('Y-m-d',strtotime($request->get('wedding_anniversary')));
        }
        $member->save();
        // return response()->json(['status' => true, 'text' => "Member Successfully registered"]);
        if ($user == null) {
            $referralCode = encrypt($branchname["branchcode"]);
            return redirect()->route('member.registration.form', ['ref' => $referralCode])->with('status', 'Member Successfully registered');
        }else{
            return redirect()->route('member.register.form')->with('status', 'Member Successfully registered');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categorymember(Request $request)
    {
      // validate email
        $members = Member::all();
        foreach ($members as $member){
            $dob = Carbon::parse($member->dob);
            $age = $dob->age;
            $status = $age < 18 ? 'Child' : 'Adult';
            $member->category = $status;
            $member->save();
        }
    }

    /**
     * Return error response for both API and web form.
     */
    private function errorResponse($message, Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['status' => false, 'text' => $message], 422);
        }

        return redirect()->back()->withInput()->with('error', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member, $id)
    {
      $member = Member::find($id);
      $user = Auth::user();
      // dd($user->members()->where('members.id', $id)->get());
      $c_types = CollectionsType::getTypes();
      // $sql = 'SELECT COUNT(case when attendance = "yes" then 1 end) AS present, COUNT(case when attendance = "no" then 1 end) AS absent,
      // MONTH(attendance_date) AS month FROM `members_attendances` WHERE YEAR(attendance_date) = YEAR(CURDATE()) AND member_id = '.$member->id.' GROUP BY month';
      $attendance = $member->attendances()->selectRaw("SUM(CASE when attendance = 'yes' then 1 else 0 end) As yes,
        SUM(CASE when attendance = 'no' then 1 else 0 end) As no")->first();//->sum('');
      // dd($attendance);
      return view('members.profile', compact('member', 'attendance', 'member', 'c_types'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member,$id)
    {
        $array = array('id'=>$id);
        Validator::make($array, [
            'id' => 'required|integer|max:10',
        ])->validate();
        $subjects = Subject::all();
        $subject = Subject::whereId($id)->firstOrFail();
        $edit = array('editmode'=>'true');
        $classes = TheClass::all();
        return view('subject.index', compact('subjects', 'subject', 'edit', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // check if image isnt empty
        if (!empty($request->file('photo'))){
            // validate image
            $this->validate($request, [
//                'class_id' => 'bail|required|integer|min:1',
//                'section_id' => 'required|integer|min:1',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $image = $request->file('photo');
            $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $input['imagename']);
            $image_name = $input['imagename'];
        }

        $id = $request->get('id');
        $member = Member::whereId($id)->firstOrFail();
//        $member->class_id = $request->get('class_id');
//        $member->section_id = $request->get('section_id');
        $member->firstname = $request->get('firstname');
        $member->lastname = $request->get('lastname');
        $member->phone = $request->get('phone');
        if (!empty($image_name) && ($image_name!== NULL)) $member->photo = $image_name;
        $member->sex = $request->get('gender');
        $member->dob = $request->get('dob');
        $member->address = $request->get('address');
        $member->address2 = $request->get('address2');
        $member->postal = $request->get('postal');
        $member->city = $request->get('city');
        $member->state = $request->get('state');
        $member->country = $request->get('country');
        $member->marital_status = $request->get('marital_status');
//        $member->guardian_is = $request->get('guardian_is');
//        $member->father_name = $request->get('father_name');
//        $member->father_phone = $request->get('father_phone');
//        $member->father_occupation = $request->get('father_occupation');
//        $member->mother_name = $request->get('mother_name');
//        $member->mother_phone = $request->get('mother_phone');
//        $member->mother_occupation = $request->get('mother_occupation');
//        $member->guardian_name = $request->get('guardian_name');
//        $member->guardian_relation = $request->get('guardian_relation');
//        $member->guardian_phone = $request->get('guardian_phone');
//        $member->guardian_occupation = $request->get('guardian_occupation');
//        $member->guardian_address = $request->get('guardian_address');
//        $member->is_active = $request->get('is_active');
        $member->save();
        return redirect()->route('member.profile', $id)->with('status', 'Member Record Successfully updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member, $id)
    {
      $member = Member::whereId($id)->firstOrFail();
      $member->delete();
      return response()->json(['status' => true, 'text' => "$member->firstname has been deleted!"]);
    }

    public function delete(Request $request){
      $failed = 0;
      $text = "All selected members deleted successfully";
      foreach ($request->id as $key => $value) {
        # code...
        $member = Member::whereId($value)->first();
        if($member){
          $member->delete();
        } else {
          $failed++;
          $text = "$failed Operations could not be performed";
        }
      }
      return response()->json(['status' => true, 'text' => $text]);
    }

    public function getRelative(Request $request, $search_term){

      $user = Auth::user();

//      $sql = "SELECT * from members WHERE branch_id = '$user->id' AND  MATCH (firstname,lastname)
//      AGAINST ('$search_term')";
//      $members = \DB::select($sql);

        $members=Member::where([['branch_id', $user->id], ['firstname', 'LIKE', '%'.$search_term.'%']])->orwhere([['branch_id', $user->id], ['lastname', 'LIKE', '%'.$search_term.'%']])->get();
      return response()->json(['success' => true, "result"=> sizeof($members) > 0 ? $members : ['message'=>'no result found']]);
    }

    public function modify($id){
      $user = \Auth::user();
      $member = Member::whereId($id)->where('branch_id',$user->id)->first();
      if (!$member) { return 'Member Not exists'; }
      return view('members.edit', compact('member'));
    }

    public function upgrade(Request $request){
      $status = false;
      $user = Member::where('id', $request->id)->first()->upgrade();
      if ($user) { $status = true; $text = "$user is now a full member"; }
      else { $text = "Error occured Please try again"; }
      return response()->json(['status' => $status, 'text' => $text]);
    }

    public function uploadImg(Request $request){
      $image_name = 'profile.png'; // default profile image
      if ($request->hasFile('photo'))
      {
          $image = $request->file('photo');
          $input['imagename'] = ($image->getClientOriginalExtension() != '') ? time().'.'.$image->getClientOriginalExtension() : time().'.jpg';
          print_r($input);

          $destinationPath = public_path('/images');

          $image->move($destinationPath, $input['imagename']);

          $image_name = $input['imagename'];

          $user = Member::orderBy('id', 'desc')->first();
          $user->photo = $image_name;
          $user->save();
          return response()->json(['status' => true,]);
      }
      return response()->json(['status' => false, 'text' => "No photo file"]);
    }

    public function testMail(Request $request){
      return new \App\Mail\MailToMember($request, Auth::user());
    }

    public function updateMember(Request $request){
      $member = Member::whereId($request->id)->first();
      // dd($request);
      if($member) {
        $errors = [];
        $fields = (array)$request->request;//->parameters;//->ParameterBag->parameters;
        $fields = $fields["\x00*\x00parameters"];
        foreach ($fields as $key => $value) {
          if ($key != 'id' && $key != '_token' && $key != 'action') {
              $member->$key = $request->$key;
          }
        }
        try {
          $member->save();
        } catch (\Exception $e) {
          array_push($errors, $e);
          // dd($e);
          return response()->json(['status' => false, 'text' => $e->errorInfo[2]]);
        }
      }
      else {return response()->json(['status' => false, 'text' => "Member does not exist"]);}
      return response()->json(['status' => true, 'text' => "Member has been updated!"]);
    }

    public function attendance($id, Request $request){
      $member = Member::find($id);
      if ($member) {  $member = $member->attendances()->with(['service_types'])->get(); }
      // dd($member);
      return DataTables::of($member)->make(true);
    }

  public function memberAnalysis (Request $request){
    $user = \Auth::user();
    $c_types = \App\CollectionsType::getTypes();
    $savings = \App\MemberCollection::rowToColumn(\App\MemberCollection::where('branch_id', $user->id)->where('member_id', $request->id)->get());
    $interval = $request->interval;
    $group = $request->group;
    $months = [];
    for ($i = $interval-1; $i >= 0; $i--) {
      $t = 'M';
      switch ($group) {
        case 'day': $t = 'D'; break;
        case 'week': $t = 'W'; break;
        case 'month': $t = 'M'; break;
        case 'year': $t = 'Y'; break;
      }
      $dateOrNot = $group == 'month' ? date('Y-m-01') : '';
      $months[$i] = date($t, strtotime($dateOrNot. "-$i $group")); //1 week ago
    }
    $collections2 = $this->calculateSingleTotal($savings, $group);
    $dt = (function($savings, $c_types, $months, $group){
      $output = [];
      foreach ($months as $key => $value) {
  		$month = $value; $found = false;
  		foreach ($savings as $collection) {
  			if($value == $collection->$group){
  				$found = true;
          $output[] = $this->yData($collection, $c_types, $value);
  			}
  		}
  		if(!$found){
  			$output[] = $this->noData($c_types, $value);
  		}
  	}
    return $output;
  })($collections2, $c_types, $months, $group);
    // dd($dt);
    return response()->json($dt);
  }

  private function yData($collection,$c_types, $value){
    $y = new \stdClass();
    $y->y = $value;  $i = 1; $size = sizeof($c_types);
    foreach ($c_types as $key => $value) {
      $name = $value->name;
      $amount = isset($collection->$name) ? $collection->$name : 0;
      $y->$name = $amount;
      $i++;
    }
    return $y; //. "},";
  }

  private function noData($c_types, $value){
    $y = new \stdClass();
    $y->y = $value; $i=1;
    foreach ($c_types as $key => $value) {
      $name = $value->name;
      $y->$name = 0;
      $i++;
    }
    return $y;//. "},";
  }

  private function calculateSingleTotal($savings, $type){
    $obj = [];
    foreach ($savings as $key => $value) {
      switch ($type) {
        case 'day': $t = 'D'; break;
        case 'week': $t = 'W'; break;
        case 'month': $t = 'M'; break;
        case 'year': $t = 'Y'; break;
      }
      $date = date($t, strtotime($value->date_collected));
      $year = (int)substr($value->date_collected, 0,4);
      foreach ($value->amounts as $ke => $valu) {
        if (isset($obj[$date])) {
          if (isset($obj[$date]->$ke)) {  $obj[$date]->$ke += $valu; } else { $obj[$date]->$ke = $valu; }
        } else {
          $obj[$date] = new \stdClass();
          $obj[$date]->$ke = $valu;
          $obj[$date]->$type = $date;
        }
      }
    }
    return $obj;
  }
// count(case when sex = 'male' then 1 end) AS male, count(case when sex = 'female' then 1 end) AS female,
  public function memberRegStats(Request $request){
    $user = \Auth::user();
    $members = Member::selectRaw("COUNT(id) as total, SUM(CASE WHEN sex='male' THEN 1 ELSE 0 END) AS male, SUM(CASE WHEN sex='female' THEN 1 ELSE 0 END) AS female,
    MONTH(member_since) AS month")->whereRaw("member_since > DATE(now() + INTERVAL - 12 MONTH)")->where("branch_id", $user->id)->groupBy("month")->get();
    // dd($members);
    $group = 'month';
    $months = [];
    $interval = 0;
    $ii = 11;
    $c_types = Array('male', 'female');
    for ($i = $interval; $i <= 11; $i++) {
      $t = 'M';
      switch ($group) {
        case 'day': $t = 'D'; break;
        case 'week': $t = 'W'; break;
        case 'month': $t = 'M'; break;
        case 'year': $t = 'Y'; break;
      }
      $dateOrNot = $group == 'month' ? date('Y-m-01') : '';
      $months[$ii] = date($t, strtotime($dateOrNot. "-$i $group")); //1 week ago
      $ii--;
    }

    $dt = (function($members, $c_types, $months, $group){
      $output = [];
      foreach ($months as $key => $value) {
  		$month = $value; $found = false;
  		foreach ($members as $member) {
        $m;
        switch ($member->$group) {
          case 1: $m = 'Jan'; break;
          case 2: $m = 'Feb'; break;
          case 3: $m = 'Mar'; break;
          case 4: $m = 'Apr'; break;
          case 5: $m = 'May'; break;
          case 6: $m = 'Jun'; break;
          case 7: $m = 'Jul'; break;
          case 8: $m = 'Aug'; break;
          case 9: $m = 'Sep'; break;
          case 10: $m = 'Oct'; break;
          case 11: $m = 'Nov'; break;
          case 12: $m = 'Dec'; break;
        }
        // dd($m);
  			if($month == $m){
  				$found = true;
          $output[] = $this->flotY($member, $c_types, $key);
  			}
  		}
  		if(!$found){
  			$output[] = $this->flotNoData($c_types, $key);
  		}
  	}
    return $output;
  })($members, $c_types, $months, $group);

  return $dt;
  }

  private function flotY($member, $c_types, $value){
    $y = [];
    $y['month'] = $value;  $i = 1; $size = sizeof($c_types);
    foreach ($c_types as $key => $value) {
      $name = $value;
      $amount = isset($member->$name) ? $member->$name : 0;
      $y[$name] = $amount;
      $i++;
    }
    return $y;
  }

  private function flotNoData($c_types, $value){
    $y = [];
    $y['month'] = $value; $i=1;
    foreach ($c_types as $key => $value) {
      $name = $value;   $y[$name] = 0;    $i++;
    }
    return $y;
  }

  public function calculateSingleTotalCollection($savings, $type){
    $obj = [];
    foreach ($savings as $key => $value) {
      switch ($type) {
        case 'day': $t = 'D'; break;
        case 'week': $t = 'W'; break;
        case 'month': $t = 'M'; break;
        case 'year': $t = 'Y'; break;
      }
      $date = date($t, strtotime($value->date_collected));
      $year = (int)substr($value->date_collected, 0,4);

      if ($type == 'year') {
        foreach ($value as $k => $v) {
          // dd($savings);
          $year = substr($k, 0,4);
          foreach ($v['amounts'] as $savingName => $savingAmount) {
            if (!isset($obj->$savingName)) {$obj->$savingName = new \stdClass();}
            if (isset($obj->$savingName->$year)) {
              $obj->$savingName->$year += $savingAmount;
            } else {
              $obj->$savingName->$year = $savingAmount;
            }
          }
        }
      }
    }
    return $obj;
  }

  //for department

  public function getdepartment()
  {
    $dept = Department::all();
    return view('dept.department', compact('dept'));
  }

  public function createdepartment(Request $request)
  {
    $request->validate([
      'deptname' => 'required',
    ]);
    $group = Department::create([
      'branch_id' => $request->branch_id,
      'dept_name' => $request->deptname
    ]);

    if ($group) {
      return redirect()->back()->with('status', 'Department created Successfully!');
    } else {
      return redirect()->back()->with('status', 'Department not created Successfully!');
    }
  }

  public function editDept(Request $request, $id)
  {
    $findDept =  Department::where('id', $id)->first();

    return view('dept.viewdept', compact('findDept'));
  }

  public function updatedept(Request $request, $id)
  {
    $deptname = $request->deptname;
    $updateDept = Department::find($id);
    $updateDept->update(['dept_name' => $deptname]);

    return redirect()->back()->with('status', 'Department Updated Successfully!');

  }

}
