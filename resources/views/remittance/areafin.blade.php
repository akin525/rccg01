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
        <div id="page-content">
            @include('layouts.error')
            <div class="col-md-12 col-md-offset-0 col-lg-8 col-lg-offset-1" style="margin-bottom:20px">
                <div class="panel rounded-top" style="background-color: #e8ddd3;">
                    <div class="panel-heading">
                        {{-- <h3 class="panel-title text-center">Mark Attendnace for <strong>{{\Auth::user()->branchname}} <i>{{\Auth::user()->branchcode}}</i></strong></h3> --}}
                    </div>




                    <form id="monthly-financial-report" method="POST" action="">
                        @csrf
                        <div class="panel-body">
                            <h3>The Redeemed Christian Church of God Monthly Financial Report</h3>
                            <div class="form-group">
                                <label for="month">Month</label>
                                <input type="text" id="month" name="month" class="form-control" placeholder="Enter Month (e.g., January)">
                            </div>

                            <div class="form-group">
                                <label for="zone">Zone</label>
                                <input type="text" id="zone" name="zone" class="form-control" placeholder="Enter Zone">
                            </div>

                            <!-- FORM A -->
                            <h4>Form A</h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Area</th>
                                    <th>Group</th>
                                    <th>Offering Type</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="text" name="form_a[0][area]" class="form-control" placeholder="Enter Area"></td>
                                    <td><input type="text" name="form_a[0][group]" class="form-control" placeholder="Group"></td>
                                    <td><input type="text" name="form_a[0][offering_type]" class="form-control" placeholder="Offering Type"></td>
                                    <td><input type="number" name="form_a[0][total]" class="form-control" placeholder="Total"></td>
                                </tr>
                                <!-- Add more rows as needed for each offering type -->
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3"><strong>Total A</strong></td>
                                    <td><input type="number" name="total_a" id="total_a" class="form-control" readonly></td>
                                </tr>
                                </tfoot>
                            </table>

                            <!-- FORM B -->
                            <h4>Form B</h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Area</th>
                                    <th>Group</th>
                                    <th>Offering Type</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="text" name="form_b[0][area]" class="form-control" placeholder="Enter Area"></td>
                                    <td><input type="text" name="form_b[0][group]" class="form-control" placeholder="Group"></td>
                                    <td><input type="text" name="form_b[0][offering_type]" class="form-control" placeholder="Offering Type"></td>
                                    <td><input type="number" name="form_b[0][total]" class="form-control" placeholder="Total"></td>
                                </tr>
                                <!-- Add more rows as needed for each offering type -->
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3"><strong>Total B</strong></td>
                                    <td><input type="number" name="total_b" id="total_b" class="form-control" readonly></td>
                                </tr>
                                </tfoot>
                            </table>

                            <!-- Grand Total -->
                            <div class="form-group">
                                <label for="grand_total">Total Amount Remitted</label>
                                <input type="number" id="grand_total" name="grand_total" class="form-control" readonly>
                            </div>

                            <!-- Signature Section -->
                            <div class="form-group">
                                <label for="pic_name">Name of PIC Area</label>
                                <input type="text" id="pic_name" name="pic_name" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="pic_signature">Signature of PIC Area</label>
                                <input type="text" id="pic_signature" name="pic_signature" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary">Submit Report</button>
                        </div>
                    </form>

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