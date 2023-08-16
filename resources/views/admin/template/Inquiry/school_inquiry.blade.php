@extends(".admin.layout.master")

@section("title", "Bugler | Enquiries")

@section("body")
<link  href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Enquiries</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Enquiries</li>
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

              <h3 class="card-title">Enquiries Details</h3>

              <a href="{{route('admin.trip.import', 1)}}" class="btn btn-success float-sm-right text-light" style="margin-left: 2px;">Import</a>

              <a href="{{route('admin.inquiry.create', ['status' => 1])}}" class="btn btn-info text-light float-sm-right">+ Add</a>

            </div>

            <!-- /.card-header -->

            <form action="{{route('admin.inquiry.deleteCustomeRecord')}}" method="post"> 
              @csrf
              <button style="position: absolute; left: 200px; top: 103px; z-index: 9999;" class="btn btn-danger">Delete Records</button>

            <div class="card-body">

              <table id="example1" class="table table-bordered table-striped display select" cellspacing="0">

                <thead>

                <tr>
                  <th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>
                  <th>Quotation No.</th>
                  <th>Date</th>
                  <th>Customer Name</th>
                  <!-- <th>Contact Name</th> -->
                   <th>Driver Name</th>
                  <!-- <th>Contact Details</th> -->
                  <th>Pickup Point</th>
                  <th>Pickup Time</th>
                   <th>Report Time</th>
                   <th>Job End Time</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody id="trip_inquery">

                </tbody>

                <tfoot>
                <tr>
                  <th></th>
                  <th>Quotation No.</th>
                  <th>Date</th>
                  <th>Customer Name</th>
                  <th>Driver Name</th>
                  <th>Pickup Point</th>
                  <th>Pickup Time</th>
                  <th>Report Time</th>
                  <th>Job End Time</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            </form>
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

  <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

  <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
  
  
  <script type="text/javascript">

  $("#example-select-all").click(function(){

    $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
  });

  $("#example-select-all1").click(function(){

   $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
  });
  function getDateFormat(dateValue) {

    let d = new Date(dateValue);
    let dstring = `${("0" + d.getDate()).slice(-2)}/${("0" + (d.getMonth() + 1)).slice(-2)}/${d.getFullYear()}`;
    return dstring;
  }
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $(document).ready(function(){
   
    var batchesTable = $('#example1').DataTable({
      "processing": true,
      "serverSide": true,
        "ordering" : true,
      'scrollX': true,
        "columnDefs": [{
              type: 'date',
              targets: 2
          }],
      "pageLength": 25,
          order: [[ 2, 'desc' ]],
            dom: 'Blfrtip',
        buttons: [],
      "lengthMenu": [[ 25, 50, 100, -1], [25, 50, 100, 'All']],
      ajax: { url : "{{route('admin.inquiry_school.school_listinquery')}}",  method:"POST", data: function (d) {
          d.date = $('.date_filter').val()
        } 
      },
      columns: [
        {data: 'checkbox_choose', name: 'checkbox_choose' },
        {data: 'quotation_no',    name: 'quotation_no'},
        {data: 'trip_start_date', render: formatDate },
        {data: 'cr_name',         name: 'cr_name'},
        {data: 'select_driver',   name: 'select_driver'},
        {data: 'pick_up_point',   name: 'pick_up_point'},
        {data: 'pick_up_time',    name: 'pick_up_time'},
        {data: 'report_time',     name: 'report_time'},
        {data: 'job_end_time',    name: 'job_end_time'},
        {data: 'status',          name: 'status'},
        {data: 'action',          name: 'action',  searchable: true},
      ]
    });
  
    function formatDate(data, type, row) {
      if (type === 'display' || type === 'filter') {
        return moment(data).format('DD-MMM-YY');
      }
      return data;
    }
  });
 </script>
  @endsection