@extends('layouts.app')
@section('header')
<title>province</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<style>
    .box {
        width: 500px;
        margin: 0 auto;
        border: 1px solid #ccc;
    }

    .button:active {
        background-color: #3e8e41;
        box-shadow: 0 5px #666;
        transform: translateY(4px);
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="card">
        <div class="card-body">
            <h3>Dropdown</h3>
            <form>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="province">จังหวัด</label>
                        <select name="province_id" id="province" class="form-control province">
                            <option value="">เลือกจังหวัดของท่าน</option>
                            @foreach ($list as $row)
                            <option value="{{ $row->id }}">{{$row->name_th}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="amphure">อำเภอ</label>
                        <select name="amphure_id" id="amphure" class="form-control amphures">
                            <option value="">เลือกอำเภอของท่าน</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="district">ตำบล</label>
                        <select name="district_id" id="districts" class="form-control districts">
                            <option value="">เลือกตำบลของท่าน</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <form method="get" action="{{ url('/fullcalendareventmaster') }}">
                        <button id="button" type="submit" class="btn btn-success button">Success</button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
{{ csrf_field()}}

<div class="container">
    <div class="response"></div>
    <div id='calendar'></div>
</div>
@endsection

@section('foot_script')
<!-- dropdown -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.province').change(function() {
            if ($(this).val() != '') {
                var select = $(this).val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dropdown.fetchAmphures')}}",
                    method: "POST",
                    data: {
                        select: select,
                        _token: _token
                    },
                    success: function(result) {
                        $('.amphures').html(result);
                    }
                })
            }
        });


    });

    $(document).ready(function() {
        $('.amphures').change(function() {
            if ($(this).val() != '') {
                var select = $(this).val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dropdown.fetchDistricts')}}",
                    method: "POST",
                    data: {
                        select: select,
                        _token: _token
                    },
                    success: function(result) {
                        $('.districts').html(result);
                    }
                })
            }
        });
    });




    // calendar
    jQuery(document).ready(function($) {
        var SITEURL = "{{url('/')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var calendar = $('#calendar').fullCalendar({
            editable: true,
            events: SITEURL + "/fullcalendareventmaster",
            displayEventTime: true,
            editable: true,
            eventRender: function(event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                var title = prompt('Event Title:');
                if (title) {
                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                    $.ajax({
                        url: SITEURL + "/fullcalendareventmaster/create",
                        data: 'title=' + title + '&start=' + start + '&end=' + end,
                        type: "POST",
                        success: function(data) {
                            displayMessage("Added Successfully");
                        }
                    });
                    calendar.fullCalendar('renderEvent', {
                            title: title,
                            start: start,
                            end: end,
                            allDay: allDay
                        },
                        true
                    );
                }
                calendar.fullCalendar('unselect');
            },
            eventDrop: function(event, delta) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                $.ajax({
                    url: SITEURL + '/fullcalendareventmaster/update',
                    data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                    type: "POST",
                    success: function(response) {
                        displayMessage("Updated Successfully");
                    }
                });
            },
            eventClick: function(event) {
                var deleteMsg = confirm("Do you really want to delete?");
                if (deleteMsg) {
                    $.ajax({
                        type: "POST",
                        url: SITEURL + '/fullcalendareventmaster/delete',
                        data: "&id=" + event.id,
                        success: function(response) {
                            if (parseInt(response) > 0) {
                                $('#calendar').fullCalendar('removeEvents', event.id);
                                displayMessage("Deleted Successfully");
                            }
                        }
                    });
                }
            }
        });
    });

    function displayMessage(message) {
        $(".response").html("" + message + "");
        setInterval(function() {
            $(".success").fadeOut();
        }, 1000);
    }

// hide calendar
    $(document).ready(function() {
        $("#calendar").hide()
        $("#districts").change(function() {
            if ($("#districts").val() != "") {
                $("#calendar").show()
            }
        });

    });
</script>
@endsection