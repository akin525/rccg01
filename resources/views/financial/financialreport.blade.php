@extends('layouts.app')

@section('title') Register Tithe @endsection

@section('link')
    <link href="{{ URL::asset('css/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!--CONTENT CONTAINER-->
    <!--===================================================-->
    <div id="content-container">
        <div id="page-head">

            <!--Page Title-->
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <div id="page-title">
                <h1 class="page-header text-overflow">Add Offering total Amount For Today</h1>
            </div>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End page title-->

            <!--Breadcrumb-->
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i><a href="{{ route('dashboard') }}"> Dashboard</a>
                </li>
                <li class="active">Add Offering Amount</li>
            </ol>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End breadcrumb-->

        </div>


        <!--Page content-->
        <!--===================================================-->
        <div id="page-content">
            @include('layouts.error')
            <div class="col-md-12 col-md-offset-0 col-lg-8 col-lg-offset-1" style="margin-bottom:20px">
                <div class="panel rounded-top" style="background-color: #e8ddd3;">
                    <div class="panel-heading">
                        {{-- <h3 class="panel-title text-center">Mark Attendnace for <strong>{{\Auth::user()->branchname}} <i>{{\Auth::user()->branchcode}}</i></strong></h3> --}}
                    </div>

                        <style>
                            .report-container {
                                font-family: 'Arial', sans-serif;
                                border: 2px solid red;
                                padding: 20px;
                                max-width: 1000px;
                                margin: auto;
                                font-size: 12px;
                            }

                            .report-title {
                                text-align: center;
                                font-weight: bold;
                                color: red;
                            }

                            .report-subtitle {
                                text-align: center;
                                font-weight: bold;
                                font-size: 14px;
                            }

                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-top: 10px;
                            }

                            th, td {
                                border: 1px solid red;
                                padding: 3px;
                                text-align: center;
                            }

                            .section-header {
                                background-color: #fdd;
                                font-weight: bold;
                                text-align: left;
                                padding-left: 5px;
                            }

                            .bottom-section {
                                margin-top: 20px;
                            }

                            .bottom-section div {
                                margin-bottom: 10px;
                            }

                            .signature-area {
                                margin-top: 20px;
                                display: flex;
                                justify-content: space-between;
                            }
                        </style>

                        <div class="report-container">
                            <div class="report-title">
                                THE REDEEMED CHRISTIAN CHURCH OF GOD<br>
                                LAGOS PROVINCE 64<br>
                                (The Province of Ceaseless Praise)
                            </div>
                            <div class="report-subtitle">MONTHLY GENERAL PROGRESS REPORT SHEET</div>

                            <div class="row mt-3 mb-3">
                                <div>For the Month of: <strong>{{ $month ?? '_____________' }}</strong> &nbsp;
                                    Year: <strong>{{ $year ?? '_______' }}</strong>
                                </div>
                                <div>Area: <strong>{{ $area ?? '__________________' }}</strong> &nbsp;
                                    Parish: <strong>{{ $parish ?? '__________________' }}</strong>
                                </div>
                            </div>

                            {{-- ATTENDANCE TABLE --}}
                            <table>
                                <thead>
                                <tr>
                                    <th rowspan="2">DATE</th>
                                    <th rowspan="2">DAYS</th>
                                    <th colspan="3">ATTENDANCE</th>
                                    <th rowspan="2">PREACHER</th>
                                    <th rowspan="2">Sunday School</th>
                                    <th rowspan="2">House Fellowship</th>
                                    <th rowspan="2">MONETARY 100%</th>
                                    <th rowspan="2">%</th>
                                    <th rowspan="2">AMOUNT REMITTED</th>
                                </tr>
                                <tr>
                                    <th>MEN</th>
                                    <th>WOMEN</th>
                                    <th>CHILDREN</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reportLines ?? range(1, 31) as $line)
                                    <tr>
                                        <td>{{ $line['date'] ?? '' }}</td>
                                        <td>{{ $line['day'] ?? '' }}</td>
                                        <td>{{ $line['men'] ?? '' }}</td>
                                        <td>{{ $line['women'] ?? '' }}</td>
                                        <td>{{ $line['children'] ?? '' }}</td>
                                        <td>{{ $line['preacher'] ?? '' }}</td>
                                        <td>{{ $line['sunday_school'] ?? '' }}</td>
                                        <td>{{ $line['house_fellowship'] ?? '' }}</td>
                                        <td>{{ $line['monetary_type'] ?? '' }}</td>
                                        <td>{{ $line['percentage'] ?? '' }}</td>
                                        <td>{{ $line['amount_remitted'] ?? '' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{-- FOOTER TOTALS --}}
                            <div class="bottom-section">
                                <div><strong>FOR OFFICE USE ONLY</strong></div>
                                <div>Comment on the attendance compared with that of last month: ___________________________</div>

                                <div class="signature-area">
                                    <div>
                                        Treasurer's Name: _______________________<br>
                                        Tel No: ___________<br>
                                        Signature & Date: _______________
                                    </div>
                                    <div>
                                        Pastor In Charge: _______________________<br>
                                        Tel No: ___________<br>
                                        Signature & Date: _______________
                                    </div>
                                </div>

                                <div class="mt-3"><strong>TOTAL AMOUNT REMITTED:</strong> ₦{{ $totalRemitted ?? '__________' }}</div>
                            </div>
                        </div>


                </div>
            </div>
        </div>
    </div>
    <!--===================================================-->
    <!--End page content-->

    <!--===================================================-->
    <!--END CONTENT CONTAINER-->
@endsection

@section('js')
    <script src="{{ URL::asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('js/functions.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/media/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/media/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/buttons.semanticui.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/buttons.html5.min.js') }}"></script>

    <script src="{{ URL::asset('plugins/datatables/buttons.colVis.min.js') }}"></script>
@endsection