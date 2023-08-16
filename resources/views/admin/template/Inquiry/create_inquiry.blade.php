

@extends(".admin.layout.master")

@section("title", "Bugler | Create Enquiry")

@section("page_css")

<link rel="stylesheet" href="{{ asset('admin_asset/dist/css/form_validation.css')}}">

{{-- select2 --}}

<link rel="stylesheet" href="{{ asset('admin_asset/plugins/select2/css/select2.min.css')}}">

<link rel="stylesheet" href="{{ asset('admin_asset/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">



  <!-- iCheck for checkboxes and radio inputs -->

  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

@endsection

@section("body")

<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>Create Enquiry</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>

              <li class="breadcrumb-item active">Create Enquiry</li>

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

          <h3 class="card-title">Create Enquiry</h3>

          <a href="{{url('admin/inquiry')}}" class="btn btn-info text-light float-sm-right">< Back</a>

        </div>

        <div class="card-body">

          <div id="error_message"></div>

          <form role="form" id="validate_form" method="post" enctype="multipart/form-data" data-parsley-validate>

              {{csrf_field()}}

              <input type="hidden" name="is_school" value="{{ $status }}">

                <div class="card-body col-sm-10">

                  <h3>Customer Info:</h3>

                  <br>

                  <h4><u>For Existing Customers</u></h4>

                  <span class="text-danger">*</span >

                  <span><i>Choose existing customer</i></span>

                  <div class="row">

                    <div class="form-group col-sm-6">

                      <div class="form-group">

                        <label>Choose Customer</label>

                        <select class="form-control select2bs4 is_selected" name="customer_id" style="width: 100%;">

                          <option value="">-Choose Customer-</option>

                          @foreach($customers as $customer)

                            <option value="{{$customer->id}}">{{$customer->name.' || '.$customer->email}} </option>

                          @endforeach

                        </select>

                      </div>

                    </div>

                  </div>

                  <h4>For New Customers</h4>

                  <span class="text-danger">*</span>

                  <span><i>Complete customer details only if the customer does not exist in in the list above.</i></span>

                  <div class="row">

                    <div class="form-group col-sm-6">

                      <div class="form-group">

                        <label for="customer_name">Name</label>

                        <input type="text" class="form-control is_requires" name="customer_name" placeholder="Enter Name" data-parsley-required="true" data-parsley-trigger="change">

                      </div>

                    </div>

                     <div class="form-group col-sm-6">

                      <div class="form-group">

                        <label for="customer_name"> Contact Name</label>

                        <input type="text" class="form-control is_requires" name="contact_name" placeholder="Enter Contact Name" data-parsley-required="true" data-parsley-trigger="change">

                      </div>

                    </div>

                    <div class="form-group col-sm-6">

                      <div class="form-group">

                        <label for="email">Email</label>

                        <input type="text" class="form-control is_requires" name="email" placeholder="Enter Email" data-parsley-required="true" data-parsley-trigger="change">

                      </div>

                    </div>

                    <div class="form-group col-sm-6">

                      <div class="form-group">

                        <label for="phone">Phone</label>

                        <input type="text" class="form-control is_requires" name="phone" placeholder="Enter Phone" data-parsley-required="true" data-parsley-trigger="change">

                      </div>

                    </div>

                    <div class="form-group col-sm-6">

                      <div class="form-group">

                        <label for="address">Address</label>

                        <textarea name="address" id="" rows="2" class="form-control is_requires" placeholder="Enter Address" data-parsley-required="true" data-parsley-trigger="change"></textarea>

                      </div>

                    </div>



                  </div>



               <hr>

                  <br>

                  <h3>Trip Info:</h3>



                  <div class="row">

                    <div class="form-group col-sm-6">

                      <label for="Pickup_point">Pickup Point<span class="text-danger">*</span></label>

                      <input type="text" class="form-control" id="Pickup_point" name="Pickup_point"  placeholder="Enter Pickup Point" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change">

                    </div>



                    <div class="form-group col-sm-3">

                      <label for="report_time">Reporting Time<span class="text-danger">*</span></label>

                      <input type="time" class="form-control" id="report_time" name="report_time"  placeholder="Enter Reporting Time" data-parsley-required="true" data-parsley-trigger="change">

                    </div>

                    <div class="col-sm-3"> 

                      <div class="custom-control custom-checkbox" style="margin-top: 37px; margin-left: 50px;">

                        <input type="checkbox" class="custom-control-input pick-check" id="defaultUnchecked" name="Pickup_time_not_fix" value="not_fix">

                        <label class="custom-control-label" for="defaultUnchecked">Or Not Sure</label>

                      </div>

                    </div>



                    <div class="form-group col-sm-6">

                      <label for="Destination">Destination<span class="text-danger">*</span></label>

                      <input type="text" class="form-control" id="Destination" name="Destination"  placeholder="Enter Destination" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change">

                    </div>

                    <div class="form-group col-sm-3">

                      <label for="Trip_Start_Date">Trip Start Date<span class="text-danger">*</span></label>

                      <input type="date" class="form-control" id="Trip_Start_Date" name="Trip_Start_Date"  placeholder="Enter Pickup Time" data-parsley-required="true" data-parsley-trigger="change">

                    </div>

                      <div class="form-group col-sm-3">

                      <label for="Pickup_time">Pickup Time<span class="text-danger">*</span></label>

                      <input type="time" class="form-control" id="Pickup_time" name="Pickup_time"  placeholder="Enter Pickup Time" data-parsley-required="true" data-parsley-trigger="change">

                    </div>


                  </div>

                  <div class="row">

                    <div class="form-group col-sm-4">

                      <label for="return_date">Return Date<span class="text-danger">*</span></label>

                      <input type="date" class="form-control" id="return_date" name="return_date"  placeholder="Enter Destination" data-parsley-required="true" data-parsley-trigger="change">

                    </div>

                    <div class="form-group col-sm-4">

                      <label for="Return_time">Return Time<span class="text-danger">*</span></label>

                      <input type="time" class="form-control" id="return_time" name="return_time"  placeholder="Enter Return Time" data-parsley-required="true" data-parsley-trigger="change">

                    </div>

                    <div class="col-sm-4"> 

                      <div class="custom-control custom-checkbox" style="margin-top: 37px; margin-left: 50px;">

                        <input type="checkbox" class="is_one_way" id="is_one_way" name="is_one_way" value="1">

                        <label class="custom-control-label-1" for="defaultUnchecked">Is One Way</label>

                      </div>

                    </div>

                    <div class="form-group col-sm-4">

                      <label for="return_date">Total Trip Mileage<span class="text-danger">*</span></label>

                      <input type="text" class="form-control" value="1" id="total_mileage" name="total_mileage" data-parsley-required="true" data-parsley-trigger="change" data-parsley-type="digits" onkeyup="recaculation()" >

                    </div>

                    <div class="form-group col-sm-4">

                      <label for="driver_hours">Number of Driver hours<span class="text-danger">*</span></label>

                      <input type="text" class="form-control" value="1" id="driver_hours" name="driver_hours" data-parsley-required="true" data-parsley-trigger="change" step="0.1" data-parsley-pattern="/^[+]?([1-9][0-9]*(?:[\.][0-9]*)?|0*\.0*[1-9][0-9]*)(?:[eE][+-][0-9]+)?$/" onkeyup="recaculation()">

                    </div>

                    <div class="form-group col-sm-3">

                      <label for="trip_end_date">Trip End Date<span class="text-danger">*</span></label>

                      <input type="date" class="form-control" id="trip_end_date" name="trip_end_date"  placeholder="Enter End Date" data-parsley-required="true" data-parsley-trigger="change">

                    </div>

                     <div class="form-group col-sm-4">

                      <label for="job_end_time">Job End Time<span class="text-danger">*</span></label>

                      <input type="time" class="form-control" id="job_end_time" name="job_end_time"  placeholder="Enter Job End Time" data-parsley-required="true" data-parsley-trigger="change">

                    </div>





                  </div>

                  



                  <div class="row">

                    <div class="col-sm-11" id="coach1">

                    <div class="row coach_data" style="border: 2px solid #c3c3c3;">

                      <div class="form-group col-sm-4">

                        <label>Coach Type</label>

                        <select class="form-control coach_type" name="coach_type[]" required="" id="coachType_1" onchange="coachType()">

                          <option value="" selected>-Choose-</option>

                          @if(count($coach_types)>0)

                          @php

                           $i = 1;

                          @endphp

                          @foreach($coach_types as $type)

                            <option value="{{$type['id']}}" >{{$type['type']}}</option>

                          @endforeach

                          @endif

                        </select>

                      </div>

                      <div class="form-group col-sm-4">

                        <label for="no_of_coaches">Number of Coaches</label>

                        <input type="text" class="form-control" value="1" id="noOfCoaches_1" name="no_of_coaches[]" data-parsley-trigger="keyup"  min="1" max="100" step="1" data-parsley-required="true" data-parsley-validation-threshold="1" data-parsley-type="digits" data-parsley-type-message="Please enter numeric value like 10" placeholder="Enter Number of Coaches" onkeyup="recaculation()">

                      </div>



                      <div class="form-group col-sm-4">

                        <label for="CostPerMile">Cost Per Mile</label>

                        <input type="text" value="" class="form-control CostPerMile" id="CostPerMile_1" name="CostPerMile[]"  placeholder="Enter Cost Per Mile" data-parsley-trigger="keyup" data-parsley-required="true" data-parsley-pattern="^[0-9]*\.[0-9]{2}$" data-parsley-pattern-message="Please enter numeric value like 10.00" onkeyup="coachType()" readonly="">

                      </div>

                      <div class="form-group col-sm-4">

                        <label for="no_of_days">Number of Days</label>

                        <input type="text"  class="form-control" value="1" id="noOfDays_1" name="no_of_days[]" data-parsley-trigger="keyup"  min="1" max="100" step="100" data-parsley-validation-threshold="1" data-parsley-required="true" data-parsley-type="digits" data-parsley-type-message="Please enter numeric value like 3"  placeholder="Enter Number of Days" onkeyup="recaculation()">

                      </div>

                      <div class="form-group col-sm-4">

                        <label for="cost_for_day">Cost Per Days</label>

                        <input type="text" class="form-control costPerDay" id="costForDay_1" name="cost_for_day[]"  placeholder="Enter Cost for Day" data-parsley-trigger="keyup" data-parsley-required="true" data-parsley-pattern="^[0-9]*\.[0-9]{2}$" data-parsley-pattern-message="Please enter numeric value like 10.00" onkeyup="coachType()" readonly="">

                      </div>

                      <div class="form-group col-sm-4">

                        <label for="cost_for_driver">Driver hire cost per hour </label>

                        <input type="text" class="form-control costPerDriver" id="costForDriver_1" name="cost_for_driver[]"  placeholder="Enter Cost for Day" data-parsley-trigger="keyup" data-parsley-required="true" data-parsley-pattern="^[0-9]*\.[0-9]{2}$" data-parsley-pattern-message="Please enter numeric value like 10.00" onkeyup="coachType()" readonly="">

                      </div>



                      <div class="form-group col-sm-4">

                        <label for="total_charge_per_coach">Total Charge for This Coach</label>

                        <input type="text" class="form-control" step="0.01" id="totalChargePerCoach_1" name="total_charge_per_coach[]"  placeholder="Enter total charge per coach" readonly="">

                      </div>

                    </div>

                    </div>

                    <div class="col-sm-1">

                      <button type="button" name="add" id="add" class="btn btn-success" style="border-radius: 19px; margin-top: 63px;margin-left: 58px;"><i class="fas fa-plus"></i></button>

                    </div>

                  </div>

                  <br>

                 <div id="divForAppend"></div>

                  <div class="row">

                    <div class="form-group col-sm-4">

                      <label for="supplemental_costs">Supplemental costs(if any)</label>

                      <input type="text" class="form-control" id="supplemental_costs_1" name="supplemental_costs_1"  placeholder="Supplemental costs" data-parsley-trigger="keyup" data-parsley-pattern="/^[+-]?([1-9][0-9]*(?:[\.][0-9]*)?|0*\.0*[1-9][0-9]*)(?:[eE][+-][0-9]+)?$/" onkeyup="coachType()">

                    </div>

                    <div class="form-group col-sm-4">

                      <label for="supplemental_costs">Supplemental costs(if any)</label>

                      <input type="text" class="form-control" id="supplemental_costs_2" name="supplemental_costs_2"  placeholder="Supplemental costs" data-parsley-trigger="keyup" data-parsley-pattern="/^[+-]?([1-9][0-9]*(?:[\.][0-9]*)?|0*\.0*[1-9][0-9]*)(?:[eE][+-][0-9]+)?$/" onkeyup="coachType()">

                    </div>

                    <div class="form-group col-sm-4">

                      <label for="supplemental_costs">Supplemental costs(if any)</label>

                      <input type="text" class="form-control" id="supplemental_costs_3" name="supplemental_costs_3"  placeholder="Supplemental costs" data-parsley-trigger="keyup" data-parsley-pattern="/^[+-]?([1-9][0-9]*(?:[\.][0-9]*)?|0*\.0*[1-9][0-9]*)(?:[eE][+-][0-9]+)?$/" onkeyup="coachType()">

                    </div>

                  </div>

                  <div class="row">

                    <div class="form-group col-sm-6">

                      <label for="deposit_required">Deposit Required<span class="text-danger">*</span></label>

                      <input type="text" class="form-control" id="deposit_required" name="deposit_required"  placeholder="Enter Deposit Required" data-parsley-trigger="change" data-parsley-pattern="/^[+]?([1-9][0-9]*(?:[\.][0-9]*)?|0*\.0*[1-9][0-9]*)(?:[eE][+-][0-9]+)?$/" required="">

                    </div>

                    <div class="form-group col-sm-6">

                      <label for="deposit_received">Deposit Received</label>

                      <input type="number" class="form-control" step="0.01" id="deposit_received" name="deposit_received"  placeholder="Enter Deposit Received" data-parsley-trigger="change" data-parsley-pattern="/^[+]?([1-9][0-9]*(?:[\.][0-9]*)?|0*\.0*[1-9][0-9]*)(?:[eE][+-][0-9]+)?$/" data-parsley-pattern-message="Please enter numeric value like 10" onkeyup="outstandingBalance()">

                    </div>

                  </div>

                  <div class="row">

                    <div class="form-group col-sm-6">

                      <label for="balance_outstanding">Balance Outstanding<span class="text-danger">*</span></label>

                      <input type="text" class="form-control" id="balance_outstanding" name="balance_outstanding"  placeholder="Enter balance outstanding" data-parsley-trigger="change" data-parsley-pattern="/^[+]?([1-9][0-9]*(?:[\.][0-9]*)?|0*\.0*[1-9][0-9]*)(?:[eE][+-][0-9]+)?$/" required="">

                    </div>

                    <div class="form-group col-sm-6">

                      <label for="total_charge">Total Charge<span class="text-danger">*</span></label>

                      <input type="text" class="form-control" step="0.001" id="total_charge" name="total_charge"  placeholder="Enter Total Charge" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="/^[+]?([1-9][0-9]*(?:[\.][0-9]*)?|0*\.0*[1-9][0-9]*)(?:[eE][+-][0-9]+)?$/">

                    </div>

                  </div>

                  <div class="row">

                    <div class="form-group col-sm-6">

                      <label for="no_of_passengers">Number of Passengers<span class="text-danger">*</span></label>

                      <input type="text" class="form-control" id="no_of_passengers" name="no_of_passengers"  placeholder="Enter Number of Passengers" data-parsley-trigger="keyup" data-parsley-required="true"  min="1" max="200" step="100" data-parsley-validation-threshold="1" data-parsley-type="digits" data-parsley-type-message="Please enter numeric value like 10">

                    </div>

                    <div class="form-group col-sm-6">

                      <label for="no_of_wheelchairs">No of Wheelchairs</label>

                      <input type="text" class="form-control" id="no_of_wheelchairs" name="no_of_wheelchairs" data-parsley-trigger="keyup"data-parsley-trigger="keyup" max="100" step="10" data-parsley-type="digits" data-parsley-type-message="Please enter numeric value like 3"  placeholder="Enter No of Wheelchairs">

                    </div>

                  </div>

                  <div class="row">

                    <div class="form-group col-sm-6">

                      <label for="driver_sheet_notes">Driver Sheet Notes</label>

                      <textarea class="form-control" id="driver_sheet_notes" name="driver_sheet_notes"  placeholder="Enter Notes"></textarea>

                    </div>

                    <div class="col-sm-6">

                      <div>



                        <label for=""> Luggage </label>

                      </div>

                      <div class="icheck-primary d-inline" style="margin-top: 50px;">

                        <input type="radio" id="radioPrimary1" name="luggage" checked value="YES">

                        <label for="radioPrimary1">Yes

                        </label>

                      </div>

                      <div class="icheck-primary d-inline">

                        <input type="radio" id="radioPrimary2" name="luggage" value="NO">

                        <label for="radioPrimary2">No

                        </label>

                      </div>

                    </div>

                   

                  </div>

                   <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{$currentUser->id}}"  placeholder="Enter No of Wheelchairs">

                  <label>Status</label>

                  <div class="row">

                    <div class="custom-control custom-radio">

                      <input class="custom-control-input" type="radio" id="customRadio1" name="status" value="Quotation" checked>

                      <label for="customRadio1" class="custom-control-label">Active</label>

                    </div>&nbsp;&nbsp;&nbsp;

                    <div class="custom-control custom-radio">

                      <input class="custom-control-input" type="radio" id="customRadio2" name="status" value="Quotation">

                      <label for="customRadio2" class="custom-control-label">Inactive</label>

                    </div>

                  </div>

                </div>

                <!-- /.card-body -->



                <div class="card-footer">

                  <button class="btn btn-primary" id="submit" type="submit" value="submit" name="submit">Submit</button>

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



  <!-- Select2 -->

  <script src="{{asset('admin_asset/plugins/select2/js/select2.full.min.js')}}"></script>

  <script src="https://parsleyjs.org/dist/parsley.js"></script>

  <!-- bs-custom-file-input -->

  <script src="{{asset('admin_asset/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>



  <script>

 

  $(document).ready(function () {

    //--bootstrap custom file----

    bsCustomFileInput.init();



    // form validation start

    $('#validate_form').on('submit', function(event)

    {

      $(document).on('click','#submit', function(e) { pace.start(); });

      event.preventDefault();

      $('#validate_form').parsley();

      if($('#validate_form').parsley().isValid())

      {

        $.ajax({

          url: '{{route('admin.inquiry.store')}}',

          method:"POST",

          data:new FormData(this),

          dataType:"json",

          contentType: false,

          cache: false,

          processData: false,

          beforeSend:function()

          {

           $('#submit').attr('disabled', 'disabled');

           $('#submit').html('Submitting...');

          },

          success:function(data)

          {



           

            $('#validate_form').parsley().reset();

            $('#submit').attr('disabled', false);

            $('#submit').html('Submit');

            if(data.success){

              console.log(data);

              $('#validate_form')[0].reset();

              errorsHtml = '<div class="alert alert-success"><ul>';

              $.each(data.success,function (k,v) {

                     errorsHtml += '<li>'+ v + '</li>';

              });

              errorsHtml += '</ul></di>';

              $('#error_message').html(errorsHtml);

              //appending to a <div id="error_message"></div> inside form

              $('#error_message').hide(3000);

            }else{

              console.log(data.error);

              errorsHtml = '<div class="alert alert-danger"><ul>';

              $.each(data.error,function (k,v) {

                     errorsHtml += '<li>'+ v + '</li>';

              });

              errorsHtml += '</ul></di>';

              $('#error_message').html(errorsHtml);

              //appending to a <div id="error_message"></div> inside form

            }



            $('html, body').animate({ scrollTop: 0 }, 0);

          },



        });

      }

    });

  });





