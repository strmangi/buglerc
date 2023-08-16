@extends(".admin.layout.master")
@section("title", "Bugler | Edit School")
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
            <h1>Edit School</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Edit School</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
       <div class="card">
        <div class="card-header">
          <h3 class="card-title">Edit School</h3>
        </div>
        <div class="card-body col-md-8">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
            </div>
          @endif
          <div id="error_message"></div>
          <form action="{{ route('admin.school.update',$school->SchoolID) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
          <div class="row">
            <div class="col-sm-12 col-md-6">
             <div class="form-group">
                <label for="customer_name"> Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="customer_name" name="Name"  placeholder="Enter School Name" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change" value="{{ $school->Name }}">
              </div>
            </div>
          
            <div class="col-sm-12 col-md-6">
             <div class="form-group">
                <label for="address">Address <span class="text-danger">*</span></label>
                <textarea class="form-control" id="address" name="Address"  placeholder="Enter Address">{{ $school->Address }}</textarea>
              </div>
            </div>
          
            <div class="col-sm-12 col-md-6">
             <div class="form-group">
                <input type="submit" class="btn btn-success" name="submit" value="Submit">
              </div>
            </div>
          </div>
            </form>
          </div>

        </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">

        </div>
        <!-- /.card-footer-->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection

  @section("page_script")

 {{--  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script> --}}
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
          url: '{{route('admin.users.store')}}',
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
            //console.log(data);

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
