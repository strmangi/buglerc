@extends(".admin.layout.master")

@section("title", "Bugler | Enquiries")

@section("body")

<style type="text/css">
  .row a{
    margin-bottom: 10px;
  }
 .date-selector {
position: relative !important;
}

.date-selector>input[type=date] {
text-indent: -500px !important;
}
</style>

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

            <a href="{{route('admin.trip.import')}}" class="btn btn-success float-sm-right text-light" style="margin-left: 2px;">Import</a>

              <a href="{{route('admin.inquiry.create')}}" class="btn btn-info text-light float-sm-right">+ Add</a>

            </div>
            <div class="left_side_filter">
              <div class="form-group col-sm-4">

                    <form method="post" id="duty_form">

                    {{ csrf_field() }}

                    <label>Search By Date</label>
                     <div class="form-group date-selector">
                      @if(isset($_GET['trip-date']))
                      <input type="date" name="duty_date" id="datePicker" class="date_filter form-control form-control-sm" value="{{$_GET['trip-date']}}" >
                      <span id="datePickerLbl" style="pointer-events: none;"></span>
                      @else
                       <input type="date" name="duty_date" id="datePicker" class="date_filter form-control form-control-sm"  >
                       <span id="datePickerLbl" style="pointer-events: none;"></span>
                      @endif
                    
                    <!--<input type="text" name="duty_date" id="datepicker" class="date_filter" width="220">-->

                    </div>


                    </form>

              </div>
            </div>



            <!-- /.card-header -->
            <form action="{{route('admin.inquiry.deleteCustomeRecord')}}" method="post"> 
              @csrf
            <button style="position: absolute; left: 200px; top: 190px; z-index: 9999;" class="btn btn-danger">Delete Records</button>

            <div class="card-body">

              <table id="example1" class="table table-bordered table-striped">

                <thead>

                <tr>
                   <th>S.No</th>
                   <th class="nosort"><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>

                  <th>Quotation No.</th>

                  <th>Customer Name</th>
                  <th>Contact Name</th>
                  <th>Driver Name</th>
                  <th>Contact Details</th>

                  <th>Pickup Point</th>

                  <th>Pickup Time</th>

                  <th>Trip start date</th>
                  <th>Report Time</th>
                  <th>Trip End date</th>
                  <th>Return Time</th>

                  <th>Status</th>

                  <th>Action</th>

                </tr>

                </thead>

                <tbody id="trip_inquery">

      

                </tbody>

                <tfoot>

                <tr>
                   <th>S.No</th>
                  <th class="nosort"><input type="checkbox" name="select_all" value="1" id="example-select-all1"></th>
                  <th>Quotation No.</th>

                  <th>Customer Name</th>
                  <th>Contact Name</th>
                  <th>Driver Name</th>
                  <th>Contact Details</th>

                  <th>Pickup Point</th>

                  <th>Pickup Time</th>

                  <th>Trip start date</th>
                  <th>Report Time</th>
                  <th>Trip End date</th>
                   <th>Return Time</th>

                  <th>Status</th>

                  <th>Action</th>

                </tr>

                </tfoot>

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
    $("#datePicker").on("change", function(e) {

  displayDateFormat($(this), '#datePickerLbl', $(this).val());

});

      $("#example-select-all").click(function(){

        $("input[type=checkbox]").prop('checked', $(this).prop('checked'));

  });

  $("#example-select-all1").click(function(){

$("input[type=checkbox]").prop('checked', $(this).prop('checked'));
});

function displayDateFormat(thisElement, datePickerLblId, dateValue) {

  $(thisElement).css("color", "rgba(0,0,0,0)")
    .siblings(`${datePickerLblId}`)
    .css({
      position: "absolute",
      left: "10px",
      top: "3px",
      width: $(this).width()
    })
    .text(dateValue.length == 0 ? "" : (`${getDateFormat(new Date(dateValue))}`));

}

function getDateFormat(dateValue) {

  let d = new Date(dateValue);
  let dstring = `${("0" + d.getDate()).slice(-2)}/${("0" + (d.getMonth() + 1)).slice(-2)}/${d.getFullYear()}`;

  return dstring;
}
  </script>

  
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>



 <script>

$(document).ready(function(){

   
    var batchesTable = $('#example1').DataTable({
        "processing": true,
        "serverSide": true,
         "ordering" : true,
        'scrollX': true,
         "columnDefs": [{
                type: 'date',
                targets: 9
            }],
        "pageLength": 25,
            order: [[ 9, 'desc' ]],
             dom: 'Blfrtip',
          buttons: [
             
        ],
   
        "lengthMenu": [[ 25, 50, 100, -1], [25, 50, 100, 'All']],
         ajax: { url : "{{route('admin.inquery.listinquery')}}",  method:"POST", data: function (d) {
                 d.date = $('.date_filter').val()
               
            } 
          },
       
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex' },
            {data: 'checkbox_choose', name: 'checkbox_choose' },
            {data: 'quotation_no', name: 'quotation_no'},
            {data: 'cr_name', name: 'cr_name'},
            {data: 'cr_contact_name', name: 'cr_contact_name'},
            {data: 'select_driver', name: 'select_driver'},
            {data: 'cr_phone', name: 'cr_phone'},
            {data: 'pick_up_point', name: 'pick_up_point'},
            {data: 'pick_up_time', name: 'pick_up_time'},
            {data: 'trip_start_date',  render: formatDate },
            {data: 'report_time', name: 'report_time'},
            {data: 'trip_end_date', name: 'trip_end_date' , /*render: $.fn.dataTable.render.moment('Do MMM YYYY'  )*/ },
            {data: 'return_time', name: 'return_time'},
            {data: 'status', name: 'status'},
            
            {
                data: 'action', 
                name: 'action', 
                searchable: true
            },
        ]
});
 
function formatDate(data, type, row) {
  if (type === 'display' || type === 'filter') {
    return moment(data).format('DD-MMM-YY');
  }
  return data;
}
$(".date_filter").change(function(){

    batchesTable.draw();
});

 var date = $('.date_filter').val();

 if(date != ''){

   batchesTable.draw();

  }

 

});

 </script>





  @endsection