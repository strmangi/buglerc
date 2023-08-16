@extends(".admin.layout.master")
@section("title", "Buddha | Create Brand")
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
            <h1>Create Brand</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Create Brand</li>
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
          <h3 class="card-title">Create Brand</h3>
          
          <a href="{{url('admin/brand')}}" class="btn btn-info text-light float-sm-right">< Back</a>
        </div>
        <div class="card-body">
          <div id="error_message"></div>
          <form role="form" id="validate_form" method="post" enctype="multipart/form-data">
         
            {{csrf_field()}}
                <div class="card-body">
                  <div class="form-group">
                    <label for="brand_name">Brand Name</label>
                    <input type="text" class="form-control" id="brand_name" name="brand_name"  placeholder="Enter Brand Name" data-parsley-required="true" data-parsley-minlength="2" data-parsley-trigger="change">
                  </div> 
                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="brand_img" id="exampleInputFile"data-parsley-max-file-size="2048">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                    </div>
                  </div>
                  <label>Status</label>
                  <div class="row">
                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="customRadio1" name="status" value="AC" checked>
                      <label for="customRadio1" class="custom-control-label">Active</label>
                    </div>&nbsp;&nbsp;&nbsp;
                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="customRadio2" name="status" value="PN">
                      <label for="customRadio2" class="custom-control-label">Pending</label>
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
    bsCustomFileInput.init();

    // form validation start

    $('#validate_form').on('submit', function(event){
     event.preventDefault();
    $('#validate_form').parsley();
    if($('#validate_form').parsley().isValid())
    {
    $('#validate_form').parsley();
    $.ajax({
      url: '{{url("admin/brand")}}',
      method:"POST",
      //data:$(this).serialize(),
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
        //alert(data.success);
        if(data.success){
          //alert(data.success);
          errorsHtml = '<div class="alert alert-success"><ul>';
          $.each(data.success,function (k,v) {
                 errorsHtml += '<li>'+ v + '</li>';
          });
          errorsHtml += '</ul></di>';
          $('#error_message').html(errorsHtml);
          //appending to a <div id="error_message"></div> inside form 
          $('#error_message').hide(2000);
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
$('#validate_form').parsley();
  

// file
window.Parsley.addValidator('maxFileSize', {
  validateString: function(_value, maxSize, parsleyInstance) {
    if (!window.FormData) {
      alert('You are making all developpers in the world cringe. Upgrade your browser!');
      return true;
    }
    var files = parsleyInstance.$element[0].files;
    return files.length != 1  || files[0].size <= maxSize * 1024;
  },
  requirementType: 'integer',
  messages: {
    en: 'This file should not be larger than %s Kb',
    fr: 'Ce fichier est plus grand que %s Kb.'
  }
});
  </script>

@endsection