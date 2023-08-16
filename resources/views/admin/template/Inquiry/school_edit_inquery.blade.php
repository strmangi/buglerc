@extends(".admin.layout.master")
@section("title", "Bugler | Enquiry")
@section("page_css")
  <link rel="stylesheet" href="{{ asset('admin_asset/dist/css/form_validation.css')}}">
  {{-- select2 --}}
<link rel="stylesheet" href="{{ asset('admin_asset/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('admin_asset/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<style type="text/css">
  @media print {
  body * {
    visibility: hidden;
  }
  #divToPrint, #divToPrint * {
    visibility: visible;
  }
  #divToPrint {
    position: absolute;
    left: 0;
    top: 0;
  }
  #submit{
    visibility: hidden;
  }

}
.btn-margin{
  margin-top: 120px;
}
</style>

<?php 
  
  // echo "<pre>";
  // print_r($inquiry);
  // die("DF");

?>


@endsection
@section("body")
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Enquiry</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Edit Enquiry</li>
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
          <h3 class="card-title">Edit Enquiry</h3>
          @if(request()->is('admin/inquiry/print*'))
            <input type="button" value="print" onclick="PrintDiv();" class="btn btn-primary text-light float-sm-right" style="margin-right: 4px;"/>
          @else
            <a href="{{url('admin/inquiry_school')}}" class="btn btn-info text-light float-sm-right">< Back</a>
          @endif
        </div>
        <div class="card-body" id="divToPrint">
          @if($errors->any())
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">
            <li>{{$error}}</li>
            </div>
            @endforeach
          @endif
          <div class="tab-content" id="custom-tabs-three-tabContent">
            <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
            <!-- Print header start -->
              @if(request()->is('admin/inquiry/print/*'))
              <div class="row">
                <div class="col-md-12">
                  <img src="{{ asset('admin_asset/dist/img/bugler-logo.jpg') }}" style="width: 80%;" alt="">
                </div>
              </div>
              <div class="row" style="border-bottom: #534d4d solid; margin-top: 2%; margin-bottom: 2%;"></div>
              @endif
            <!-- Print header end -->
              <form role="form" id="validate_form" method="post" action="{{url('admin/school_inquiry/'.$inquiry->id)}}" enctype="multipart/form-data">
                {{csrf_field()}}
                
                <div class="card-body col-sm-10">
                  <h3>Customer Info:</h3>
                  <div class="row">
                    <div class="form-group col-sm-6">
                    <div class="form-group">
                      <label for="customer_name">Choose Customer<span class="text-danger">*</span></label>
                      <select class="form-control select2bs4" name="customer_id" id="customer_name" style="width: 100%;">
                        @foreach($customers as $customer)
                        <option value="{{$customer->id}}" @if($inquiry->customer_id == $customer->id) selected @endif >{{$customer->name}} </option>
                        @endforeach
                      </select>
                    </div>
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="Pickup_point">Pickup Point<span class="text-danger">*</span></label>
                      <input   type="text" class="form-control" id="Pickup_point" name="Pickup_point" value="{{$inquiry->pick_up_point}}"  placeholder="Enter Pickup Point" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change" @error('email') is-invalid @enderror">
                    </div>
                    <div class="form-group col-sm-6">
                      <div class="form-group">
                        <label for="Pickup_time">Pickup Time<span class="text-danger">*</span></label>
                        <input   type="time" class="form-control" id="Pickup_time" name="Pickup_time" value="{{$inquiry->pick_up_time}}"  placeholder="Enter Pickup Time" @if(!empty($inquiry->pick_up_time)) data-parsley-required="true" data-parsley-trigger="change" @endif>
                      </div>
                    </div>
                    <div class="col-sm-3"> 
                      <div class="" style="margin-top: 37px; margin-left: 50px;">
                        <input type="checkbox" class="pick-check" id="" name="Pickup_time_not_fix" value="not_fix" @if(empty($inquiry->pick_up_time)) checked @endif>
                        <label class="" for="">Or Not Sure</label>
                      </div>
                    </div>
                    
                  </div>
                  <br>
                  <h3>Trip Info:</h3>
                  
                  <div class="row">
                    <div class="form-group col-sm-6">
                      <div class="form-group">
                        <label for="Destination">Destination<span class="text-danger">*</span></label>
                        <input   type="text" class="form-control" id="Destination" name="Destination" value="{{$inquiry->destination}}"  placeholder="Enter Destination" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <label for="Trip_Start_Date">No of passanger<span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="Trip_Start_Date" name="no_of_passengers"  data-parsley-required="true" data-parsley-trigger="change" value="{{$inquiry->no_of_passengers}}">
                    </div>


                        @php $k = 0; @endphp
                     </div>
                    <div class="row">
                      @foreach($coaches as $coach)
                        @php ++$k @endphp  
                        <div class="col-sm-11" id="coach{{$k}}">
                            <div class="form-group col-sm-4">
                              <label>Coach Type</label>
                              <select class="form-control coach_type" name="coach_type[]" required="" id="coachType_{{$k}}">
                                <option value="" selected>-Choose-</option>
                                @if(count($coach_types)>0)
                                @foreach($coach_types as $type)
                                  <option value="{{$type['id']}}" @if($type['id'] == $coach->coach_type_id) selected @endif>{{$type['type']}}</option>
                                @endforeach
                                @endif
                              </select>
                            </div>
                        </div>
                         @endforeach
                      </div>
                 
                    <div class="row"></div>
                 
                      @php $k = 0; @endphp
                  

                    @if($inquiry->status=='Start' || $inquiry->status=='Completed') 
                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="customRadio6" name="status" value="Completed" @if($inquiry->status=='Completed') {{'checked'}} @endif>
                      <label for="customRadio6" class="custom-control-label">Trip Completed</label>
                    </div>
                    @endif
                
                </div>
                  <!-- /.card-body -->
                <div class="card-footer">
                  @if(request()->is('admin/inquiry/print*'))
                    <label></label>
                  @else
                    <button type="btn" name="submit" id="submit" class="btn btn-primary">Submit</button>
                  @endif
                </div>
              </form>
            </div>
            <!-- Print footer start -->
              @if(request()->is('admin/inquiry/print/*'))
                <div class="row">
                  <div class="col-md-12 text-center">
                    Copyright 2020 Bugler Coaches, (Registered Company 4907826)
                  </div>
                  <div class="col-md-12 text-center">
                    To get in touch with us, please call 01225 444422 or alternatively email info@buglercoaches.co.uk
                  </div>
                  <div class="col-md-12 text-center">
                    Our postal address is

                      Bugler Coaches Ltd

                      Tyne Depot

                      Stowey Road

                      Clutton

                      Bristol

                      BS39 5TG
                  </div>
                </div>
              @endif
            <!-- Print footer end -->
            <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
            </div>
          </div>
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
  <script src="{{asset('admin_asset/plugins/select2/js/select2.full.min.js')}}"></script>
  <script src="https://parsleyjs.org/dist/parsley.js"></script>
  <!-- bs-custom-file-input -->
  <script src="{{asset('admin_asset/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <script>
    function PrintDiv() {
      window.print();
    }
    // bind parsley to the form
    $("#validate_form").parsley();
    // on form submit
    $("#validate_form").on('submit', function(event) {
        // validate form with parsley.
        $(this).parsley().validate();

        // if this form is valid
        if ($(this).parsley().isValid()) {
          $('#submit').attr('disabled', 'disabled');
           $('#submit').html('Submitting...');
        }else{
          event.preventDefault();
        }
    });
  </script>
