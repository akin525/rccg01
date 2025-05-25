<?php

namespace App\Http\Controllers;

use App\BuildingOffering;
use App\FinancialReport;
use App\OfferingDetail;
use App\OfferingType;
use App\Remittance;
use App\Tithe;
use App\Offering;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    public function tithe(Request $request)
    {
        $validate = validator([
            'amount' => 'required',
            'date' => 'required',
        ]);
        if ($validate) {
            $savetithe = Tithe::create([
                'amount' => $request->amount,
                'date' => $request->date,
            ]);
            if ($savetithe) {
                return redirect()->back()->with('status', "Tithe Amount saved successfully!");
            }
        } else {
            return redirect()->back()->with('status', "all fields is required");
        }
    }

    public function getithe()
    {
        return view('financial.tithe');
    }
    public function remintance()
    {
        return view('remittance.areafin');
    }

    public function offering(Request $request)
    {
        $user = Auth::user();
        $validate = validator([
            'type' => 'required|string',
            'date' => 'required|date',
            'denominations.*.quantity' => 'nullable|integer|min:0',
            'denominations.*.total' => 'nullable|numeric|min:0',
        ]);
        if ($validate) {
//            $offering = new Offering();
//            $offering->type = $request->input('type');
//            $offering->date = $request->input('date');
//            $offering->grand_total = $request->input('grand_total');
//            $offering->save();

            // Save each denomination detail
            foreach ($request->input('denominations') as $denomination => $data) {
                if (!empty($data['quantity']) && !empty($data['total'])) {
                    $offeringDetail = new OfferingDetail();
                    $offeringDetail->offering_id = $request->input('type');
                    $offeringDetail->denomination = $denomination;
                    $offeringDetail->quantity = $data['quantity'];
                    $offeringDetail->total = $data['total'];
                    $offeringDetail->offering_date = $request->input('date');
                    $offeringDetail->branch_id = $user->id;
                    $offeringDetail->save();
                }
            }
                return redirect()->back()->with('status', "Offering Amount saved successfully!");

        } else {
            return redirect()->back()->with('status', "all fields is required");
        }
    }

    public function getoffering()
    {
        $offering_types = OfferingType::all();
        return view('financial.offering', compact('offering_types'));
    }
    public function finreport()
    {
        $report = FinancialReport::all();

        return view('remittance.allareafin', compact('report'));
    }

    public function buildingoffering(Request $request)
    {
        $validate = validator([
            'amount' => 'required',
            'date' => 'required',
        ]);
        if ($validate) {
            $savetithe = BuildingOffering::create([
                'amount' => $request->amount,
                'date' => $request->date,
            ]);
            if ($savetithe) {
                return redirect()->back()->with('status', "Building offering Amount saved successfully!");
            }
        } else {
            return redirect()->back()->with('status', "all fields is required");
        }
    }

    public function getbuildingoffering()
    {
        return view('financial.buildingoffering');
    }

    public function viewall(Request $request)
    {
        $user = Auth::user();
        $week = $request->input('week', now()->weekOfYear);
        $year = $request->input('year', now()->year);

        $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->setISODate($year, $week)->endOfWeek(Carbon::SUNDAY);

        $offerings = OfferingDetail::whereBetween('offering_date', [$startOfWeek, $endOfWeek])
            ->where('branch_id', $user->id)
            ->get();

        $offering_types = OfferingType::all();

        return view('financial.viewall', compact('offering_types', 'offerings', 'endOfWeek'));
    }

    public function viewall1()
    {
        $user = Auth::user();
        $startOfWeek = Carbon::now()->subWeeks(2)->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->subWeeks(2)->endOfWeek(Carbon::SUNDAY);
        $startOfWeek = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->subWeek()->endOfWeek(Carbon::SUNDAY);
        $offerings = OfferingDetail::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('branch_id', $user->id)->get();
        $offering_types = OfferingType::all();
        return view('financial.viewall', compact('offering_types','offerings'));
    }

    public function fetchUnremittedOfferings()
    {
        $user = Auth::user();
// Get the current date
        $now = Carbon::now()->subMonth();

        // Get the latest two remittance records
        $remittances = Remittance::where('branch_id', $user->id)
//            ->orderByDesc('remittance_date')
//            ->limit(2)
            ->pluck('remittance_date');

        // Define start and end of period
        $startDate = $remittances->filter(function ($date) use ($now) {
            return $date <= $now;
        })->first();
        $endDate = $remittances->filter(function ($date) use ($now) {
            return $date >= $now;
        })->first();   // Latest remittance

        // If no remittance at all, fetch everything
        if ($remittances->isEmpty()) {
            $offerings = OfferingDetail::where('branch_id', $user->id)->get();
        }else {
            // If there's no previous remittance, default to first day of last month
            if (!$startDate) {
                $startDate = Carbon::now()->subMonth()->startOfMonth();
            }
            $offerings = OfferingDetail::whereDate('offering_date', '>', Carbon::parse($startDate)->toDateString())
                ->whereDate('offering_date', '<=', Carbon::parse($endDate)->toDateString())
                ->where('branch_id', $user->id)
                ->get();
        }
        $offering_types = OfferingType::all();

        $totalsByOfferingType = []; // initialize array to hold totals

        foreach ($offering_types as $offering_type) {
            $totalsByOfferingType[$offering_type->id] = $offerings
                ->where('offering_id', $offering_type->id)
                ->sum('total');
        }
        dd("Gospel Offering $totalsByOfferingType[1], Sunday School $totalsByOfferingType[2], Building Offering $totalsByOfferingType[3], SLO $totalsByOfferingType[4], Children Offering $totalsByOfferingType[5], Congregation Tithe $totalsByOfferingType[6], Minister Tithe $totalsByOfferingType[7], First Fruit $totalsByOfferingType[8], Tuesday Offering $totalsByOfferingType[9], Thursday Offering $totalsByOfferingType[10], Thanksgiving $totalsByOfferingType[11], Others $totalsByOfferingType[12]");
        return view('financial.financialreport', compact('totalsByOfferingType', 'offering_types' ));
    }
}
