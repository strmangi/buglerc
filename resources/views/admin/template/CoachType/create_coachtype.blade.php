@extends(".admin.layout.master")
@section("title", "Buddha | Create Driver")
@section("page_css")
<link rel="stylesheet" href="{{ asset('admin_asset/dist/css/form_validation.css')}}">
@endsection
@section("body")
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create Coach-Type</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Create Coach-Type</li>
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
          <h3 class="card-title">Create Coach-Type</h3>
          
          <a href="{{url('admin/type')}}" class="btn btn-info text-light float-sm-right">< Back</a>
        </div>
        <div class="card-body">
          <div id="error_message"></div>
          <form role="form" id="validate_form" method="post" enctype="multipart/form-data">
         
            {{csrf_field()}}
                <div class="card-body col-sm-8">
                  <div class="form-group">
                    <label for="coach_type">Coach-Type Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="coach_type" name="coach_type"  placeholder="Enter Coach-type Name" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change">
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-4">
                      <label for="cost_per_mile">Cost per mile<span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="cost_per_mile" name="cost_per_mile"  placeholder="Enter Cost per mile" data-parsley-required="true" data-parsley-pattern="^[0-9]*\.[0-9]{2}$" data-parsley-pattern-message="Please enter numeric value like 10.00" data-parsley-minlength="1" data-parsley-trigger="change">
                    </div>
                    <div class="form-group col-sm-4">
                      <label for="cost_per_day">Cost per day<span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="cost_per_day" name="cost_per_day"  placeholder="Enter Cost per day" data-parsley-required="true" data-parsley-pattern="^[0-9]*\.[0-9]{2}$" data-parsley-pattern-message="Please enter numeric value like 10.00" data-parsley-minlength="1" data-parsley-trigger="change">
                    </div>
                    <div class="form-group col-sm-4">
                      <label for="cost_per_driver">Cost per driver(per hour)<span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="cost_per_driver" name="cost_per_driver"  placeholder="Enter Cost per driver" data-parsley-required="true" data-parsley-pattern="^[0-9]*\.[0-9]{2}$" data-parsley-pattern-message="Please enter numeric value like 10.00" data-parsley-minlength="1" data-parsley-trigger="change">
                    </div>
                  </div>



                  <div  class="form-group">
                    <label>Status</label>
                    <div class="row">
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="customRadio1" name="status" value="1" checked>
                        <label for="customRadio1" class="custom-control-label">Active</label>
                      </div>&nbsp;&nbsp;&nbsp;
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="customRadio2" name="status" value="0">
                        <label for="customRadio2" class="custom-control-label">Inactive</label>
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
 
 {{--  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script> --}}
  <script src="http://parsleyjs.org/dist/parsley.js"></script>
  <!-- bs-custom-file-input -->
  <script src="{{asset('admin_asset/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <script type="text/javascript">
  $(document).ready(function () {
    //--bootstrap custom file----
    bsCustomFileInput.init();

    // form validation start
    $('#validate_form').on('submit', function(event)
    {
      event.preventDefault();
      $('#validate_form').parsley();
      if($('#validate_form').parsley().isValid())
      {
        $.ajax({
          url: '{{route('admin.type.store')}}',
          method:"POST",
          data:new FormData(this),
          dataType:"json",
          contentType: false,
          cache: false,
          processData: false,
          beforeSend:function()
          {
           $('#submit').attr('disabled', 'disabled');
           $('#submit').val('Submitting...');
          },
          success:function(data)
          {
            console.log(data);
           
            $('#validate_form')[0].reset();
            $('#validate_form').parsley().reset();
            $('#submit').attr('disabled', false);
            $('#submit').val('Submit');
            if(data.success){
              //alert(data.success);
              errorsHtml = '<div class="alert alert-success"><ul>';
              $.each(data.success,function (k,v) {
                     errorsHtml += '<li>'+ v + '</li>';
              });
              errorsHtml += '</ul></di>';
              $('#error_message').html(errorsHtml);
              //appending to a <div id="error_message"></div> inside form 
              $('#error_message').hide(3000);
            }else{
              //console.log(data.error);
              errorsHtml = '<div class="alert alert-danger"><ul>';
              $.each(data.error,function (k,v) {
                     errorsHtml += '<li>'+ v + '</li>';
              });
              errorsHtml += '</ul></di>';
              $('#error_message').html(errorsHtml);
              //appending to a <div id="error_message"></div> inside form 
            }
          }
        });
      }
    });
  });
// $('#validate_form').parsley();
  


  </script>

@endsection