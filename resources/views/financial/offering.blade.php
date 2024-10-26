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
                    <form id="b-attendance-form" method="POST" action="{{ route('financial.offering') }}">
                        @csrf
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label">Date</label>
                                        <input type="date" name="date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label">Total Amount</label>
                                        <select class="form-control" name="amount" required>
                                            <option>Select your domination</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="200">200</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3"></div>
                            </div>
                            <div class="row">
                            </div>
                        </div>
                        </div>
                        <div class="panel-footer text-right bg-dark">
                         <button id="btn-mark" class="btn btn-success" type="submit">Submit</button>
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
