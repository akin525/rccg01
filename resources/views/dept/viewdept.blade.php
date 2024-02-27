@extends('layouts.app')

@section('title')
    All Departments
@endsection

@section('content')
    <!--CONTENT CONTAINER-->
    <!--===================================================-->
    <div id="content-container">
        <div id="page-head">

            <!--Page Title-->
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <div id="page-title">
                <h1 class="page-header text-overflow">Departments</h1>
            </div>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End page title-->


            <!--Breadcrumb-->
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i><a href="{{ route('dashboard') }}"> Dashboard</a>
                </li>
            </ol>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End breadcrumb-->

        </div>


        <!--Page content-->
        <!--===================================================-->
        <div id="page-content">
            @if (session('status'))
                <!-- Line Chart -->
                <!---------------------------------->
                <div class="panel">
                    <div class="panel-heading">
                    </div>
                    <div class="pad-all">
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
                </div>
                <!---------------------------------->
            @endif

            <div class="panel" style="background-color: #e8ddd3;">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">Create Department</h3>
                </div>
                <div class="pad-all">
                    <form method="POST" action="{{ url('member.updatedept/' . $findDept->id) }}">
                        @csrf
                        {{-- <input type=text name="branch_id" value="{{ \Auth::user()->branchcode }}" hidden=hidden /> --}}
                        <input style="border:1px solid #ddd; padding:7px;outline:none" name="deptname" type=text value="{{ $findDept->dept_name }}" />
                        <button type="submit" class="btn btn-success btn-md"><i class="fa fa-plus"></i> Update
                            Department</button>
                    </form>
                </div>
            </div>

        </div>
        <!--===================================================-->
        <!--End page content-->

    </div>
    <!--===================================================-->
    <!--END CONTENT CONTAINER-->
@endsection
