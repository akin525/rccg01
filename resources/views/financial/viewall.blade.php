@extends('layouts.app')

@section('title', 'Usher Count Sheet')

@section('content')
    <style>
        .count-sheet-table, .count-sheet-table td, .count-sheet-table th {
            border: 1px solid #000;
            border-collapse: collapse;
        }
        .count-sheet-table td, .count-sheet-table th {
            padding: 4px;
            text-align: center;
        }
        .section-title {
            background-color: #ccc;
            font-weight: bold;
            text-align: center;
        }
    </style>

    @php
        $currentWeek = request('week', now()->weekOfYear);
        $currentYear = request('year', now()->year);

        $todayWeek = now()->weekOfYear;
        $todayYear = now()->year;

        $previousWeek = $currentWeek - 1;
        $previousYear = $currentYear;
        if ($previousWeek < 1) {
            $previousYear -= 1;
            $previousWeek = \Carbon\Carbon::create($previousYear)->endOfYear()->weekOfYear;
        }

        $nextWeek = $currentWeek + 1;
        $nextYear = $currentYear;
        if ($nextWeek > 53) {
            $nextYear += 1;
            $nextWeek = 1;
        }

        $showNext = !($currentWeek == $todayWeek && $currentYear == $todayYear);
    @endphp

    <div class="container">

        @if(request('week'))
            @php
                $startOfWeek = \Carbon\Carbon::now()->setISODate(request('year', now()->year), request('week'))->startOfWeek(\Carbon\Carbon::MONDAY);
                $endOfWeek = \Carbon\Carbon::now()->setISODate(request('year', now()->year), request('week'))->endOfWeek(\Carbon\Carbon::SUNDAY);
            @endphp
        @endif

        <div class="col-md-6 col-md-offset-3">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif
        </div>


        <h3 class="text-center">THE REDEEMED CHRISTIAN CHURCH OF GOD</h3>
        <p class="text-center">LAGOS PROVINCE 64</p>
        <h4 class="text-center">USHERS COUNT SHEET</h4>

            <div class="row text-center mb-3">
                <div class="form-group" style="margin-left: 20px;">
                    <a href="{{ route('financial.viewall', ['week' => $previousWeek, 'year' => $previousYear]) }}" class="btn btn-warning">Previous</a>

                    @if($showNext)
                        <a href="{{ route('financial.viewall', ['week' => $nextWeek, 'year' => $nextYear]) }}" class="btn btn-success" style="margin-left: 10px;">Next</a>
                    @endif
                </div>
            </div>
        <table style="width:100%; margin-bottom: 10px;">
            <tr>
                <td><strong>Parish:</strong> {{\Auth::user()->branchname}}</td>
                <td><strong>Area:</strong> 41</td>
                <td><strong>Date:</strong> {{ $endOfWeek->format('F j, Y') }}</td>
                <td><strong>Sheet No:</strong> 0651</td>
            </tr>
        </table>

        @php

            $labels = ['1000','500','200','100','50','20','10','5',"Transfer"
            ];
             $chunks = collect($offering_types)->chunk(3);
        @endphp

        @foreach($chunks as $group)
            <table class="count-sheet-table" width="100%" style="margin-bottom: 10px;">
                <tr>
                    @foreach($group as $type)
                        <th colspan="3" class="section-title">{{ $type->name }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($group as $type)
                        <td>Denom</td><td>Qty</td><td>N</td>
                    @endforeach
                </tr>

                @foreach($labels as $label)
                    <tr>
                        @foreach($group as $type)
                            @php
                                $items = $offerings->where('offering_id', $type->id)->where("denomination", $label)->first();
                            @endphp
                            <td>{{ $label }}</td>
                            <td>{{ $items->quantity ?? '' }}</td>
                            <td>{{ $items->total ?? '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        @endforeach

        <!-- Summary Table -->
        <table class="count-sheet-table" width="100%" style="margin-top: 20px;">
            <tr><th colspan="4" class="section-title">SUMMARY</th></tr>
            @foreach($offering_types as $key)
                @php
                    $sum = $offerings->where('offering_id', $key->id)->sum('total');
                @endphp
                <tr>
                    <td>{{ $key->name }}</td>
                    <td colspan="2"></td>
                    <td><strong>{{ number_format($sum, 2) }}</strong></td>
                </tr>
            @endforeach
            <tr>
                <td>Total</td>
                <td colspan="2"></td>
                <td><strong>{{ number_format($offerings->sum('total'), 2) }}</strong></td>
            </tr>
        </table>

        <!-- Signatures -->
        <table style="width:100%; margin-top: 30px;">
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Head Usher</strong></td><td>Name: ____________________</td><td>Signature: ________________</td>
            </tr>
            <tr>
                <td><strong>Treasurer</strong></td><td>Name: ____________________</td><td>Signature: ________________</td>
            </tr>
            <tr>
                <td><strong>Pastor</strong></td><td>Name: ____________________</td><td>Signature: ________________</td>
            </tr>
        </table>
            <br>
            <br>
    </div>
@endsection
