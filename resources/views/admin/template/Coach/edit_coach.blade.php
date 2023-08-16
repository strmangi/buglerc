@extends(".admin.layout.master")

@section("title", "Bugler | Edit Coach")

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

            <h1>Edit Coach</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>

              <li class="breadcrumb-item active">Edit Coach</li>

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

          <h3 class="card-title">Edit Coach</h3>

          

          <a href="{{url('admin/coach')}}" class="btn btn-info text-light float-sm-right">< Back</a>

        </div>

        <div class="card-body">

          @if($errors->any())

            @foreach($errors->all() as $error)

            <div class="alert alert-danger">

            <li>{{$error}}</li>

            </div>

            @endforeach

          @endif

          

          <form role="form" id="validate_form" method="post" action="{{url('admin/coach/'.$coach->id)}}" enctype="multipart/form-data">

            {{csrf_field()}}

            {{method_field("PUT")}}

                <div class="card-body col-sm-8">

                  <div class="form-group">

                    <label for="coach_name">Coach Name <span class="text-danger">*</span></label>

                    <input type="text" class="form-control" id="coach_name" name="coach_name" value="{{$coach->coach_name}}"  placeholder="Enter Coach Name" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change">

                  </div>

                  <div class="form-group">

                    <label for="registration">Registration No. <span class="text-danger">*</span></label>

                    <input type="text" class="form-control" id="registration" name="registration_no" value="{{$coach->registration_no}}" placeholder="Enter Coach Name" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change">

                  </div> 

                  <div class="form-group">

                    <label>Choose Coach Type<span class="text-danger">*</span></label>

                    <select class="form-control" name="coach_type" required="">

                      @if(count($coach_types)>0)

                      <option value="" selected="">--select--</option>

                      @foreach($coach_types as $type)

                      <option value="{{$type['id']}}" @if($type['id']==$coach->coach_type) selected="" @endif>{{$type['type']}}</option>

                      @endforeach

                      @else

                      <option value="">Types not found</option>

                      @endif

                    </select>

                  </div>

                  <label>Status</label>

                  <div class="row">

                    <div class="custom-control custom-radio">

                      <input class="custom-control-input" type="radio" id="Available1" name="status" value="Available" @if($coach->status=='Available') {{'checked'}} @endif>

                      <label for="Available1" class="custom-control-label">Available</label>

                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <div class="custom-control custom-radio">

                      <input class="custom-control-input" type="radio" id="customRadio1" name="status" value="1" @if($coach->status=='Active') {{'checked'}} @endif>

                      <label for="customRadio1" class="custom-control-label">Active</label>

                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <div class="custom-control custom-radio">

                      <input class="custom-control-input" type="radio" id="customRadio2" name="status" value="0" @if($coach->status=='Inactive') {{'checked'}} @endif>

                      <label for="customRadio2" class="custom-control-label">Inactive</label>

                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <div class="custom-control custom-radio">

                      <input class="custom-control-input" type="radio" id="booked" name="status" value="0" @if($coach->status=='Booked') {{'checked'}} @endif>

                      <label for="booked" class="custom-control-label">Booked</label>

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

  <script type="text/javascript">

  $(document).ready(function () {

    //--bootstrap custom file----

    bsCustomFileInput.init();



    $('#validate_form').parsley();

  });





  </script>



@endsection