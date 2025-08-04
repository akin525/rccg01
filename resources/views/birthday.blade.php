@extends('layouts.app')

@section('title')
   Birthdays
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
                <h1 class="page-header text-overflow">Birthdays For the Current Month</h1>
            </div>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End page title-->

            <!--Breadcrumb-->
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i><a href="{{ route('dashboard') }}"> Dashboard</a>
                </li>
                <li class="active">Birthday</li>
            </ol>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End breadcrumb-->

        </div>

        @php
            $currentMonth = request('month', date('m'));

            $todayWeek = now()->weekOfYear;
            $todayYear = now()->year;

            if ($currentMonth == 12){
                $nextWeek = 1;
            }else{
            $nextWeek = $currentMonth + 1;
            }
            if ($currentMonth == 1){
                $previousWeek = 12;
            }else{
            $previousWeek = $currentMonth - 1;
            }
//            $showNext = !($currentWeek == $todayWeek && $currentYear == $todayYear);
        @endphp

        <!--Page content-->
        <!--===================================================-->
        <div id="page-content">
            @include('layouts.error')

            <div class="col-md-12 col-md-offset-0 col-lg-8 col-lg-offset-1" style="margin-bottom:20px">
                <div class="panel bg-warning rounded-top" style="overflow:scroll; background-color: #e8ddd3;">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center"><strong>
                                <i>Birthdays For the Month of {{ date('F', mktime(0, 0, 0, $currentMonth, 10)) }}</i></strong></h3>
                    </div>

                    <div class="row text-center mb-3">
                        <div class="form-group" style="margin-left: 20px;">
                            <a href="{{ route('birthday', ['month' => $previousWeek]) }}" class="btn btn-warning">Previous</a>

{{--                            @if($showNext)--}}
                                <a href="{{ route('birthday', ['month' => $nextWeek]) }}" class="btn btn-success" style="margin-left: 10px;">Next</a>
{{--                            @endif--}}
                        </div>
                    </div>

                    @if (!count($members) > 0)
                        <div class="col-12 well text-center bg-danger">
                            <div class="text-lg">
                                <div class="col-8">
                                    Oooops! No Member has birthday this month
                                </div>
                                {{-- <div class="col-4">
                                    <a class="btn btn-info" href="{{ route('branch.tools') }}">Add Service Type</a>
                                </div> --}}
                            </div>
                        </div>
                    @else
                        <div class="panel-body">
                            <form id="m-attendance" action="{{ route('attendance.mark') }}" method="post">
                                @csrf
                                <table id="mTable" class="table table-striped table-bordered datatable text-dark"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Title</th>
                                            <th>Member Name</th>
                                            <th>Birthday</th>
                                            {{-- <th><input id="select-all" type="checkbox" />Mark All</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; ?>
                                        <?php $class = ['normal', 'alt'];
                                        $i = 0;
                                        $size = sizeof($members); ?>
                                        @foreach ($members as $member)
                                            <?php if ($i == 1) {
                                                $num = 0;
                                                $i = 0;
                                            } else {
                                                $num = 1;
                                                $i = 1;
                                            } ?>
                                            <tr class="<?php echo $class[$num]; ?>" id="row,{{ $count }}">
                                                <td><strong>{{ $count }}</strong></td>
                                                <td>{{ $member->title }}
                                                <td>{{"" .$member->firstname ." " .$member->lastname }}
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($member->dob)->format('jS \of M Y') }}
                                                        {{-- <br>
                                                        {{ $member->dob }} --}}
                                                    </td>
                                            </tr>
                                            <?php $count++; ?>
                                        @endforeach

                                    </tbody>
                                </table>
                            </form>
                        </div>
                    @endif
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
    <script>
        $(document).ready(() => {
            //for bulk delete
            $('#select-all').click(function() {
                console.log('s');
                if (this.checked) {
                    $('input[name=atte]').each(function() {
                        this.checked = true;
                    });
                } else {
                    $('input[name=atte]').each(function() {
                        this.checked = false;
                    });
                }
            });

            //configure member table
            if ($.fn.dataTable.isDataTable('.datatable')) {
                table = $('#mTable').DataTable()
            } else {
                var table = $('.datatable').DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    "paging": false,
                    buttons: ['colvis']
                });
                table.buttons().container()
                    .appendTo($('div.eight.column:eq(0)', table.table().container()));
            }
            // Branch Attendnace
            $('#b-attendance-form').submit((e) => {
                toggleAble($('#btn-mark'), true, 'submitting...')
                e.preventDefault()
                data = $('#b-attendance-form').serializeArray()
                url = "{{ route('attendance.submit') }}"
                poster({
                    url,
                    data
                }, (res) => {
                    if (res.status) {
                        // toggleAble($('#btn-mark'), false)
                        $('#b-attendance-form').trigger('reset')
                    }
                    toggleAble($('#btn-mark'), false)
                })
            })
            //member Attendnace
            $(":checkbox").change(function() {
                if ($(this).is(':checked')) {
                    $(this).next().val('yes');
                } else {
                    $(this).next().val('no');
                }
            });
            $('#m-attendance').submit((e) => {
                toggleAble($('#m-submit-btn'), true, 'marking...')
                e.preventDefault()
                let data = $('#m-attendance').serializeArray()
                url = "{{ route('attendance.mark') }}"
                poster({
                    url,
                    data
                }, (res) => {
                    if (res.status) {
                        // location.reload()
                        $('#m-attendance').trigger('reset')
                    }
                    toggleAble($('#m-submit-btn'), false)
                })
            })
        })
    </script>
@endsection