// select2

  $(function () {

     //Initialize Select2 Elements

    $('.select2bs4').select2({

      theme: 'bootstrap4'

    })

  })



//==============append new coach fields start============

  var i = 1;

  $('#add').on('click',function()

  {

    i++;

    $("#coach"+i).children().children().find($('input[name="coach_type[]"]')).attr({"id":"coachType_"+i});

    $("#coach1").last().clone().attr({"id":"coach"+i}).appendTo('#divForAppend').after('<button type="button" class="btn btn_remove btn-danger" id="'+i+'"><i class="fas fa-times"></i></button>');

    $("#coach"+i).children().children().find($('input[name="no_of_coaches[]"]')).attr({"id":"noOfCoaches_"+i});

    $("#coach"+i).children().children().find($('input[name="CostPerMile[]"]')).attr({"id":"CostPerMile_"+i});

    $("#coach"+i).children().children().find($('input[name="no_of_days[]"]')).attr({"id":"noOfDays_"+i});

    $("#coach"+i).children().children().find($('input[name="cost_for_day[]"]')).attr({"id":"costForDay_"+i});

     $("#coach"+i).children().children().find($('input[name="cost_for_driver[]"]')).attr({"id":"costForDriver_"+i});

    $("#coach"+i).children().children().find($('input[name="total_charge_per_coach[]"]')).attr({"id":"totalChargePerCoach_"+i});

    //--reset form field after clon---

    $("#coach"+i).children().children().find('input:text').val('');

    $("#coach"+i).children().children().find('input[name="no_of_days[]').val(1);

  });

  //==============Remove added coach field==============

  $(document).on('click', '.btn_remove', function(){

    var button_id = $(this).attr("id");

    $('#coach'+button_id+'').remove();

    $('#'+button_id+'').remove();

  });

