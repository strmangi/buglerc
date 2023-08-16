@extends(".admin.layout.master")
@section("title", "Bugler | Defect Notice")

@section("page_css")
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
  <style type="text/css">
  .rectangle {
    height: 300px;
    width: 30px;
    background-color: #fff;
    border: 1px solid;
  }
  .normal{
    margin-top: -110px;
    margin-left: 27px;
    position: absolute;
    z-index: 1;
    border-bottom: 1px solid black;
  }
  .low{
    margin-top: -60px;
    position: absolute;
    margin-left: 27px;
    z-index: 1;
    border-bottom: 1px solid black;
  }
  </style>
@endsection
@section("body")
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Driver Vehicle Defect Notice</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Defect Notice</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Bugler Coaches - Driver Vehicle Defect Notice</h3>
              <button class="btn btn-primary text-light float-sm-right" onclick="PrintDiv();">Print <i class="fas fa-print"></i></button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12">
                  <table class="table table-borderless display">
                    <tbody>
                      <tr>
                        <td><label>Date</label></td>
                        <td class="col-space"><input type="date" name="trip_start_date" class="form-control"></td>
                        <td><label>Driver's Name</label></td>
                        <td>
                         <input type="text" class="form-control" name="">
                        </td>
                      </tr>
                      <tr>
                        <td><label>Vehicle Registration Number</label></td>
                        <td><input type="text" name="" value="" class="form-control"></td>
                        <td><label>Odometer Reading</label></td>
                        <td><input   type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td colspan="4" style="border: 0;">
                          Daily safety Check: Tick if OK - Cross if not OK and give further details in box
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-7">
                  <table border="1" class="">
                    <tbody>
                      <tr>
                        <td width="250">Fuel or Oil Leaks</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250">Emergency exit:</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">Battery security & condition</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250">Vehicle cleanliness interior</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">Wheel nuts tight</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250">Windscreen washers working</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">pro-Locks on EVERY WHEEL</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250">Horn working(not before)</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">Tyre condition OK</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250">Exhaust, excessive smoke</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">Doors & Exits incl. warning</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250"></td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">Steering</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250">Fire extinguisher in position</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">Mirrors</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250">Fire Aid kit</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">Brakes</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250">Seat Belts</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">Lights all working</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250">Reflectors in good order</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">Indicators working</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250">Wipers working</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">Bodywork exterior</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                        <td width="250">Bodywork exterior</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td width="250">Bodywork interior</td>
                        <td width="100"><input type="text" name="" class="form-control"></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="col-sm-5">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="col-sm-5"><p>Volvo Oil Level Sight Glass</p></div>
                      <div class="col-sm-1 rectangle">&nbsp;</div>
                      <div class="col-sm-6"><p>Indicate level shown on the glass before you start your</p></div>
                    </div>
                    <div class="col-sm-6">
                      <div class="col-sm-5"><p>Mercedes & Dart Oil Level Dip Stick</p></div>
                      <div class="col-sm-1 rectangle">&nbsp;</div>
                      <span class="normal">Normal</span>
                      <span class="low">Low</span>
                      <div class="col-sm-7"><p>Indicate level shown on the dipstick before before you start your Journey</p></div>
                    </div>
                  </div>
                </div> 
              </div>
              <div class="row">
                    <div class="col-sm-6">
                      <table border="1">
                        <tr>
                          <td>
                            <label>'O' License Disk display in</label>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <label>I have checked that it is present -</label>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <input type="text" class="form-control" name="">
                          </td>
                        </tr>
                      </table>
                    </div>
              </div>
              <div class="col-sm-10">
                <label>Note any exterior or interior damage observed here and add additional defect detail if</label>
                <textarea class="form-control"></textarea>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <label>Driver's Signature</label>
                  <input type="text" name="" class="form-control">
                  <label>Defects rectified by</label>
                  <input type="text" name="" class="form-control">
                  <label>Signature</label>
                  <input type="text" name="" class="form-control">
                  <label>Date</label>
                  <input type="date" name="" class="form-control">
                </div>
                <div class="col-sm-5">
                  <label>Action taken to rectify defects or if NFF</label>
                  <textarea class="form-control" rows="4"></textarea>
                </div>
              </div>
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
  </script>
@endsection
