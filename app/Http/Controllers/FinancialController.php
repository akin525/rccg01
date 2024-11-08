<?php

namespace App\Http\Controllers;

use App\BuildingOffering;
use App\FinancialReport;
use App\OfferingDetail;
use App\Tithe;
use App\Offering;
use Illuminate\Http\Request;

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
        $validate = validator([
            'type' => 'required|string',
            'date' => 'required|date',
            'denominations.*.quantity' => 'nullable|integer|min:0',
            'denominations.*.total' => 'nullable|numeric|min:0',
        ]);
        if ($validate) {
            $offering = new Offering();
            $offering->type = $request->input('type');
            $offering->date = $request->input('date');
            $offering->grand_total = $request->input('grand_total');
            $offering->save();

            // Save each denomination detail
            foreach ($request->input('denominations') as $denomination => $data) {
                if (!empty($data['quantity']) && !empty($data['total'])) {
                    $offeringDetail = new OfferingDetail();
                    $offeringDetail->offering_id = $offering->id;
                    $offeringDetail->denomination = $denomination;
                    $offeringDetail->quantity = $data['quantity'];
                    $offeringDetail->total = $data['total'];
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
        return view('financial.offering');
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

    public function viewall()
    {
        $building = BuildingOffering::all();
        $offering = Offering::all();
        $tithe = Tithe::all();
        return view('financial.viewall', compact('building', 'offering', 'tithe'));
    }
}
