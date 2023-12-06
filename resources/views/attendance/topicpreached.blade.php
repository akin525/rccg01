@extends('layouts.app')

@section('title')
    Add Topic/Message
@endsection

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
                <h1 class="page-header text-overflow">Add Topic/Message</h1>
            </div>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End page title-->

            <!--Breadcrumb-->
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i><a href="{{ route('dashboard') }}"> Dashboard</a>
                </li>
                <li class="active">Add</li>
            </ol>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End breadcrumb-->

        </div>


        <!--Page content-->
        <!--===================================================-->
        @include('layouts.error')
        <div class="col-md-12 col-md-offset-0 col-lg-8 col-lg-offset-1" style="margin-bottom:20px">
            <div class="panel rounded-top" style="background-color: #e8ddd3;">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">Mark Attendnace for <strong>{{ \Auth::user()->branchname }}
                            <i>{{ \Auth::user()->branchcode }}</i></strong></h3>
                </div>
                <!-- if service types not exists -->
                @if (!count($services) > 0)
                    <div class="col-12 well text-center bg-danger">
                        <div class="text-lg">
                            <div class="col-8">
                                Oooops! to mark attendance please create service type here
                            </div>
                            <div class="col-4">
                                <a class="btn btn-info" href="{{ route('branch.tools') }}">Add Service Type</a>
                            </div>
                        </div>
                    </div>
                @else
                    <!--Block Styled Form -->
                    <!--===================================================-->
                    <form id="b-attendance-form" method="POST" action="{{ route('attendance.topic.store') }}">
                        @csrf
                        <input name="branch_id" value="3" type="text" hidden="hidden" />
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-6">
                                    <div class="form-group text-center">
                                        <label class="control-label">Date</label>
                                        <input id="mark-date" type="date" name="date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-sm-3"></div>
                            </div>
                            {{-- <div class="row text-center"> --}}
                            {{-- <div class="col-sm-2"> --}}
                            <div class="form-group">
                                <label class="control-label">Topic</label>
                                <input type="text" name="topic" class="form-control" required>
                            </div>
                            {{-- </div> --}}
                            {{-- <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label">Female</label>
                                        <input type="number" min=0 name="female" class="form-control" required>
                                    </div>
                                </div> --}}

                            {{-- <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label">Children</label>
                                        <input type="number" min=0 name="children" class="form-control" required>
                                    </div>
                                </div> --}}

                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label class="control-label">Service Type</label>
                                    <select name="type" id="mark-select" class="selectpicker" data-style="btn-success"
                                        required>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                            </div>
                            <div class="row">
                            </div>
                        </div>
            </div>
            <div class="panel-footer text-right bg-dark">
                <button id="btn-mark" class="btn btn-success" type="submit">Submit</button>
            </div>
            </form>
            @endif
        </div>
    </div>
    </div>

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