//==============append new coach fields end==============



//==============price calculation start================

  let total_trip_cost = 0;

  let deposit_required = 0;

  let deposit_received = 0;

  let balanceOutstanding = 0;

  let totalMileage = 1;

  let driverHours = 1;

  let no_of_coaches = 1;

  let CostPerMile = 1;

  let no_of_days = 1;

  let cost_for_day = 1;

  let cost_for_driver = 1;



  //--get type one coach---



  function coachType()

  {

    

    let supplemental_costs_1 = $('#supplemental_costs_1').val();

    if(supplemental_costs_1 == '')

    {

     supplemental_costs_1 = 0;

    }

    let supplemental_costs_2 = $('#supplemental_costs_2').val();

    if(supplemental_costs_2 == '')

    {

      supplemental_costs_2 = 0;

    }

    let supplemental_costs_3 = $('#supplemental_costs_3').val();

    if(supplemental_costs_3 == '')

    {

      supplemental_costs_3 = 0;

    }

    

    totalMileage = $('#total_mileage').val();

    driverHours  = $('#driver_hours').val();



    var getId = event.target.id;

    var idNumber = getId.split("_");

    var no_of_coaches = $('#noOfCoaches_'+idNumber[1]).val();

    var CostPerMile   = $('#CostPerMile_'+idNumber[1]).val();

    var no_of_days    = $('#noOfDays_'+idNumber[1]).val();

    var cost_for_day  = $('#costForDay_'+idNumber[1]).val();

    var cost_for_driver  = $('#costForDriver_'+idNumber[1]).val();

    if(no_of_days=='')

    {

      no_of_days = 0;

    }

    console.log("no_of_days", no_of_days);

    console.log("no_of_coaches", no_of_coaches);

    console.log("totalMileage", totalMileage);

    console.log("CostPerMile", CostPerMile);

    console.log("cost_for_day", cost_for_day);

    console.log("cost_for_driver", cost_for_driver);

    console.log("driverHours", driverHours);

    console.log("no_of_coaches", no_of_coaches);



    if(no_of_coaches=='')

    {

      no_of_coaches = 1;

    }

    if(no_of_days == 0){



      total_cost = 0;

    }else{

      

      // total_cost = parseFloat(no_of_coaches)*parseFloat(totalMileage)*parseFloat(CostPerMile)+parseFloat(no_of_days)*parseFloat(cost_for_day)+parseFloat(cost_for_driver)*parseFloat(driverHours)*parseFloat(no_of_coaches);

      total_cost = parseFloat(no_of_coaches)*parseFloat(totalMileage)*parseFloat(CostPerMile)+parseFloat(cost_for_driver)*parseFloat(driverHours)*parseFloat(no_of_coaches);

    }

    console.log("total_cost", total_cost);



    $('#totalChargePerCoach_'+idNumber[1]).val(parseFloat(total_cost).toFixed(2));

    total_trip_cost = totalCostCalculate();

    console.log("total_trip_cost1", total_trip_cost);

    if(no_of_days == 0){



      total_trip_cost = 0;

    }else{



      total_trip_cost = parseFloat(total_trip_cost)+parseFloat(supplemental_costs_1)+parseFloat(supplemental_costs_2)+parseFloat(supplemental_costs_3);

    }



    console.log("total_trip_cost2", total_trip_cost);

    $('#total_charge').val(parseFloat(total_trip_cost).toFixed(2));



    //---deposit required 10% of total cost--

    deposit_required = total_trip_cost * 10/100;

    $('#deposit_required').val(parseFloat(deposit_required).toFixed(2));



    //---balance outstanding-----

    deposit_received = $('#deposit_received').val();

    balanceOutstanding = total_trip_cost - deposit_received;

    $('#balance_outstanding').val(parseFloat(balanceOutstanding).toFixed(2));

  }



  function totalCostCalculate()

  {

    total_trip_cost = 0;

     var values = $("input[name='total_charge_per_coach[]']").map(function(){return $(this).val();}).get();

    for (var j = values.length - 1; j >= 0; j--)

    {

      total_trip_cost = parseFloat(total_trip_cost) + parseFloat(values[j]);

    }

    $('#deposit_required').val(parseFloat(deposit_required).toFixed(2));

    if(no_of_days = 0){

    

      return 0;

    }else{



      return parseFloat(total_trip_cost).toFixed(2);

    }

  }
  
 function outstandingBalance()
  {
    deposit_received = $('#deposit_received').val();
     var total_charge = $('#total_charge').val();
    if(total_charge != ''){
    var balanceOutstanding = total_charge - deposit_received;
     $('#balance_outstanding').val(parseFloat(balanceOutstanding).toFixed(2));
    }else{
      balanceOutstanding = total_trip_cost - deposit_received;
      $('#balance_outstanding').val(parseFloat(balanceOutstanding).toFixed(2));
    }
   
    
  }




  /*

  ** on changes total trip milage or driver hourse again, recalculate all cost

  */

  function recaculation()

  {



    let supplemental_costs_1 = $('#supplemental_costs_1').val();

    if(supplemental_costs_1 == '')

    {

     supplemental_costs_1 = 0;

    }

    let supplemental_costs_2 = $('#supplemental_costs_2').val();

    if(supplemental_costs_2 == '')

    {

      supplemental_costs_2 = 0;

    }

    let supplemental_costs_3 = $('#supplemental_costs_3').val();

    if(supplemental_costs_3 == '')

    {

      supplemental_costs_3 = 0;

    }

  

    totalMileage = $('#total_mileage').val();

    driverHours  = $('#driver_hours').val();



    var values = $("input[name='total_charge_per_coach[]']").map(function(){return $(this).val();}).get();







    for (var j = 1; j <= values.length; j++)

    {

      var no_of_coaches    = $('#noOfCoaches_'+j).val();

      var CostPerMile      = $('#CostPerMile_'+j).val();

      var no_of_days       = $('#noOfDays_'+j).val();

      var cost_for_day     = $('#costForDay_'+j).val();

      var cost_for_driver  = $('#costForDriver_'+j).val();







      var Mileage_cost = totalMileage*no_of_coaches*CostPerMile;

      // var Driver_cost = (driverHours*no_of_coaches*cost_for_driver) + (cost_for_day*no_of_coaches);

      var Driver_cost = (driverHours*no_of_coaches*cost_for_driver) ;

      var Day_cost = no_of_days*no_of_coaches*cost_for_day;







       console.log("no_of_days", no_of_days);

       console.log("Mileage_cost", Mileage_cost);

       console.log("Driver_cost", Driver_cost);

       console.log("Day_cost", Day_cost);



      if(no_of_coaches=='')

      {

        no_of_coaches = 1;

      }

      if(no_of_days == 0){



        total_cost = 0;

      }else{

        

        // total_cost = parseFloat(no_of_coaches)*parseFloat(totalMileage)*parseFloat(CostPerMile)+parseFloat(no_of_days)*parseFloat(no_of_coaches)*parseFloat(cost_for_day)+parseFloat(cost_for_driver)*parseFloat(driverHours)*parseFloat(no_of_coaches);

        total_cost = parseFloat(Mileage_cost)+parseFloat(Driver_cost)+parseFloat(Day_cost);

      }

      console.log('total1', total_cost);

      console.log('total2', Mileage_cost+Driver_cost+Day_cost);

      // total_cost = parseFloat(no_of_coaches)*parseFloat(totalMileage)*parseFloat(CostPerMile)+parseFloat(no_of_days)*parseFloat(cost_for_day)+parseFloat(cost_for_driver)*parseFloat(driverHours)*parseFloat(no_of_coaches);



      $('#totalChargePerCoach_'+j).val(parseFloat(total_cost).toFixed(2));

      total_trip_cost = totalCostCalculate();



      console.log("total_trip_cost1", total_trip_cost);

      if(no_of_days == 0){



        total_trip_cost = 0;

      }else{



        total_trip_cost = parseFloat(total_trip_cost)+parseFloat(supplemental_costs_1)+parseFloat(supplemental_costs_2)+parseFloat(supplemental_costs_3);

      }



      console.log("total_trip_cost2", total_trip_cost);





      // total_trip_cost = parseFloat(total_trip_cost)+parseFloat(supplemental_costs_1)+parseFloat(supplemental_costs_2)+parseFloat(supplemental_costs_3);

      $('#total_charge').val(parseFloat(total_trip_cost).toFixed(2));



      //---deposit required 10% of total cost--

      deposit_required = total_trip_cost * 10/100;

      $('#deposit_required').val(parseFloat(deposit_required).toFixed(2));



      //---balance outstanding-----

      deposit_received = $('#deposit_received').val();

      balanceOutstanding = total_trip_cost - deposit_received;

      $('#balance_outstanding').val(parseFloat(balanceOutstanding).toFixed(2));

    }

  }





 //==============price calculation end==================

