@extends(".admin.layout.master")
@section("title", "Buddha | blank-page")
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
            <h1>Brand Edit</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Brand Edit</li>
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
          <h3 class="card-title">Brand Edit</h3>
          <a href="{{url('admin/brand')}}" class="btn btn-info text-light float-sm-right">< Back</a>
        </div>
        <div class="card-body">
          @if($errors->any())
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">
            <li>{{$error}}</li>
            </div>
            @endforeach
          @endif
          @if (session()->has('msg')) 
          <div class="alert alert-success">{{session()->get('msg')}}</div>
          @endif
          <form role="form" id="validate_form" action="{{url('admin/brand/'.$brand->id)}}" method="post" enctype="multipart/form-data">
        
            
            {{csrf_field()}}
            {{method_field("PUT")}}
                <div class="card-body">
                  <div class="form-group">
                    <label for="brand_name">Brand Name</label>
                    <input type="text" class="form-control" id="brand_name" name="brand_name" value="{{$brand->name}}"  placeholder="Enter Brand Name" data-parsley-required="true"  data-parsley-trigger="keyup">
                  </div> 
                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="brand_img" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                    </div>
                    <img src="{{asset('images/brand/'.$brand->image)}}" height="50" width="70" class="img-thumbnail">
                  </div>
                  <label>Status</label>
                  <div class="row">
                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="customRadio1" name="status" value="AC" @if($brand->status=='AC') {{'checked'}} @endif >
                      <label for="customRadio1" class="custom-control-label">Active</label>
                    </div>&nbsp;&nbsp;&nbsp;
                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="customRadio2" name="status" value="PN" @if($brand->status=='PN') {{'checked'}} @endif>
                      <label for="customRadio2" class="custom-control-label">Deactive</label>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
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

  <script src="http://parsleyjs.org/dist/parsley.js"></script>
  <!-- bs-custom-file-input -->
  <script src="{{asset('admin_asset/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <script type="text/javascript">
  $(document).ready(function () {
    bsCustomFileInput.init();
    
    // form validation start
    $('#validate_form').parsley();
 });


  </script>


  @endsection