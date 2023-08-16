@extends(".admin.layout.master")
@section("title", "Bugler | Supplier Details")
@section("body")
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Supplier Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Supplier Details</li>
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
              <h3 class="card-title">Supplier Details</h3>
             
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Contact No.</th>
                  <th>Email</th>
                  <th>Address</th>
                </tr>
                </thead>
                <tbody>
                  @if(count($suppliers)>0)
                    @foreach($suppliers as $supplier)
                      <tr>
                        <td>{{$supplier->name}}</td>
                        <td>{{$supplier->phone}}</td>
                        <td>{{$supplier->email}}</td>
                        <td>{{$supplier->address}}</td>
                      </tr>
                    @endforeach
                  @else
                  <tr>No suppliers found</tr>
                  @endif
                </tbody>
                <tfoot>
                <tr>
                  <th>Name</th>
                  <th>Contact No.</th>
                  <th>Email</th>
                  <th>Address</th>
                </tr>
                </tfoot>
              </table>
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
  <!-- DataTables -->

  <script src="{{ asset('admin_asset/plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{ asset('admin_asset/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
  <script>
  $(function () {
   
    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
    });
  });
  </script>



  @endsection