</script>





<script>

//========On change coach type append cost============

$(document).on("change",".coach_type", function(){

    var coach_type_id= $(this).val();

    //get selected attribute parent id

    var pid = $(this).parent().parent().parent().attr("id");

 

   // run ajax and get cost

   jQuery.ajaxSetup({ async: false });

    $.post({

      url: '{{url("admin/get_cost")}}/'+coach_type_id,

      //method:"POST",

      data:{'_token':'{{csrf_token()}}'},



      dataType:"json",

      success:function(data)

      {

        //make value with 2 desimal

        let cost_per_mile  = parseFloat(data.cost_per_mile).toFixed(2);

        let cost_per_day   = parseFloat(data.cost_per_day).toFixed(2);

        let cost_per_driver = parseFloat(data.cost_per_driver).toFixed(2);

        //set value in input field

         $("div#"+pid+" >>> .CostPerMile").val(cost_per_mile);

         $("div#"+pid+" >>> .costPerDay").val(cost_per_day);

         $("div#"+pid+" >>> .costPerDriver").val(cost_per_driver);

         recaculation();

      }

  });

});

//========On change coach type append cost End============



// ---------on change pickup time not-sure chackbox-----

$('.pick-check').on('change',function(){



  if($(this).is(":checked")){

      $('#Pickup_time').removeAttr('data-parsley-required');
       $('#reporting_time').removeAttr('data-parsley-required');

  }

  else if($(this).is(":not(:checked)")){

    $('#Pickup_time').attr('data-parsley-required', 'true');
    $('#reporting_time').attr('data-parsley-required', 'true');

  }



});



//---------customer validate-------

$('.is_selected').on('change',function(){

  var selected_value = $(this).val();



  if(selected_value)

  {

    $('.is_requires').removeAttr('data-parsley-required');

  }

  else

  {

    

    $('.is_requires').attr('data-parsley-required', 'true');

  }

});



// ---------on change one way -----

$('.is_one_way').on('change',function(){



  if($(this).is(":checked")){

      $('#return_time').removeAttr('data-parsley-required');

      $('#return_date').removeAttr('data-parsley-required');

  }

  else if($(this).is(":not(:checked)")){

    $('#return_time').attr('data-parsley-required', 'true');

    $('#return_date').attr('data-parsley-required', 'true');

  }



});





</script>



@endsection

