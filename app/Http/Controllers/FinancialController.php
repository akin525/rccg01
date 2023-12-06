<?php

namespace App\Http\Controllers;

use App\BuildingOffering;
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

    public function offering(Request $request)
    {
        $validate = validator([
            'amount' => 'required',
            'date' => 'required',
        ]);
        if ($validate) {
            $savetithe = Offering::create([
                'amount' => $request->amount,
                'date' => $request->date,
            ]);
            if ($savetithe) {
                return redirect()->back()->with('status', "Offering Amount saved successfully!");
            }
        } else {
            return redirect()->back()->with('status', "all fields is required");
        }
    }

    public function getoffering()
    {
        return view('financial.offering');
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
