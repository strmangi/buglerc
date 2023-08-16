@extends(".admin.layout.master")
@section("title", "Bugler | Assign Driver")
@section("page_css")
<link rel="stylesheet" href="{{ asset('admin_asset/dist/css/form_validation.css')}}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://www.jqueryscript.net/demo/Date-Time-Picker-Bootstrap-4/build/css/bootstrap-datetimepicker.min.css">
@endsection
@section("body")

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Assign Driver</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>
            <li class="breadcrumb-item active">Assign Driver</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Assign Driver</h3>

        <a href="{{url('admin/inquiry')}}" class="btn btn-info text-light float-sm-right">
          < Back</a>
      </div>

      <div class="card-body">
        <?php
        if ($details->is_school) {
          $trip_end_time = $details->school_close_date . ' ' . $details->job_end_time;
          $trip_ending_time = strtotime($trip_end_time);
          $reportingtime = $details->school_start_date . ' ' . $details->report_time;
          $pickuptime = $details->school_start_date . ' ' . $details->pick_up_time;
        } else {
          $reportingtime = '';
          $pickuptime = '';
          $trip_end_time = date('d-m-Y H:i:s', $details->trip_ending_time);
          $trip_ending_time = $details->trip_ending_time;
        }

        ?>
        <input type="hidden" class="etime" value="<?php echo $trip_ending_time; ?>">
        <div id="error_message"></div>
        <form role="form" id="validate_form" method="post" enctype="multipart/form-data">
          {{csrf_field()}}
          <div class="card-body col-sm-8 field_div">
            <div class="form-group">
              <label>TRIP<span class="text-danger">*</span></label>
              <select class="form-control" name="trip" required="" id="trip" readonly>
                @if(count($trips)>0)
                <option value="{{$trips[0]->inq_id}}">{{$trips[0]->pick_up_point.' -to- '.$trips[0]->destination.' || '.date('d-M-y', strtotime($trips[0]->trip_start_date)).' || pick up time : '.$trips[0]->pick_up_time}}</option>
                @else
                <option value="">Trip not found</option>
                @endif
              </select>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label>Reporting Time<span class="text-danger">*</span></label>
                  <input type="text" name="reporting_time" class="form-control reporting_time" id="reporting_time" data-parsley-required="true" data-parsley-trigger="change" value="{{ date('d-m-Y H:i', strtotime($details->trip_start_date)) }}">
                </div>
              </div>

              <div class="col-sm-4">
                <div class="form-group">
                  <label>Departure Time<span class="text-danger">*</span></label>
                  <input type="text" name="departure_time" class="form-control ending_time" id="ending_time" data-parsley-required="true" data-parsley-trigger="change" value="{{ date('d-m-Y H:i', strtotime($details->trip_start_date)) }}">
                  {{--<input type="text" name="departure_time" class="form-control ending_time" id="ending_time" data-parsley-required="true" data-parsley-trigger="change" value="{{$details->return_time}}"> --}}
                </div>
              </div>

              <div class="col-sm-4">
                <div class="form-group">
                  <label>Trip End Time<span class="text-danger">*</span></label>
                  <input type="text" name="end_time" disabled="disabled" class="form-control trip_end_time" id="trip_end_time" data-parsley-required="true" data-parsley-trigger="change" value="{{ $trip_end_time }}">
                </div>
                <input type="hidden" name="trip_end_timestamp" value="{{ $trip_ending_time }}">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label for=""></label>

                  <button class="btn btn-info getdc" type="button" id="checkavailablity">Click to check Coaches and Driver Availabllity</button>

                </div>
              </div>

            </div>
            @php
            $j =1;
            @endphp
            @foreach($trips as $trip)
            @if($trip->no_of_coach > 1)

            @for($i = 1; $i <= $trip->no_of_coach; $i++)
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Coach Type<span class="text-danger">*</span></label>
                    <select class="form-control" name="coach_type[]" required="" id="coachType_{{$j}}">
                      <option value="{{$trip->coachTypeId}}" selected="">{{$trip->type}}</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Available Coach<span class="text-danger">*</span></label>
                    <select class="form-control selectCoach" name="coach[]" required="" id="coach_{{$j}}">
                      <option value="">-select-</option>
                      {{-- @foreach($coachesList as $coach)
                                <option value="{{$coach['id']}}">{{$coach['coach_name'].' || '.$coach['registration_no']}}</option>
                      @endforeach --}}
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Available Driver<span class="text-danger">*</span></label>
                    <select class="form-control driver" name="driver[]" required="" id="driver_{{$j}}">
                      <option value="" selected="">-select-</option>
                      {{-- @foreach($drivers as $driver)
                              <option value="{{$driver->id}}">{{$driver->name.' || '.$driver->email}}</option>
                      @endforeach --}}
                    </select>
                  </div>
                </div>
              </div>
              @php $j++; @endphp
              @endfor
              @else
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Coach Type<span class="text-danger">*</span></label>
                    <select class="form-control" name="coach_type[]" required="" id="coachType_{{$j}}">
                      <option value="{{$trip->coachTypeId}}" selected="">{{$trip->type}}</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Available Coach<span class="text-danger">*</span></label>
                    <select class="form-control selectCoach" name="coach[]" required="" id="coach_{{$j}}">
                      <option value="">-select-</option>
                      @foreach($coaches as $coach)
                      <option value="{{$coach->id}}">{{$coach->coach_name.' || '.$coach->registration_no}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">

                  <div class="form-group">
                    <label>Available Driver<span class="text-danger">*</span></label>
                    <select class="form-control driver" name="driver[]" required="" id="driver_{{$j}}">
                      <option value="" selected="">-select-</option>
                      @foreach($drivers as $driver)
                      <option value="{{$driver->user_id}}">{{$driver->name.' || '.$driver->email}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              @php $j++; @endphp
              @endif
              @endforeach
              <div class="row">
                <div class="col-sm-4" style="display: none">
                  <div class="form-group">
                    <label>slot</label>


                    <div class="radio">
                      <label><input type="radio" name="slot_time" value="Morning only trip"> Morning only trip</label>
                    </div>

                    <div class="radio">
                      <label><input type="radio" name="slot_time" value="Afternoon only trip"> Afternoon only trip</label>
                    </div>

                  </div>
                </div>
              </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <button type="btn" name="submit" id="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">

      </div>
      <!-- /.card-footer-->
    </div>
    <!-- /.card -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section("page_script")

<script src="https://parsleyjs.org/dist/parsley.js"></script>
<!-- bs-custom-file-input -->
<script src="{{asset('admin_asset/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script>
  // Available Driver
  $(document).ready(function() {

    $("#checkavailablity").click(function() {
      console.log("Check Availavle Driver");
      var reportingTime = $('.reporting_time').val();
      var endingTime = $('.ending_time').val();
      // var trip_end_time = <?php echo $details->trip_ending_time; ?>;
      var trip_end_time = $('.etime').val();
      if (reportingTime && endingTime && trip_end_time) {



        // Check Driver
        $.ajax({
          url: '{{route('admin.driver.list')}}',
          type: 'post',
          data: {
            "_token": "{{ csrf_token() }}",
            strtingTime: reportingTime,
            endingTime: endingTime,
            trip_end_time: trip_end_time
          },
          dataType: 'json',
          success: function(response) {
            var len = response.length;

            $(".driver").empty();
            for (var i = 0; i < len; i++) {

              var id = response[i].user_id;
              var name = response[i].name;
              var email = response[i].email;
              $(".driver").append("<option value='" + id + "'>" + name + '|' + email + "</option>");

            }
          }
        });

        $.ajax({
          url: '{{route('admin.coach.list')}}',
          type: 'post',
          data: {
            "_token": "{{ csrf_token() }}",
            strtingTime: reportingTime,
            endingTime: endingTime,
            trip_end_time: trip_end_time
          },
          dataType: 'json',
          success: function(response) {

            var len = response.length;

            $(".selectCoach").empty();
            for (var i = 0; i < len; i++) {

              var id = response[i].id;
              var registration_no = response[i].registration_no;
              var name = response[i].coach_name;


              $(".selectCoach").append("<option value='" + id + "'>" + registration_no + '||' + name + "</option>");

            }
          }
        });
      }
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {


    //--bootstrap custom file----
    bsCustomFileInput.init();

    //====== form validate and submit start=======
    $('#validate_form').on('submit', function(event) {
      console.log("Validating Form..");
      event.preventDefault();

      $('#validate_form').parsley();
      if ($('#validate_form').parsley().isValid()) {
        $.ajax({
          url: '{{route('admin.driver.assign.store')}}',
          method: "POST",
          data: new FormData(this),
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function() {
            $('#submit').attr('disabled', 'disabled');
            $('#submit').val('Submitting...');
          },
          success: function(data) {
            //console.log(data);

            $('#validate_form')[0].reset();
            $('#validate_form').parsley().reset();
            $('#submit').attr('disabled', false);
            $('#submit').val('Submit');
            if (data.success) {
              // errorsHtml = '<div class="alert alert-success"><ul>';
              // $.each(data.success,function (k,v) {
              //        errorsHtml += '<li>'+ v + '</li>';
              // });
              window.location.href = '{{route("admin.assign.trip")}}';
              // errorsHtml += '</ul></di>';
              // $('#error_message').html(errorsHtml);
              // //appending to a <div id="error_message"></div> inside form 
              // $('#error_message').hide(3000);
            } else {
              errorsHtml = '<div class="alert alert-danger"><ul>';
              $.each(data.error, function(k, v) {
                errorsHtml += '<li>' + v + '</li>';
              });
              errorsHtml += '</ul></di>';
              $('#error_message').html(errorsHtml);
              //appending to a <div id="error_message"></div> inside form 
            }
          }
        });
      }
    });
    //====== form validate and submit end=======
  });
  //---document ready end----

  //=======check unique coach====
  $('select.selectCoach').change(function() {
    if ($('select.selectCoach option[value="' + $(this).val() + '"]:selected').length > 1) {
      $(this).val('-1').change();
      alert('You have already selected this coach previously - please choose another.')
    }
  });

  //=======check unique driver====
  $('select.driver').change(function() {
    if ($('select.driver option[value="' + $(this).val() + '"]:selected').length > 1) {
      $(this).val('-1').change();
      alert('You have already selected this driver previously - please choose another.')
    }
  });
</script>


@endsection