@extends('layouts.app')

@section('title')
    {{ \Auth::user()->branchname }}{{ \Auth::user()->branchcode }}: Attendance Report
@endsection

@section('link')
    <link rel="stylesheet" href="{{ URL::asset('css/pignose.calendar.min.css') }}">
@endsection

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <style>
        li {
            display: inline;
        }
        .dropbtn {
            background-color: #4CAF50;
            color: white;
            padding: 16px;
            font-size: 16px;
            border: none;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #ddd;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
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
            <div id="page-title">
                <h1 class="page-header text-overflow">Attendance</h1>
            </div>
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="{{ route('dashboard') }}"> Dashboard</a></li>
                <li class="active">All</li>
            </ol>
        </div>

        <div id="page-content">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <form method="GET" action="{{ route('financial.viewall') }}" class="form-inline text-center">
                        <div class="form-group">
                            <label for="week">Week #</label>
                            <input type="number" name="week" id="week" class="form-control" min="1" max="53"
                                   value="{{ request('week', now()->weekOfYear) }}">
                        </div>
                        <div class="form-group" style="margin-left: 10px;">
                            <label for="year">Year</label>
                            <input type="number" name="year" id="year" class="form-control"
                                   value="{{ request('year', now()->year) }}">
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-left: 10px;">Filter</button>
                    </form>
                </div>
            </div>

            @if(request('week'))
                @php
                    $startOfWeek = \Carbon\Carbon::now()->setISODate(request('year', now()->year), request('week'))->startOfWeek(\Carbon\Carbon::MONDAY);
                    $endOfWeek = \Carbon\Carbon::now()->setISODate(request('year', now()->year), request('week'))->endOfWeek(\Carbon\Carbon::SUNDAY);
                @endphp
                <div class="alert alert-info text-center">
                    Showing offerings for the week of <strong>{{ $startOfWeek->format('F j, Y') }}</strong> to <strong>{{ $endOfWeek->format('F j, Y') }}</strong>
                </div>
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

            @foreach($offering_types as $offering_type)
                <div class="col-md-offset-1 col-md-10" style="margin-bottom:50px">
                    <div class="panel rounded-top" style="background-color: #e8ddd3;">
                        <div class="panel-heading text-center">
                            <h1 class="panel-title">{{ $offering_type["name"] }}</h1>
                        </div>
                        <div class="panel-body clearfix table-responsive">
                            <table id="demo-dt-basic" class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th class="min-tablet">Denomination</th>
                                    <th class="min-tablet">Quantity</th>
                                    <th class="min-tablet">Total</th>
                                    <th class="min-tablet">Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $count = 1; @endphp
                                @foreach($offerings->where('offering_id', $offering_type["id"]) as $offering)
                                    <tr>
                                        <td><strong>{{ $count }}</strong></td>
                                        <td>{{ $offering->denomination }}</td>
                                        <td>{{ $offering->quantity }}</td>
                                        <td>{{ $offering->total }}</td>
                                        <td>{{ \Carbon\Carbon::parse($offering->date)->format('l, F j, Y') }}</td>
                                    </tr>
                                    @php $count++; @endphp
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
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
            $('#demo-dt-basic').DataTable({
                dom: '<"top"B>rt<"bottom"ip><"clear">',
                buttons: [
                    { extend: 'copy', text: 'Copy' },
                    { extend: 'excel', text: 'Excel' },
                    { extend: 'pdf', text: 'PDF', title: 'PDF Title' }
                ]
            });
        });
    </script>
@endsection
