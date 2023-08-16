@extends(".admin.layout.master")

@section("title", "Bugler | Create Coach")

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

            <h1>Create Coach</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>

              <li class="breadcrumb-item active">Create Coach</li>

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

          <h3 class="card-title">Create Coach</h3>

          

          <a href="{{url('admin/coach')}}" class="btn btn-info text-light float-sm-right">< Back</a>

        </div>

        <div class="card-body">

          <div id="error_message"></div>

          <form role="form" id="validate_form" method="post" enctype="multipart/form-data" data-parsley-validate="true">

         

            {{csrf_field()}}

                <div class="card-body col-sm-8">

                  <div class="form-group">

                    <label for="coach_name">Coach Name <span class="text-danger">*</span></label>

                    <input type="text" class="form-control" id="coach_name" name="coach_name"  placeholder="Enter Coach Name" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change">

                  </div>

                  <div class="form-group">

                    <label for="registration">Registration No. <span class="text-danger">*</span></label>

                    <input type="text" class="form-control" id="registration" name="registration_no"  placeholder="Enter Coach Name" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change">

                  </div> 

                  <div class="form-group">

                    <label>Choose Coach Type<span class="text-danger">*</span></label>

                    <select class="form-control" name="coach_type" required="">

                      @if(count($coach_types)>0)

                      <option value="" selected="">--select--</option>

                      @foreach($coach_types as $type)

                      <option value="{{$type['id']}}">{{$type['type']}}</option>

                      @endforeach

                      @else

                      <option value="">Types not found</option>

                      @endif

                    </select>

                  </div>

                  <label>Status</label>

                  <div class="row">

                    <div class="custom-control custom-radio">

                      <input class="custom-control-input" type="radio" id="Available1" name="status" value="Available" checked>

                      <label for="Available1" class="custom-control-label">Available</label>

                    </div>&nbsp;&nbsp;&nbsp;

                    <div class="custom-control custom-radio">

                      <input class="custom-control-input" type="radio" id="customRadio1" name="status" value="Active">

                      <label for="customRadio1" class="custom-control-label">Active</label>

                    </div>&nbsp;&nbsp;&nbsp;

                    <div class="custom-control custom-radio">

                      <input class="custom-control-input" type="radio" id="customRadio2" name="status" value="Inactive">

                      <label for="customRadio2" class="custom-control-label">Inactive</label>

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

 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://parsleyjs.org/dist/parsley.js"></script>

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

          url: '{{route('admin.coach.store')}}',

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



  





  </script>



@endsection