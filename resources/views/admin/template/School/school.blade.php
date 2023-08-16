@extends(".admin.layout.master")

@section("title", "Bugler | School")

@section("body")

 <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>School</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>

              <li class="breadcrumb-item active">School</li>

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

              <h3 class="card-title">school</h3>

              <a href="{{route('admin.school.create')}}" class="btn btn-info float-sm-right text-light">+ Add</a>

            

            </div>

            <!-- /.card-header -->

            <div class="card-body">

              <table id="example1" class="table table-bordered table-striped">

                <thead>

                <tr>

                  <th>Name</th>

                
                  <th>Address</th>

          
                  <th>Action</th>

                  

                </tr>

                </thead>

                <tbody>

                  @if(count($school)>0)

                    @foreach($school as $customer)

                      <tr>

                        <td>{{$customer->Name}}</td>
          

                        <td>{{$customer->Address}}</td>

                        
                        <td>
                          <div class="row">
                          
                            <a href="{{ route('admin.school.edit',$customer->SchoolID) }}" class="btn btn-info text-light btn-sm"><i class="far fa-edit"></i> Edit</a>
                            <form onsubmit="return confirm('Do you really want to delete this Record ?');" method="post" action="{{ route('admin.school.destroy', $customer->SchoolID) }}" class="">
                              {{csrf_field()}}
                              {{ method_field('delete') }}
                              <button type="submit" name="delete" class="delete btn btn-danger btn-sm" style="margin-left: 2px;"><i class="far fa-trash-alt"></i> Delete</button>
                            </form>
                          </div>
                          
                        </td>
                      </tr>

                    @endforeach

                  

                  @else

                  <tr>No school found</tr>

                  @endif

                </tbody>

                <tfoot>

                <tr>

                  <th>Name</th>

              

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