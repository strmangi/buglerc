@extends(".admin.layout.master")

@section("title", "Bugler | Customers")

@section("body")

 <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>Customers</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>

              <li class="breadcrumb-item active">Customers</li>

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

              <h3 class="card-title">Customers</h3>

              <a href="{{route('admin.customer.create')}}" class="btn btn-info float-sm-right text-light">+ Add</a>

              <a href="{{route('admin.export')}}" class="btn btn-success text-light float-sm-right" style="margin-right: 3px;">Export Customer</a>

            </div>

            <!-- /.card-header -->

            <div class="card-body">

              <table id="example1" class="table table-bordered table-striped">

                <thead>

                <tr>

                  <th>Name</th>

                  <th>Email</th>

                  <th>Phone</th>

                  <th>Address</th>

                  <th>Status</th>
                  <th>Action</th>

                  

                </tr>

                </thead>

                <tbody>

                  @if(count($customers)>0)

                    @foreach($customers as $customer)

                      <tr>

                        <td>{{$customer->name}}</td>

                        <td>{{$customer->email}}</td>

                        <td>{{$customer->phone}}</td>

                        <td>{{$customer->address}}</td>

                        <td>

                          @if($customer->status==1)

                            <span class="badge badge-success">Active</span>

                          @else

                            <span class="badge badge-secondary">Inactive</span>

                          @endif

                        </td>
                        <td>
                          <div class="row">
                          
                            <a href="{{ route('admin.customer.edit',$customer->id) }}" class="btn btn-info text-light btn-sm"><i class="far fa-edit"></i> Edit</a>
                            <form method="post" action="{{ route('admin.customer.destroy', $customer->id) }}" class="">
                              {{csrf_field()}}
                              {{ method_field('delete') }}
                              <button type="submit" name="delete" class="delete btn btn-danger btn-sm" style="margin-left: 2px;"><i class="far fa-trash-alt"></i> Delete</button>
                            </form>
                          </div>
                          
                        </td>
                      </tr>

                    @endforeach

                  

                  @else

                  <tr>No customers found</tr>

                  @endif

                </tbody>

                <tfoot>

                <tr>

                  <th>Name</th>

                  <th>Email</th>

                  <th>Phone</th>

                  <th>Address</th>

                  <th>Status</th>

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