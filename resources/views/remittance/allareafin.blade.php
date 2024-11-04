@extends('layouts.app')

@section('title') Area Financial Report @endsection

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
                <h1 class="page-header text-overflow">Add Area Financial Report</h1>
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
        <div class="container">
            <h3 class="text-center">THE REDEEMED CHRISTIAN CHURCH OF GOD</h3>
            <h4 class="text-center">Lagos Province 64 - Monthly Financial Report</h4>

            <div class="panel panel-default">
                <div class="panel-body">
                    <!-- Report Header Information -->
{{--                    <div class="row">--}}
{{--                        <div class="col-md-4">--}}
{{--                            <strong>Zone:</strong> {{ $report->zone ?? 'Zone Name' }}--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4">--}}
{{--                            <strong>Month:</strong> {{ $report->month ?? 'Month Name' }}--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4">--}}
{{--                            <strong>Name of PIC Area:</strong> {{ $report->pic_name ?? 'PIC Name' }}--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <hr>

                    <!-- FORM A Section -->
                    <h5><strong>FORM A</strong></h5>
                    <table class="table table-bordered table-condensed text-center">
                        <thead>
                        <tr>
                            <th>Area</th>
                            <th>Group</th>
                            <th>Offering Type</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($report as $formAEntry)
                            <tr>
                                <td>{{ $formAEntry->area }}</td>
                                <td>{{ $formAEntry->group }}</td>
                                <td>{{ $formAEntry->offering_type }}</td>
                                <td>{{ number_format($formAEntry->total, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total A</strong></td>
{{--                            <td>{{ number_format($report->total_a, 2) }}</td>--}}
                        </tr>
                        </tfoot>
                    </table>

                    <!-- FORM B Section -->
                    <h5><strong>FORM B</strong></h5>
                    <table class="table table-bordered table-condensed text-center">
                        <thead>
                        <tr>
                            <th>Area</th>
                            <th>Group</th>
                            <th>Offering Type</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($report as $formBEntry)
                            <tr>
                                <td>{{ $formBEntry->area }}</td>
                                <td>{{ $formBEntry->group }}</td>
                                <td>{{ $formBEntry->offering_type }}</td>
                                <td>{{ number_format($formBEntry->total, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total B</strong></td>
{{--                            <td>{{ number_format($report->total_b, 2) }}</td>--}}
                        </tr>
                        </tfoot>
                    </table>

                    <!-- Grand Total -->
                    <div class="row">
                        <div class="col-md-8 text-right">
                            <strong>Total Amount Remitted:</strong>
                        </div>
                        <div class="col-md-4">
{{--                            <input type="text" id="grand_total" name="grand_total" class="form-control" value="{{ number_format($report->grand_total, 2) }}" readonly>--}}
                        </div>
                    </div>

                    <!-- PIC Signature Section -->
                    <div class="row mt-4">
                        <div class="col-md-6">
{{--                            <strong>Name of PIC Area:</strong> {{ $report->pic_name ?? 'PIC Name' }}--}}
                        </div>
                        <div class="col-md-6 text-right">
                            <strong>Sign of PIC Area:</strong> <span class="signature-line">__________________</span>
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