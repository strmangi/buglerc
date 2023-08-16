@extends(".admin.layout.master")
@section("title", "Bugler | Coach-types")
@section("body")
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Coach</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Coach</li>
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
              <h3 class="card-title">Coach Types</h3>
              @can('edit-user')
              <a href="{{route('admin.type.create')}}" class="btn btn-info text-light float-sm-right">+ Add</a>
              @endcan
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Coach Type</th>
                  <th>Status</th>
                  @can('edit-user')
                  <th>Action</th>
                  @endcan
                </tr>
                </thead>
                <tbody>
                  @if(count($types)>0)
                    @foreach($types as $value)
                      <tr>
                        <td>{{$value->type}}</td>
                        <td>
                          @if($value->status==1)
                            <span class="badge badge-success">Active</span>
                          @else
                            <span class="badge badge-secondary">Inactive</span>
                          @endif
                        </td>
                        @can('edit-user')
                          <td>
                            <div class="row">
                              <a href="{{route('admin.type.edit', $value->id)}}" name="edit" id="{{$value->id}}" class="edit text-light btn btn-info btn-sm"><i class="far fa-edit"></i> Edit</a>
                              @endcan
                              @can('delete-user')
                              <form method="post" action="{{ url('admin/type/'.$value->id) }}" class="">
                                {{csrf_field()}}
                                {{ method_field('delete') }}
                                <button type="submit" name="delete" class="delete btn btn-danger btn-sm" style="margin-left: 2px;"><i class="far fa-trash-alt"></i> Delete</button>
                              </form>
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
                  <th>Coach Type</th>
                  <th>Status</th>
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