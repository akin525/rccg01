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
                            <div class="form-group">
                                <label class="control-label">Offering Type</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option>Select Offering Type</option>
                                    @foreach($offering_types as $offering_type)

                                        <option value="{{$offering_type['id']}}">{{$offering_type["name"]}}</option>

                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Name</label>
                                <input type="text" name="other_name" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Denomination</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody id="denomination-rows">
                                <!-- Each row will use array inputs for denomination, quantity, and total -->
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td><input type="text" id="grand-total" name="grand_total" readonly class="form-control" /></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                    <script>
                        // JavaScript for generating rows with denomination values
                        document.addEventListener("DOMContentLoaded", function () {
                            const denominations = [1000, 500, 200, 100, 50, 20, 10, 5, "Transfer"];
                            const denominationRows = document.getElementById("denomination-rows");

                            denominations.forEach(denomination => {
                                const row = document.createElement("tr");
                                if(denomination === "Transfer") {
                                    row.innerHTML = `
                                        <td>${denomination}</td>
                                        <td><input type="text" name="denominations[${denomination}][quantity]" class="form-control quantity-input" data-denomination="${denomination}"  /></td>
                                        <td><input type="text" name="denominations[${denomination}][total]" class="form-control total-input" readonly /></td>
                                    `;
                                }else {
                                    row.innerHTML = `
                                        <td>${denomination}</td>
                                        <td><input type="number" name="denominations[${denomination}][quantity]" class="form-control quantity-input" data-denomination="${denomination}" min="0" /></td>
                                        <td><input type="text" name="denominations[${denomination}][total]" class="form-control total-input" readonly /></td>
                                    `;
                                }
                                denominationRows.appendChild(row);
                            });

                            function calculateTotals() {
                                let grandTotal = 0;
                                document.querySelectorAll(".quantity-input").forEach(input => {
                                    const denomination = parseFloat(input.getAttribute("data-denomination"));
                                    const quantity = parseInt(input.value) || 0;
                                    var total = 0;
                                    if(isNaN(denomination)) {
                                        total = quantity;
                                    }else {
                                        total = denomination * quantity;
                                    }

                                    const totalCell = input.closest("tr").querySelector(".total-input");
                                    totalCell.value = total ? total.toFixed(2) : "";
                                    grandTotal += total;
                                });
                                document.getElementById("grand-total").value = grandTotal.toFixed(2);
                            }

                            document.querySelectorAll(".quantity-input").forEach(input => {
                                input.addEventListener("input", calculateTotals);
                            });

                            document.getElementById("type").addEventListener("change", () => {
                                document.querySelectorAll(".quantity-input").forEach(input => input.value = "");
                                document.querySelectorAll(".total-input").forEach(input => input.value = "");
                                document.getElementById("grand-total").value = "";
                            });
                        });
                    </script>


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
@endsection