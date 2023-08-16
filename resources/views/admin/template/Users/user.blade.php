@extends(".admin.layout.master")

@section("title", "Bugler | Drivers")

@section("body")

 <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>Drivers</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>

              <li class="breadcrumb-item active">Drivers</li>

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

              <h3 class="card-title">Drivers</h3>

              @can('edit-user')

              <a href="{{route('admin.users.create')}}" class="btn btn-info text-light float-sm-right">+ Add</a>

              @endcan

            </div>

            <!-- /.card-header -->

            <div class="card-body">

              <table id="example1" class="table table-bordered table-striped">

                <thead>

                <tr>

                  <th>Name</th>

                  <th>Email</th>

                  <!-- <th>Status</th> -->

                  @can('edit-user')

                  <th>Action</th>

                  @endcan

                </tr>

                </thead>

                <tbody>

                  @if(count($users)>0)

                    @foreach($users as $user)

                      <tr>

                        <td>{{$user->name}}</td>

                        <td>{{$user->email}}</td>
                        {{--  
                        <td>

                          @if($user->driver_booking_status=='Booked')

                            <span class="badge badge-secondary">Booked</span>

                          @else

                            <span class="badge  badge-success">{{$user->driver_booking_status}}</span>

                          @endif

                        </td>--}}

                        @can('edit-user')

                          <td>

                            <div class="row">

                              <a href="{{route('admin.users.edit', $user->id)}}" name="edit" id="{{$user->id}}" class="edit text-light btn btn-info btn-sm"><i class="far fa-edit"></i> Edit</a>

                              @can('delete-user')

                              <form method="post" action="{{ url('admin/users/'.$user->id) }}" class="">

                                {{csrf_field()}}

                                {{ method_field('delete') }}

                                <button type="submit" name="delete" class="delete btn btn-danger btn-sm" style="margin-left: 2px;"><i class="far fa-trash-alt"></i> Delete</button>

                              </form>

                              @endcan

                            </div>

                          </td>

                        @endcan

                      </tr>

                    @endforeach

                  

                  @else

                  <tr>No Coach found</tr>

                  @endif

                </tbody>

                <tfoot>

                <tr>

                  <th>Name</th>

                  <th>Email</th>

                  {{--<th>Status</th>--}}

                  @can('edit-user')

                  <th>Action</th>

                  @endcan

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