<script>
  // select2
  $(function () {
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })

//==============append new coach fields start============
  var i = {{ $k }};
  $('#add').on('click',function()
  {
    ++i;
    $("#coach"+i).children().children().find($('input[name="coach_type[]"]')).attr({"id":"coachType_"+i});
    $("#coach1").last().clone().attr({"id":"coach"+i}).appendTo('#divForAppend').after('<div class="col-sm-1"> <button type="button" class="btn btn_remove btn-danger btn-margin" id="'+i+'"><i class="fas fa-times"></i></button></div>');
    $("#coach"+i).children().children().find($('input[name="no_of_coaches[]"]')).attr({"id":"noOfCoaches_"+i});
    $("#coach"+i).children().children().find($('input[name="CostPerMile[]"]')).attr({"id":"CostPerMile_"+i});
    $("#coach"+i).children().children().find($('input[name="no_of_days[]"]')).attr({"id":"noOfDays_"+i});
    $("#coach"+i).children().children().find($('input[name="cost_for_day[]"]')).attr({"id":"costForDay_"+i});
    $("#coach"+i).children().children().find($('input[name="cost_for_driver[]"]')).attr({"id":"costForDriver_"+i});
    $("#coach"+i).children().children().find($('input[name="total_charge_per_coach[]"]')).attr({"id":"totalChargePerCoach_"+i});
    //--reset form field after clon---
    $("#coach"+i).children().children().find('input:text').val('');
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

  function coachType(target_id)
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

    if (typeof target_id !== 'undefined')
    { 
      var getId = target_id;
    }
    else{
      var getId = event.target.id;
    }

    var idNumber = getId.split("_");
    var no_of_coaches = $('#noOfCoaches_'+idNumber[1]).val();
    var CostPerMile   = $('#CostPerMile_'+idNumber[1]).val();
    var no_of_days    = $('#noOfDays_'+idNumber[1]).val();
    var cost_for_day  = $('#costForDay_'+idNumber[1]).val();
    var cost_for_driver  = $('#costForDriver_'+idNumber[1]).val();
    if(no_of_days=='')
    {
      no_of_days = 1;
    }
    if(no_of_coaches=='')
    {
      no_of_coaches = 1;
    }
    total_cost = parseFloat(no_of_coaches)*parseFloat(totalMileage)*parseFloat(CostPerMile)+parseFloat(no_of_days)*parseFloat(cost_for_day)+parseFloat(cost_for_driver)*parseFloat(driverHours)*parseFloat(no_of_coaches);

    $('#totalChargePerCoach_'+idNumber[1]).val(parseFloat(total_cost).toFixed(2));
    total_trip_cost = totalCostCalculate();

    total_trip_cost = parseFloat(total_trip_cost)+parseFloat(supplemental_costs_1)+parseFloat(supplemental_costs_2)+parseFloat(supplemental_costs_3);
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
    return parseFloat(total_trip_cost).toFixed(2);
  }
  function outstandingBalance()
  {
    deposit_received = $('#deposit_received').val();
    balanceOutstanding = total_trip_cost - deposit_received;
    $('#balance_outstanding').val(parseFloat(balanceOutstanding).toFixed(2));
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
      if(no_of_days=='')
      {
        no_of_days = 1;
      }
      if(no_of_coaches=='')
      {
        no_of_coaches = 1;
      }
      total_cost = parseFloat(no_of_coaches)*parseFloat(totalMileage)*parseFloat(CostPerMile)+parseFloat(no_of_days)*parseFloat(cost_for_day)+parseFloat(cost_for_driver)*parseFloat(driverHours);

      $('#totalChargePerCoach_'+j).val(parseFloat(total_cost).toFixed(2));
      total_trip_cost = totalCostCalculate();

      total_trip_cost = parseFloat(total_trip_cost)+parseFloat(supplemental_costs_1)+parseFloat(supplemental_costs_2)+parseFloat(supplemental_costs_3);
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
$(document).on("change",".coach_type", function()
{
  var coach_type_id= $(this).val();
  var triger_id= $(this).attr('id');
 
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
      // console.log(data.cost_per_mile);
      //make value with 2 desimal 
      let cost_per_mile  = parseFloat(data.cost_per_mile).toFixed(2);
      let cost_per_day   = parseFloat(data.cost_per_day).toFixed(2);
      let cost_per_driver = parseFloat(data.cost_per_driver).toFixed(2);
      //set value in input field
       $("div#"+pid+" >>> .CostPerMile").val(cost_per_mile);
       $("div#"+pid+" >>> .costPerDay").val(cost_per_day);
       $("div#"+pid+" >>> .costPerDriver").val(cost_per_driver);
       coachType(triger_id);
    }
});
});
//========On change coach type append cost End============

// ---------on change pickup time not-sure chackbox-----
$('.pick-check').on('change',function(){

  if($(this).is(":checked")){
      $('#Pickup_time').removeAttr('data-parsley-required');
  }
  else if($(this).is(":not(:checked)")){
    $('#Pickup_time').attr('data-parsley-required', 'true');
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
