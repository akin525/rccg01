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
                <h1 class="page-header text-overflow">Department</h1>
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
        <!-- check if admin -->
        <?php $admin = \Auth::user()->isAdmin(); ?>

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

            <!-- Line Chart -->
            <!---------------------------------->
            <div class="panel" style="background-color: #e8ddd3;">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">Create Department</h3>
                </div>
                <div class="pad-all">
                    <form method="POST" action="{{ route('member.createdepartment') }}">
                        @csrf
                        <input type=text name="branch_id" value="{{ \Auth::user()->branchcode }}" hidden=hidden />
                        <input style="border:1px solid #ddd; padding:7px;outline:none" name="deptname" type=text
                            Placeholder="Department Name" required />
                        <button type="submit" class="btn btn-success btn-md"><i class="fa fa-plus"></i> Create
                            Department</button>
                    </form>
                </div>
            </div>
            <!---------------------------------->

            <!-- Basic Data Tables -->
            <!--===================================================-->
            <div class="panel" style="background-color: #e8ddd3;">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">List of Department in
                        <strong>{{ \Auth::user()->branchname }}</strong>
                        (<i>{{ \Auth::user()->branchcode }}</i>)</h3>
                </div>
                <div class="panel-body" style="overflow:scroll">
                    <table id="demo-dt-basic" class="table table-striped table-bordered datatable" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Department Name</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($dept->isEmpty())
                                <h1>No department created</h1>
                            @else
                                <?php $count = 1; ?>
                                @foreach ($dept as $depts)
                                    <tr>
                                        <th>{{ $count }}</th>
                                        <td><strong>{{ strtoupper($depts->dept_name) }}</strong></td>
                                        <td>{{ \Carbon\Carbon::parse(substr($depts->created_at, 0, 10))->format('l, jS \\of F Y') }}
                                        </td>
                                        <td>
                                            <a class="btn btn-success btn-sm d-inline"
                                                href="{{ url('member.editdept', $depts->id) }}">Edit Department</a>
                                        </td>
                                    </tr>
                                    <?php $count++; ?>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!--===================================================-->
            <!-- End Striped Table -->


        </div>
        <!--===================================================-->
        <!--End page content-->

    </div>
    <!--===================================================-->
    <!--END CONTENT CONTAINER-->
@endsection
