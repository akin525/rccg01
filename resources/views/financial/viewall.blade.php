@extends('layouts.app')

@section('title')
    {{ \Auth::user()->branchname }}{{ \Auth::user()->branchcode }}: Attendance Report
@endsection

@section('link')
    <link rel="stylesheet" href="{{ URL::asset('css/pignose.calendar.min.css') }}">
@endsection
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <?php
    
    // extract addedVariables value to variable
    if (isset($addedVariables)) {
        extract($addedVariables);
    }
    
    ?>
    <!--CONTENT CONTAINER-->
    <!--===================================================-->
    <style>
        li {
            display: inline;
        }

        /* Dropdown Button */
        .dropbtn {
            background-color: #4CAF50;
            color: white;
            padding: 16px;
            font-size: 16px;
            border: none;
        }

        /* The container <div> - needed to position the dropdown content */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        /* Links inside the dropdown */
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        /* Change color of dropdown links on hover */
        .dropdown-content a:hover {
            background-color: #ddd;
        }

        /* Show the dropdown menu on hover */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Change the background color of the dropdown button when the dropdown content is shown */
        .dropdown:hover .dropbtn {
            background-color: #3e8e41;
        }

        .icon {
            font-size: 100px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.previous {
        float: left;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.next {
        float: right;
        background-color: navy;
        color: white;
    }
    </style>
    <div id="content-container">
        <div id="page-head">

            <!--Page Title-->
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <div id="page-title">
                <h1 class="page-header text-overflow">Attendance</h1>
            </div>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End page title-->


            <!--Breadcrumb-->
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i><a href="{{ route('dashboard') }}"> Dashboard</a>
                </li>
                <li class="active">All</li>
            </ol>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End breadcrumb-->

        </div>


        <!--Page content-->
        <!--===================================================-->
        <div id="page-content">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                </div>
                {{--  --}}
                <div class="col-md-offset-1 col-md-10" style="margin-bottom:50px">
                    <div class="panel rounded-top" style="background-color: #e8ddd3;">
                        <div class="panel-heading text-center">
                            <h1 class="panel-title">Tithes<h1>
                        </div>
                        <div class="panel-body clearfix table-resposive">
                            <table id="demo-dt-basic" class="table table-striped table-bordered datatable" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th class="min-tablet">Amount</th>
                                        <th class="min-tablet">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 1; ?>
                                    @foreach ($tithe as $tithes)
                                      
                                        <tr>
                                            <td><strong>{{ $count }}</strong></td>
                                            <td>{{ $tithes->amount }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tithes->date)->format('l, F j, Y') }}</td>                                            {{-- <td><button id="{{$list->attendance_date}}" type="submit" class="btn btn-primary viewBtn" onclick="viewer(this);">View</button></td> --}}
                                        </tr>
                                        <?php $count++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-offset-1 col-md-10" style="margin-bottom:50px">
                    <div class="panel rounded-top" style="background-color: #e8ddd3;">
                        <div class="panel-heading text-center">
                            <h1 class="panel-title">Building Offering<h1>
                        </div>
                        <div class="panel-body clearfix table-resposive">
                            <table id="demo-dt-basic-building" class="table table-striped table-bordered datatable" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th class="min-tablet">Amount</th>
                                        <th class="min-tablet">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 1; ?>
                                    @foreach ($building as $buildings)
                                    
                                        <tr>
                                            <td><strong>{{ $count }}</strong></td>
                                            <td>{{ $buildings->amount }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tithes->date)->format('l, F j, Y') }}</td>                                            {{-- <td><button id="{{$list->attendance_date}}" type="submit" class="btn btn-primary viewBtn" onclick="viewer(this);">View</button></td> --}}
                                        </tr>
                                        <?php $count++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="col-md-offset-1 col-md-10" style="margin-bottom:50px">
                    <div class="panel rounded-top" style="background-color: #e8ddd3;">
                        <div class="panel-heading">
                            <h1 class="panel-title text-center">Offerings<h1>
                        </div>
                        <div class="panel-body text-center clearfix table-response">
                            <table id="demo-dt-basic-offering" class="table table-striped table-bordered datatable" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th class="min-tablet">Amount</th>
                                        <th class="min-tablet">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 1; ?>
                                    @foreach ($offering as $offerings)
                                        <tr>
                                            <td><strong>{{ $count }}</strong></td>
                                            <td>{{ ucwords($offerings->amount) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tithes->date)->format('l, F j, Y') }}</td>
                                            {{-- <td>{{ucwords($member->lastname)}}</td> --}}
                                            {{-- <td>
                                  <button data-fullname="{{$member->getFullname()}}" data-id="{{$member->id}}" class="btn btn-primary show-member-history fa fa-eye"> View</button>
                                </td> --}}
                                        </tr>
                                        <?php $count++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--===================================================-->
        <!--End page content-->
    </div>
    <!--===================================================-->
    <!--END CONTENT CONTAINER-->
@endsection

@section('js')
    <script src="{{ URL::asset('js/functions.js') }}"></script>
    <script src="{{ URL::asset('js/pignose.calendar.full.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">

    <script>
        $(document).ready(function () {
            // DataTable initialization for Tithes
            $('#demo-dt-basic').DataTable({
                dom: '<"top"B>rt<"bottom"ip><"clear">',
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Copy'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'PDF Title',
                        customize: function (doc) {
                            // You can customize the PDF output if needed
                        }
                    }
                ],
                // Add other DataTables options as needed
            });

            // DataTable initialization for Building Offering
            $('#demo-dt-basic-building').DataTable({
                dom: '<"top"B>rt<"bottom"ip><"clear">',
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Copy'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'PDF Title',
                        customize: function (doc) {
                            // You can customize the PDF output if needed
                        }
                    }
                ],
                // Add other DataTables options as needed
            });

            // DataTable initialization for Offerings
            $('#demo-dt-basic-offering').DataTable({
                dom: '<"top"B>rt<"bottom"ip><"clear">',
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Copy'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'PDF Title',
                        customize: function (doc) {
                            // You can customize the PDF output if needed
                        }
                    }
                ],
                // Add other DataTables options as needed
            });
        });
    </script>
@endsection
