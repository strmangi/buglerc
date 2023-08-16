@extends(".admin.layout.master")
@section("title", "Buddha | Brands")
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
          
{{-- @php
print_r($products);
die();
@endphp --}}
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Coach Table</h3>
              <a href="{{url('admin/brand/create')}}" class="btn btn-info text-light float-sm-right">+ Add</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Image</th>
                  <th>Brand Name</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
               
                <tfoot>
                <tr>
                  <th>Image</th>
                  <th>Brand Name</th>
                  <th>Status</th>
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
  // $(function () {
  //   $("#example1").DataTable();
  // });

$(document).ready(function(){

  });
  

 var dataTable = $('#example1').DataTable({
  processing: true,
  serverSide: true,
  ajax:{
   url: "{{ url('admin/brand') }}",
  },
  columns:[
   {
    data: 'image',
    name: 'image',
    render: function(data, type, full, meta){
     return "<img src='{{ asset('images/brand/thumbnail/') }}/"+data+"' width='70' class='img-thumbnail' alt='' />";
    },
    orderable: false
   },
   {
    data: 'name',
    name: 'name'
   },
   {
    data: 'status',
    name: 'status'
   },
   
   {
    data: 'action',
    name: 'action',
    orderable: false
   }
  ]
 });


 //  $(document).on('click', '.edit', function(){
 //  var id = $(this).attr('id');
 //  $('#form_result').html('');
 //  $.ajax({
 //   url :"/sample/"+id+"/edit",
 //   dataType:"json",
 //   success:function(data)
 //   {
 //    $('#first_name').val(data.result.first_name);
 //    $('#last_name').val(data.result.last_name);
 //    $('#hidden_id').val(id);
 //    $('.modal-title').text('Edit Record');
 //    $('#action_button').val('Edit');
 //    $('#action').val('Edit');
 //    $('#formModal').modal('show');
 //   }
 //  })
 // });


 //============delete===============
 $(document).on('click', '.delete', function(){
  var brand_id = $(this).attr("id");
  if(confirm("Are you sure you want to delete this?"))
  {
     $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
   $.ajax({
    type: "DELETE",
    url: '{{ url("admin/brand") }}/'+brand_id,
    // method:"POST",
    data: {
        "_token": "{{ csrf_token() }}",
        
        },
    success:function(data)
    {
     alert(data);
     dataTable.ajax.reload();
    }
   });
  }
  else
  {
   return false; 
  }
 });
 
  </script>
  @endsection