@extends(".admin.layout.master")
@section("title", "Bugler | Driver Duties")

@section("page_css")
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
@endsection
@section("body")
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Driver Instruction Sheet</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Edit Instruction Sheet</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Edit Driver Instruction Sheet</h3>
              <a href="{{route('admin.instructionsheet.view',$data->coach_driver_trip_id)}}" class="btn btn-info text-light float-sm-right" style="    margin-left: 3px;">< Back</a>
            </div>

            <!-- /.card-header -->
            <div class="card-body col-sm-10">
              <form action="{{route('admin.instructionsheet.update',$data->coach_driver_trip_id)}}" method="post">
            @if($errors->any())
               @foreach ($errors->all() as $error)
                  <li class="error" style="list-style: none;color: red;">{{ $error }}</li>
              @endforeach
            @endif
                {{csrf_field()}}
                {{method_field("PUT")}}
                <input type="hidden" name="inquiry_id" value="{{$data->inquiry_id}}">
                <input type="hidden" name="customer_id" value="{{$data->customer_id}}">
                <table id="example1" class="table table-borderless display">
                  <tbody>
                    <tr>
                      <td><label>Trip date:</label></td>
                      <td><input   type="date" name="trip_start_date" value="{{date('Y-m-d',strtotime($data->trip_start_date))}}" class="form-control"></td>
                      <td><label>Driver</label></td>
                      <td>
                        <input type="hidden" name="old_driver_id" value="{{$data->driver_id}}">
                        <select class="form-control" name="driver">
                            <option value="{{$data->driver_id}}" selected="">{{$data->driver_name}}</option>
                          @foreach($drivers as $driver)
                            <option value="{{$driver->id}}">{{$driver->name}}</option>
                          @endforeach
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td><label>Vehicle Registration No</label></td>
                      <td><input readonly="" type="text" name="" value="{{$data->registration_no}}" class="form-control"></td>
                      <td><label>Number & Type of Wheelchairs</label></td>
                      <td><input   type="text" name="no_of_wheelchairs" value="{{$data->no_of_wheelchairs}}" class="form-control"></td>
                    </tr>
                    
                    <tr>
                      <td><label>Client Name</label></td>
                      <td><input   type="text" name="customer_name" value="{{$data->name}}" class="form-control"></td>
                      <td><label>Contact Number</label></td>
                      <td><input   type="text" name="phone" value="{{$data->phone}}" class="form-control"></td>
                    </tr>
                    <tr>
                      <td><label>Pickup point</label></td>
                      <td><textarea  name="pick_up_point" class="pick_up_point form-control">{{$data->pick_up_point}}</textarea></td>
                      <td><label>Pickup Time</label></td>
                      <td><input   type="time" name="pick_up_time" value="{{$data->pick_up_time}}" class="form-control"></td>
                    </tr>
                    <tr>
                      <td><label>Email</label></td>
                      <td><textarea  name="email" class="email form-control">{{$data->email}}</textarea></td>
                    </tr>
                    <tr>
                      <td><label>Address</label></td>
                      <td><textarea  name="address" class="address form-control">{{$data->address}}</textarea></td>
                    </tr>
                    <tr>
                      <td><label>Destination</label></td>
                      <td><input   type="text" name="destination" value="{{$data->destination}}" class="form-control"></td>
                    </tr>
                    <tr colspan="2">
                      <td><label>Departure Time</label></td>
                      <td><input   type="time" name="departure_time" value="{{$data->departure_time}}" class="form-control"></td>
                    </tr>
                    <tr>
                      <td><label>Notes for Driver</label></td>
                      <td colspan="3"><textarea class="form-control" name="driver_sheet_notes">{{$data->driver_sheet_notes}}</textarea></td>
                    </tr>
                    <tr>
                      <td colspan="3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
@section('page_script')
  <script>
    function PrintDiv() {
      window.print();
    } 

    
    $("textarea.email").height( $("textarea.email")[0].scrollHeight );
    $("textarea.address").height( $("textarea.address")[0].scrollHeight );
    $("textarea.pick_up_time").height( $("textarea.pick_up_time")[0].scrollHeight );
    // $("textarea.Pickup").height( $("textarea.Pickup")[0].scrollHeight );


  </script>
@endsection
