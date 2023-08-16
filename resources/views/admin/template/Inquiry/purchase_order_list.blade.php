@extends(".admin.layout.master")
@section("title", "Bugler | Purchase Order")
@section("body")
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Purchase Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Purchase Order</li>
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
              <h3 class="card-title">Purchase Order</h3>
              <a href="{{route('admin.purchase_order.create')}}" class="btn btn-info text-light float-sm-right">+ Add</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Order No.</th>
                  <th>Supplier</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  @if(count($orders)>0)
                  @foreach($orders as $order)
                  <tr>
                    <td>{{$order->order_no}}</td>
                    <td>{{$order->name}}</td>
                    <td>{{date('d-M-Y',strtotime($order->date))}}</td>
                    <td>
                      <div class="row">
                        <a href="{{route('admin.purchase_order.view',$order->id)}}" id="" class="edit text-light btn btn-primary btn-sm">View</a>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                  @else
                  <tr>No order found</tr>
                  @endif
                </tbody>
                <tfoot>
                <tr>
                  <th>Order No.</th>
                  <th>Supplier</th>
                  <th>Date</th>
                  <th>Action</th>
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
      "autoWidth": true,
      "ordering": true
    });
  });
  </script>



  @endsection