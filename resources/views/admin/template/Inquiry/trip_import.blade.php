@extends(".admin.layout.master")
@section("title", "Bugler | Import Trips")
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
            <h1>Import Trips</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Import Trips</li>
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
          <h3 class="card-title">Import Trips</h3>

          <a href="{{url('admin/inquiry')}}" class="btn btn-info text-light float-sm-right">< Back</a>
        </div>
        <div class="card-body">
          <div id="error_message"></div>
         	<div class="row">
          <div class="col-sm-6">
          <form action="{{ route('admin.trip.import.save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="is_school" value="{{ $status }}">
                <label>Choose .csv file</label>
                <input type="file" name="file" class="form-control" accept=".csv">
                <br>
                <button class="btn btn-success">Import Data</button>
            </form>
          </div>
          <div class="col-sm-6">
            @if($status == 0)
             <a href="{{asset('admin_asset/enquiry.csv')}}" class="btn btn-primary text-light float-sm-right">Download Sample File</a>
             @endif
             <br>
             <br>
             <br>

             @if($status == 1)
              <a href="{{asset('admin_asset/regular_journeys.csv')}}" class="btn btn-primary text-light float-sm-right">School Trip Sample File</a>
            @endif
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