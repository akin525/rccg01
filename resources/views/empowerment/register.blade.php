@extends('layouts.app')

@section('title')
    Empowerment Registration
@endsection

@section('link')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">

    <link href="{{ URL::asset('css/cam-style.css') }}" rel="stylesheet">
    <!-- inline style -->
    <style media="screen">
        .element {
            display: inline-flex;
            align-items: center;
        }

        i.fa-camera {
            margin: 10px;
            cursor: pointer;
            font-size: 30px;
        }

        i:hover {
            opacity: 0.6;
        }

        input {
            display: none;
        }
    </style>
@endsection
@section('content')

    <!--CONTENT CONTAINER-->
    <!--===================================================-->
    <div id="content-container">
        <div id="page-head">
            <!--Page Title-->
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <div id="page-title">
                <h1 class="page-header text-overflow">Empowerment Registration</h1>
            </div>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End page title-->
            <!--Breadcrumb-->
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            @if(Auth::user() != null)
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-home"></i><a href="{{ route('dashboard') }}"> Dashboard</a>
                    </li>
                    <li class="active">Registration</li>
                </ol>
            @endif
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <!--End breadcrumb-->
        </div>
        <!--Page content-->
        <!--===================================================-->
        <div id="page-content">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="panel rounded-top" style="background-color: #e8ddd3;">
                        <div class="panel-heading">
                            <h1 class="text-center" style="padding-top:5px">Empowerment Form</h2>
                        </div>
                        <div class="col-lg-10 col-lg-offset-2">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>
                        @php
                            $selectBranch = 'hello boy';
                            @endphp
                            <div class="row panel-body" style="background-color: #e8ddd3;">
                                <div class="" style="border:1pt solid #090c5e; border-radius:25px;">
                                    <form id="application-form" method="post" action="{{ Auth::user() != null ? route('empowerment.register') : route('empowerment.registration') }}"
                                          class="panel-body form-horizontal form-padding" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-6">
                                            <input type="text" id="referralId" name="referralId" value="{{ optional(\Auth::user())->branchname ?? $referrerId }}" readonly style="position:absolute; left:-9999px;">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="referralId">Branch</label>
                                                <div class="col-md-9">
                                                    <select id="referralId" name="referralId" class="selectpicker form-control" data-style="btn-success" required>
                                                        <option value="">Select</option>
                                                        <option value="1">
                                                            RCCG Christ Chapel
                                                        </option>
                                                        <option value="">
                                                            Rccg Calvary Worship Center
                                                        </option>
                                                        <option value="4">
                                                            New Covenant Pavilion
                                                        </option>
{{--                                                        @foreach($branches as $branch)--}}
{{--                                                            <option value="{{ $branch['id'] }}" {{ $branch['id'] == $selectBranch ? 'selected' : '' }}>--}}
{{--                                                                {{ $branch['branchname'] }}--}}
{{--                                                            </option>--}}
{{--                                                        @endforeach--}}
                                                    </select>
                                                </div>
                                            </div>

                                            <!--Text Input-->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="demo-text-input">Title</label>
                                                <div class="col-md-9">
                                                    <select name="title"
                                                            class="selectpicker col-xs-6 col-sm-4 col-md-6 col-lg-9"
                                                            style="padding-left:0px !important" data-style="btn-primary">
                                                        <option value="Mr">Mr</option>
                                                        <option value="Mrs">Mrs</option>
                                                        <option value="Miss">Miss</option>
                                                        <option value="Dr">Dr</option>
                                                        <option value="Pastor">Pastor</option>
                                                        <option value="Overseer">Overseer</option>
                                                        <option value="Bishop">Bishop</option>
                                                        <option value="Elder">Elder</option>
                                                        <option value="Dr (Mrs)">Dr (Mrs)</option>
                                                        <option value="Prof">Professor</option>
                                                        <option value="Engr">Engineer</option>
                                                        <option value="Surveyor">Surveyor</option>
                                                        <option value="Snr Pastor">Snr Pastor</option>
                                                        <option value="Evangelist">Evangelist</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!--Text Input-->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="demo-text-input">First Name</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="demo-text-input" name="firstname"
                                                           value="{{ old('firstname') }}" class="form-control"
                                                           placeholder="Firstname" required>

                                                </div>
                                            </div>
                                            <!--Text Input-->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="demo-text-input">Last Name</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="demo-text-input" name="lastname"
                                                           class="form-control" placeholder="Lastname" required>

                                                </div>
                                            </div>



                                            <!--Email Input-->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="demo-email-input">Email</label>
                                                <div class="col-md-9">
                                                    <input type="email" id="demo-email-input" class="form-control"
                                                           name="email" placeholder="Enter your email">
                                                    <!--small class="help-block">Please enter your email</small-->
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="demo-email-input">Phone
                                                    Number/ WhatsApp</label>
                                                <div class="col-md-9">
                                                    <input type="number" class="form-control" name="phone"
                                                           placeholder="Enter your phone number">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="demo-email-input">Age</label>
                                                <div class="col-md-9">
                                                    <input type="number" class="form-control" name="age"
                                                           placeholder="Enter your age">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="demo-email-input">Religion</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="religion"
                                                           placeholder="Enter your Religion">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label"
                                                       for="demo-textarea-input">Address</label>
                                                <div class="col-md-9">
                                                    <textarea id="demo-textarea-input" name="address" rows="5" class="form-control" placeholder=""
                                                              required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!--Text Input-->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="demo-text-input">Employment Status</label>
                                                <div class="col-md-9">
                                                    <select name="employment_status"
                                                            class="selectpicker col-xs-6 col-sm-4 col-md-6 col-lg-9"
                                                            data-style="btn-success" required>
                                                        <option value="">Select</option>
                                                        <option value="employed">Employed</option>
                                                        <option value="unemployed">Unemployed</option>
                                                        <option value="self-employed">Self-Employed</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="course">Course</label>
                                                <div class="col-md-9">
                                                    <select id="course" name="course" class="selectpicker form-control" data-style="btn-success" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label"
                                                       for="demo-textarea-input">Brief intro about yourself</label>
                                                <div class="col-md-9">
                                                    <textarea id="demo-textarea-input" name="intro" rows="5" class="form-control" placeholder=""
                                                              required></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group pad-ve">
                                                <label class="col-md-3 control-label">Sex</label>
                                                <div class="col-md-9">

                                                    <!-- Radio Buttons -->
                                                    <div class="radio">
                                                        <input id="demo-form-radio" class="magic-radio" value="male"
                                                               type="radio" name="sex" checked>
                                                        <label for="demo-form-radio">Male</label>
                                                        <input id="demo-form-radio-2" class="magic-radio" value="female"
                                                               type="radio" name="sex">
                                                        <label for="demo-form-radio-2">Female</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group pad-ver">
                                                <label class="col-md-3 control-label">Marital Status</label>
                                                <div class="col-md-9">
                                                    <div class="radio">
                                                        <!-- Inline radio buttons -->
                                                        <input id="demo-inline-form-radio" class="magic-radio" value="single"
                                                               type="radio" name="marital_status" checked>
                                                        <label for="demo-inline-form-radio">Single</label>

                                                        <input id="demo-inline-form-radio-2" class="magic-radio"
                                                               value="married" type="radio" name="marital_status">
                                                        <label for="demo-inline-form-radio-2">Married</label>
                                                    </div>
                                                </div>
                                            </div>

                                                <div class="col-md-9">
                                                    <span class=" pull-right">
                                                        <button id="submit" class="btn btn-info pull-center"
                                                                type="submit">REGISTER</button>
                                                    </span>
                                                </div>
                                            @if(Auth::user() != null)
                                                <div class="form-group" style="padding-top:50px">
                                                    <div class="col-md-9">
                                                    <span class=" pull-right">
                                                        @php
                                                            $referralCode = encrypt(Auth::user()->branchcode);
                                                            $referralLink = url('/empowerment/registration?ref=' . $referralCode);
                                                    @endphp
                                                    <input type="text" id="referralLink" value="{{ $referralLink }}" readonly style="position:absolute; left:-9999px;">
                                                    <button type="button" id="registerlink" class="btn btn-info pull-center"
                                                    >GENERATE EMPOWERMENT REGISTRATION LINK</button>
                                                </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--===================================================-->
                        <!-- END BASIC FORM ELEMENTS -->
                        <!--Default Bootstrap Modal-->
                        <!--===================================================-->
                        <div class="modal fade" id="demo-default-modal" role="dialog" tabindex="-1"
                             aria-labelledby="demo-default-modal" aria-hidden="true">
                            {{--                            <div class="modal-dialog">--}}
                            {{--                                <div class="modal-content">--}}

                            {{--                                    <!--Modal header-->--}}
                            {{--                                    <div class="modal-header">--}}
                            {{--                                        <button type="button" class="close" data-dismiss="modal"><i--}}
                            {{--                                                class="pci-cross pci-circle"></i></button>--}}
                            {{--                                        <h4 class="modal-title">Add a Relative</h4>--}}
                            {{--                                    </div>--}}


                            {{--                                    <!--Modal body-->--}}
                            {{--                                    <div class="modal-body">--}}

                            {{--                                        <div class="form-group">--}}
                            {{--                                            <label class="col-md-2 control-label" for="demo-email-input">Search--}}
                            {{--                                                Relative</label>--}}
                            {{--                                            <div class="col-md-10">--}}
                            {{--                                                <input type="text" id="search-relative-input" class="form-control"--}}
                            {{--                                                    name="name" placeholder="Enter relative Name">--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}

                            {{--                                        <div class="col-md-12" id="relatives-result-container"></div>--}}
                            {{--                                    </div>--}}

                            {{--                                    <!--Modal footer-->--}}
                            {{--                                    <div class="modal-footer">--}}
                            {{--                                        <button data-dismiss="modal" id="close-modal-btn" class="btn btn-default"--}}
                            {{--                                            type="button">Close</button>--}}
                            {{--                                        <button class="btn btn-primary">Save changes</button>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>
                        <div class="panel-footer panel-primary bg-dark">
                            <!-- Modal -->
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="fa fa-3x close" onclick="stopWebcam();"
                                                    data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Take a photo</h4>
                                        </div>
                                        <div class="modal-body">
                                            <!-- <h1>Take a snapshot of the current video stream</h1>
            Click on the Start WebCam button.
            <p>
            <button onclick="startWebcam();">Start WebCam</button>
            <button onclick="stopWebcam();">Stop WebCam</button>
             <button onclick="snapshot();">Take Snapshot</button>
            </p>
            <video onclick="snapshot(this);" width=400 height=400 id="video" controls autoplay></video> -->
                                            <div id="captured" class="" style="display:none">
                                                <h3 class="text-primary"> Screenshots : <h3>
                                                        <canvas id="myCanvas" width="400" height="350"></canvas>
                                            </div>

                                            <!--  -->
                                            <div id="container-cam">
                                                <button class="btn btn-warning" onclick="startWebcam();">Start
                                                    WebCam</button>
                                                <div id="vid_container">
                                                    <video id="video" autoplay playsinline></video>
                                                    <div id="video_overlay"></div>
                                                </div>
                                                <div id="gui_controls">
                                                    <button id="switchCameraButton" class="button" name="switch Camera"
                                                            type="button" aria-pressed="false"></button>
                                                    <button id="takePhotoButton" class="button" name="take Photo"
                                                            type="button"></button>
                                                    <button id="toggleFullScreenButton" class="button"
                                                            name="toggle FullScreen" type="button" aria-pressed="false"
                                                            style="display:none"></button>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button id="choose-img" type="button"
                                                    onclick="choose(canvas); stopWebcam();" class="btn btn-success"
                                                    data-dismiss="modal" style="display:none">Select Image</button>
                                            <button type="button" onclick="stopWebcam();" class="btn btn-default"
                                                    data-dismiss="modal">Close</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <!--===================================================-->
                        <!--End Default Bootstrap Modal-->
                    </div>
                </div>
            </div>
        </div>
        <!--===================================================-->
        <!--End page content-->
    </div>
    <!--===================================================-->
    <!--END CONTENT CONTAINER-->
    <!-- Notification -->

    <div id="copyNotice" style="display: none; margin-top: 10px; color: green; font-weight: bold;">
        ✅ Referral link copied to clipboard!
    </div>
    <!-- Bootstrap CSS (v3 or v4 depending on your layout) -->
{{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">--}}

    <!-- jQuery -->
{{--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}

    <!-- Bootstrap JS -->
{{--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>--}}
    <!-- Bootstrap Select CSS -->
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">--}}

    <!-- Bootstrap Select JS -->
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>--}}

@endsection

@section('js')
{{--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js"></script>--}}

    <script>
        $(document).ready(function () {
            // Bootstrap-select initializations
            $('#referralId').selectpicker();
            $('#course').selectpicker();

            const rawCoursesList = @json($courseslist);
            const courseslist = {};

            // Build the courses list as a map: branchId => [courses]
            rawCoursesList.forEach(entry => {
                courseslist[entry.branch.toString()] = entry.courses;
            });

            const selectedBranch = "{{ $selectBranch ?? '' }}";
            const selectedCourse = "{{ old('course') ?? '' }}";

            function populateCourses(branchId, selectedCourse = '') {
                const $courseSelect = $('#course');
                $courseSelect.empty();

                if (branchId && courseslist[branchId.toString()]) {
                    $courseSelect.prop('disabled', false);
                    $courseSelect.append('<option value="">Select Course</option>');

                    courseslist[branchId.toString()].forEach(course => {
                        const selected = (course === selectedCourse) ? 'selected' : '';
                        $courseSelect.append(`<option value="${course}" ${selected}>${course}</option>`);
                    });
                } else {
                    $courseSelect.prop('disabled', true);
                    $courseSelect.append('<option value="">First select a branch</option>');
                }

                // Refresh for bootstrap-select to re-render options
                // $courseSelect.selectpicker('refresh');
            }

            // On branch change
            $('#referralId').on('change', function () {
                const branchId = $(this).val();
                populateCourses(branchId);
            });

            // Populate courses if a branch is already selected (on page load)
            if (selectedBranch) {
                populateCourses(selectedBranch, selectedCourse);
            }
        });
    </script>


    <!-- jQuery -->
{{--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}

    <!-- Bootstrap Datepicker -->
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>--}}

    <script src="{{ URL::asset('js/cam/DetectRTC.min.js') }}"></script>
    <script src="{{ URL::asset('js/cam/adapter.min.js') }}"></script>
    <script src="{{ URL::asset('js/cam/screenfull.min.js') }}"></script>
    <script src="{{ URL::asset('js/cam/howler.core.min.js') }}"></script>
    <script src="{{ URL::asset('js/cam/main.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#anniversary').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        });
        $(document).ready(function () {
            $('#dob').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        });
        document.querySelectorAll('input[name="marital_status"]').forEach((el) => {
            el.addEventListener('change', function () {
                if (this.value === 'married') {
                    document.getElementById('wedding').style.display = 'block';
                } else {
                    document.getElementById('wedding').style.display = 'none';
                }
            });
        });
        document.getElementById('registerlink').addEventListener('click', function () {
            const linkInput = document.getElementById('referralLink');
            const linkText = linkInput.value;
            const notice = document.getElementById('copyNotice');

            // Try using the modern Clipboard API
            if (navigator.clipboard) {
                navigator.clipboard.writeText(linkText).then(() => {
                    showNotice();
                }).catch(err => {
                    fallbackCopy(linkInput);
                    showNotice();
                });
            } else {
                // Fallback for older browsers
                fallbackCopy(linkInput);
                showNotice();
            }

            function fallbackCopy(input) {
                input.select();
                input.setSelectionRange(0, 99999); // For mobile
                document.execCommand('copy');
            }

            function showNotice() {
                notice.style.display = 'block';
                setTimeout(() => {
                    notice.style.display = 'none';
                }, 3000);
            }
        });

        // function uploadImg() {
        //   var input = document.querySelector('input[type=file]');
        //   var file = input.files[0];
        //   var form = new FormData(),
        //       xhr = new XMLHttpRequest();
        // 	// form.append("filename", imageData);
        // 	// console.log(file);
        // 	console.log(blobs);
        // 	form.append('photo', blobs);
        //   // form.append('photo', file);
        //   form.append('_token', "{{ csrf_token() }}");
        //   xhr.open('post', "{{ route('member.upload.img') }}", true);
        //   xhr.send(form);
        // }
        $(document).ready(function() {
            // Upload file section
            // $("i").click(function () {
            //   $("input[type='file']").trigger('click');
            // });

            // $('input[type="file"]').on('change', function() {
            //   var val = $(this).val();
            //   $(this).siblings('span').text(val);
            // })

            //new
            var input = document.querySelector('input[type=file]'); // see Example 4

            input.onchange = function() {
                var file = input.files[0];

                // upload(file);
                drawOnCanvas(file); // see Example 6
                // displayAsImage(file); // see Example 7
            };

            // toggle member since date
            $('#member_since').change(function() {
                let today = new Date();
                let member_date = this.value;
                let lastWeek = Date.parse(new Date(today.getFullYear(), today.getMonth(), today.getDate() -
                    7));
                //check if date within 7 days
                //If nextWeek is smaller (earlier) than the value of the input date, alert...
                if (lastWeek > Date.parse(member_date)) {
                    $('#member_status').val('old');
                    $('#member_status').selectpicker('render');
                    $('#member_status_div').show();
                } else {
                    $('#member_status').val('new');
                    $('#member_status').selectpicker('render');
                    $('#member_status_div').show();
                }
            });

            // handle register form submission
            // $('#register-form').on('submit', (e) => {
            // 	e.preventDefault()
            // 	toggleAble('#submit', true, 'registering member...')
            // 	// let data = {}
            // 	let input = document.querySelector('#img-input')
            // 	data = $('#register-form').serializeArray()
            // 	//send to db route
            // 	$.ajax({url: "{{ route('member.register') }}", data, type: 'POST'})
            // 	.done((res) => {
            // 		if (res.status) {
            // 			swal("Success!", res.text, "success");
            // 			uploadImg()
            // 			resetForm('#register-form')
            // 			resetImgUpl()
            // 			toggleAble('#submit', false)
            // 		}else {
            // 			swal("Oops", res.text, "error");
            // 			toggleAble('#submit', false)
            // 		}
            // 	})
            // 	.fail((e) => {
            // 		swal("Oops", "Internal Server Error", "error");
            // 		toggleAble('#submit', false)
            // 		console.log(e);
            // 	})
            // })
        });
        let html = `<div class="form-group">
					<label class="col-md-3 control-label">Relative</label>
					<div class="col-md-9">
					<button id="add-relative-btn"  class="btn btn-danger"type="button">Add Relative</button>
					</div>
				</div>`;
        $('#add-relative-btn').on('click', function() {

            $('#open-modal-btn').trigger('click');


            //$('#add-relative-btn').parents('.form-group').after(html)
        })

        function remove_relative(id) {

            $(`#container_relative_${id}`).remove()
        }

        function add_relative(id, name) {
            $('#add-relative-btn').parents('.form-group').after(`<div class="form-group" id="container_relative_${id}">
					<label class="col-md-3 control-label">Added Relative</label>
					<div class="col-md-9">
	        <input  value="${name}" readonly>
	        <input name="relative_${id}" value="${id}" hidden=hidden>
					<select name="relationship_${id}" class="selectpicker" style="border:1px solid #ccc;display:inline !important;outline:none" data-style="btn-success" required>
					<option value="relative">Relationship</option>
						<option value="husband">Husband</option>
						<option value="wife">Wife</option>
						<option value="brother">Brother</option>
						<option value="sister">Sister</option>
						<option value="father">Father</option>
						<option value="mother">Mother</option>
						<option value="son">Son</option>
						<option value="daughter">Daughter</option>
					</select>
					<button  class="btn btn-xs btn-danger"type="button" onClick="remove_relative(${id})">Remove Relative</button>
					</div>
				</div>`)

            $('#close-modal-btn').trigger('click');
            $('#relatives-result-container').html('')
            $('#search-relative-input').val('')

        }
        $('#search-relative-input').on('keyup', function() {
            //alert('hello')
            $('#relatives-result-container').html(
                '<img class="center-block" width="50" height="50" src="../images/spinner.gif"/>')
            let search_term = $('#search-relative-input').val()
            $.ajax({
                url: `../get-relative/${search_term}`,

            }).done(function(data) {
                console.log(data.result)
                //console.log(typeof data)
                $('#relatives-result-container').html('')

                if (typeof data.result == 'string' || data.result.message) {
                    $('#relatives-result-container').html(
                        '<span style="height:50px" class="text-info">No result found</span>')
                    return
                }
                console.log(typeof data.result)
                for (let person in data.result) {
                    console.log(data.result[person])
                    let table = `<div class="col-md-12" style="margin-bottom:10px"><span class="text-info" style="margin-right:30px;width:100px !important">${data.result[person].firstname} ${data.result[person].lastname}</span> <button onClick="add_relative(${data.result[person].id},'${data.result[person].firstname} ${data.result[person].lastname}' )" type="button" class="btn-sm btn btn-info select-relativ
	e">Select Relative</button></div>
							`;
                    $('#relatives-result-container').append(table)
                }
            }).fail(function() {
                $('#relatives-result-container').html(
                    '<span style="height:50px" class="text-info">No result found</span>')
            })
        })

        $(document).ready(() => {
            init()
        })

        //--------------------
        // GET USER MEDIA CODE
        //--------------------
        navigator.getUserMedia = (navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);

        var video;
        var webcamStream;

        // function startWebcam() {
        // 	if (navigator.getUserMedia) {
        // 		 navigator.getUserMedia (
        //
        // 				// constraints
        // 				{
        // 					 video: true,
        // 					 audio: false
        // 				},
        //
        // 				// successCallback
        // 				function(localMediaStream) {
        // 						video = document.querySelector('video');
        // 					 video.src = window.URL.createObjectURL(localMediaStream);
        // 					 webcamStream = localMediaStream;
        // 				},
        //
        // 				// errorCallback
        // 				function(err) {
        // 					 console.log("The following error occured: " + err);
        // 				}
        // 		 );
        // 	} else {
        // 		 console.log("getUserMedia not supported");
        // 	}
        // }

        function stopWebcam() {
            // if (webcamStream) {
            //    webcamStream.getTracks().forEach(function (track) { track.stop(); });
            // }
            if (window.stream) {
                window.stream.getTracks().forEach(function(track) {
                    track.stop();
                });
            }
            // webcamStream.stop();
        }
        //---------------------
        // TAKE A SNAPSHOT CODE
        //---------------------
        var canvas, ctx;

        function init() {
            // Get the canvas and obtain a context for
            // drawing in it
            canvas = document.getElementById("myCanvas");
            ctx = canvas.getContext('2d');
        }

        function snapshot() {
            // Draws current image from the video element into the canvas
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        }
    </script>
@endsection
