@extends(".admin.layout.master")

@section("title", "Bugler | Edit Driver")

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

            <h1>Edit Driver</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>

              <li class="breadcrumb-item active">Edit Driver</li>

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

          <h3 class="card-title">Edit Driver</h3>

          

          <a href="{{url('admin/drivers')}}" class="btn btn-info text-light float-sm-right">< Back</a>

        </div>

        <div class="card-body">

          @if($errors->any())

            @foreach($errors->all() as $error)

            <div class="alert alert-danger">

            <li>{{$error}}</li>

            </div>

            @endforeach

          @endif

          

          <form role="form" id="validate_form" method="post" action="{{url('admin/users/'.$user->id)}}" enctype="multipart/form-data">

            {{csrf_field()}}

            {{method_field("PUT")}}

                <div class="card-body col-sm-8">

                  <div class="form-group">

                    <label for="name">Name <span class="text-danger">*</span></label>

                    <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}"  placeholder="Enter Coach Name" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change">

                  </div>

                  <div class="form-group">

                    <label for="email">Email <span class="text-danger">*</span></label>

                    <input type="text" class="form-control" id="email" name="email" value="{{$user->email}}"  placeholder="Enter Coach Name" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change">

                  </div>

                  <div class="form-group">

                    <label for="password">Password <span class="text-danger">*</span></label>

                    <input type="password" class="form-control" id="password1" name="password" placeholder="Enter  password" data-parsley-minlength="6" data-parsley-trigger="change">

                  </div>

                  <div class="form-group">

                    <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>

                    <input type="password" class="form-control" id="password2" name="password_confirmation" placeholder="Enter Confirm password" data-parsley-minlength="6" data-parsley-equalto="#password1" data-parsley-trigger="change">

                  </div>

                 <!-- <label>Status</label>

                  <div class="row">

                    <div class="custom-control custom-radio">

                      <input class="custom-control-input" type="radio" id="customRadio1" name="status" value="Booked"  @if($user->driver_booking_status=='Booked') {{'checked'}} @endif>

                      <label for="customRadio1" class="custom-control-label">Booked</label>

                    </div>&nbsp;&nbsp;&nbsp;

                    <div class="custom-control custom-radio">

                      <input class="custom-control-input" type="radio" id="customRadio2" name="status" value="Available" @if($user->driver_booking_status=='Available') {{'checked'}} @endif>

                      <label for="customRadio2" class="custom-control-label">Available</label>

                    </div>

                  </div> -->

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

  <script type="text/javascript">

    $(document).ready(function () {

      //--bootstrap custom file----

      bsCustomFileInput.init();



      // form validation start

      $('#validate_form').parsley();

    });

  </script>



@endsection