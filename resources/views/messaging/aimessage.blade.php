@extends('layouts.app')

@section('title')
    All Members
@endsection

@section('link')
    <!--Bootstrap Datepicker [ OPTIONAL ]-->
    <link href="{{ URL::asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!--Bootstrap Select [ OPTIONAL ]-->
    <link href="{{ URL::asset('plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
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
                <h1 class="page-header text-overflow">AI Message</h1>
            </div>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End page title-->


            <!--Breadcrumb-->
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i><a href="{{ route('dashboard') }}"> Dashboard</a>
                </li>
                <li class="active">AI Message</li>
            </ol>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End breadcrumb-->

        </div>


        <!--Page content-->
        <!--===================================================-->
        <div id="page-content">
            <div class="row">
                @include('layouts.error')

                <div class="col-sm-6 col-sm-offset-3" style="margin-bottom:20px">
                    <div class="panel" style="background-color: #e8ddd3;">
                        <div class="panel-heading">
                            <h3 class="panel-title">AI Message Response</h3>
                        </div>
                        @if (isset($responseData) && $responseData['success'])
                        {{-- <p>{{ $responseData['message'] }}</p> --}}
                        <p> {{ $responseData['ai'] }}</p>
                    @endif
                    </div>

                </div>


                <div class="col-sm-6 col-sm-offset-3" style="margin-bottom:420px">
                    <div class="panel" style="background-color: #e8ddd3;">
                        <div class="panel-heading">
                            <h3 class="panel-title">AI Message</h3>
                        </div>
                        <!--Block Styled Form -->
                        <!--===================================================-->
                        <form method="POST" action="{{ route('message.aimessage') }}">
                            @csrf
                            <div class="panel-body">
                                <div class="row">

                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label">Message</label>
                                            <textarea name="message" class="form-control" style="height:300px" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button class="btn btn-success" type="submit">Get AI Message</button>
                            </div>
                        </form>
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
    <script src="{{ URL::asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('js/functions.js') }}"></script>
    <script>
        $('.datepicker').datepicker();
    </script>
    <!-- for email manual number input -->
    <script>
        var responseText = (obj) => {
            text = ''
            text += `${obj.pass.count} Sent ${obj.fail.count} Failed. Out Of ${obj.total} \n`
            text += (obj.fail.count > 0) ? `Failed Number(s): ${$.each(obj.fail.numbers,(v) => (`${v} `))} \n
   Failed Status: ${$.each(obj.fail.status,(v) => (`${v} `))}` : ''
            return text
        }
        // var dummyRes = {status: true, text: { pass: {status: [], count: 1}, fail: {status: [], count: 0, numbers: []}, total: 1}}
        $(document).ready(function() {
            $('#send-sms-form').submit((e) => {
                toggleAble($('#send-btn'), true, 'sending...')
                e.preventDefault();
                data = $('#send-sms-form').serializeArray()
                url = "{{ route('sendSMS') }}"
                poster({
                    data,
                    url,
                    alert: 'false'
                }, (res) => {
                    // res = dummyRes
                    if (res.status === true) {
                        text = responseText(res.text)
                        swal("Success", text, "success");
                    } else if (res.status === false) {
                        swal("Oops", res.text, "error");
                    }
                    toggleAble($('#send-btn'), false)
                    setBalance()
                    console.log(res);
                })
            })
            $('#add-num').click(function() {
                if (!$('#nums').val()) {
                    return;
                }
                var items = $('#nums').val().split(',');
                $.each(items, function(i, item) {
                    $('#nums').val('');
                    //$("#list").append('<li class="list-group-item d-flex justify-content-between align-items-center">'+ item +'  <span class="badge badge-danger badge-pill"><i onClick="rm_num(this);" class="btn fa fa-trash"></i></span></li>');
                    $('#num-selector').append($('<option>', {
                        value: item,
                        text: item,
                        selected: 'selected'
                    }, '</option>'));
                });
                var val = $('#num-selector').text().split(',');
                alert('Added ' + items);
                $('#num-selector').selectpicker('refresh');
                $.each(val, function(i, item) {});
            });

            //add group function
            $('#add-group').click(function() {
                //remove attribute on click
                $('#groups-selector').find(":selected").removeAttr("selected");
                var items = $('#groups-selector').find(":selected").map(function() {
                    return this.text;
                }).get();
                //do nothing if empty
                if (items.length == 0) {
                    return;
                }
                //transfer the groups
                var values = {
                    'group': items,
                    '_token': '{{ csrf_token() }}'
                };
                //get list of members in each group
                $.ajax({
                        type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                        url: "{{ route('group.members') }}", // the url where we want to POST
                        data: values, // our data object
                        dataType: 'json', // what type of data do we expect back from the server
                        encode: true
                    }) //<optgroup label="filter2">
                    // using the done promise callback
                    .done(function(data) {
                        if (data.status) {
                            let itemss = data.groupMember;
                            //append list to the emails
                            $.each(itemss, function(i, items) {
                                $('#num-selector').append($('<optgroup label="' + i +
                                    '"></optgroup>'));
                                $.each(items, function(ii, item) {
                                    //check if already in list
                                    let options = $("#num-selector option[value='" +
                                        item.phone +
                                        "'], #num-selector optgroup[value='" + item
                                        .phone + "']");
                                    if (options.length > 0) {
                                        $.each(options, function() {
                                            //delete email options
                                            $(this).remove();
                                        });
                                    }
                                    $('#num-selector optgroup[label="' + i + '"]')
                                        .append($('<option>', {
                                            value: item.phone,
                                            text: item.firstname + ' ' + item
                                                .lastname + ' - ' + item.phone,
                                            selected: 'selected'
                                        }, '</option>'));
                                });
                            });
                        } else {
                            alert('Error occured Please try again');
                        }
                        //clear the selectpicker
                        $('#groups-selector').find(":selected").removeAttr("selected");
                        $('#groups-selector').selectpicker('deselectAll');
                        $('#groups-selector').selectpicker('refresh');
                        $('#num-selector').selectpicker('refresh');
                        alert('Group Members Added');
                    });
            });

            // set the balance
            setBalance()

        });
        //selected="selected" value="' + item +'" >'+ item +'</option>'
        function rm_num(d) {
            var text = $(d).parent().parent().text();
            var input = $("#num-selector option[value='" + text + "']").remove();
            var ll = $('#list ' + d).remove();
        }

        var setBalance = async () => {
            // tell the user about to fetch sms balance
            // $('#sms_balance_container').html('<h3>Fetching sms Balance...</h3>')
            // fetch the sms balance api
            balanceUrl = await getSmsBalanceApi(async (url) => {
                if (url) {
                    // fetch the sms balance units
                    balance = await getBalance(url, (res) => {
                        if (!res) {
                            // tell the user
                            smsBalanceMessage(res)
                            return;
                        }
                        // display result to user
                        console.log(res);
                        smsBalanceMessage(res + 'Units')
                        $('#sms_balance_container').html(`<h3>${res} Units</h3>`)
                    })
                } else {

                }

            })
            // if not set
            // if (!balanceUrl) {
            // tell the user
            // $('#sms_balance_container').html('<h3>Api Not Set</h3>')
            // alert('Sms Balance Api Not Set')
            // return
            // }

            // // fetch the sms balance units
            // balance = await getBalance(balanceUrl)
            // if error fetching balance
            // if (!balance) {
            //   // tell the user
            //   $('#sms_balance_container').html(`<h3>${balance}</h3>`)
            //   return
            // }

            // // display result to user
            // $('#sms_balance_container').html(`<h3>${balance}</h3>`)
        }

        var getSmsBalanceApi = async (fn) => {
            let value = false
            $.get("{{ route('option.branch.get') }}")
                .done((res) => {
                    if (res.status) {
                        res.text.forEach((v) => {
                            if (v.name === 'smsbalanceapi') {
                                fn(v.value)
                            }
                        })
                    } else {
                        fn(false)
                    }
                })
                .fail((err) => {
                    fn(false);
                    console.log(err);
                })
        }

        var getBalance = (url, fn) => {
            value = false
            $.ajax({
                    url
                })
                .done((res) => {
                    if (res === '-2905') {
                        value = "Invalid username/password combination"
                        smsBalanceMessage("Invalid username/password combination")
                        fn(value)
                    } else {
                        value = res
                        fn(value)
                    }
                })
                .fail((err) => smsBalanceMessage())
            return value
        }

        const smsBalanceMessage = (msg = 'cannot fetch sms unit balance') => {
            $('#sms_balance_container').html(`<h3>${msg}</h3>`)
        }
    </script>
@endsection
