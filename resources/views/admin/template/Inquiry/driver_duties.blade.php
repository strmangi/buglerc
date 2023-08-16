@extends(".admin.layout.master")

@section("title", "Bugler | Driver Duties")



@section("page_css")

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">

  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

  {{-- select2 --}}

  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/select2/css/select2.min.css')}}">

  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">



  @endsection







@section("body")

 <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">
  
    <!-- Content Header (Page header) -->

    <section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>Driver Duties</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>

              <li class="breadcrumb-item active">Driver Duties </li>

            </ol>

          </div>

        </div>

      </div><!-- /.container-fluid -->

    </section>

<style type="text/css">
  .date-selector {
position: relative !important;
}

.date-selector>input[type=date] {
text-indent: -500px !important;
}
</style>

    <!-- Main content -->

    <section class="content">

      <div class="row">

        <div class="col-12">

          <div class="card">

            <div class="card-header">

              <h3 class="card-title">Driver Duties Details </h3>

            </div>

            <!-- /.card-header -->

            <div class="card-body">

              <div class="row">

                <div class="form-group col-sm-6">

                  <form method="post" id="duty_form">

                    {{ csrf_field() }}

                     <label>Search By Date</label>
                    <div class="form-group date-selector">
                    <input type="date" name="duty_date" id="datePicker" class="date_filter form-control form-control-sm">
                     <span id="datePickerLbl" style="pointer-events: none;"></span>
                    </div>

                  </form>

                </div>

                <div class="form-group col-sm-6">

                  <form method="post" id="customer_form">

                    {{ csrf_field() }}

                    <div class="form-group">

                      <label for="customer_name">Search By Customer<span class="text-danger">*</span></label>

                      <select class="form-control select2bs4 customer_filter" name="customer_id" id="customer_name" style="width: 100%;">

                        <option value="">-Choose-</option>

                        @foreach($customers as $customer)

                        <option value="{{$customer->id}}">{{$customer->name.' || '.$customer->email}} </option>

                        @endforeach

                      </select>

                    </div>

                  </form>

                </div>

              </div>

                <div class="row message" style="display: none;">
                  <p id="error" class="alert alert-danger"></p>
                  <p id="success" class="alert alert-success"></p>
                </div>

              <table id="example1" class="table table-bordered table-striped display table-responsive" >

                <thead>

                 

                <tr>
                   <th>S.No</th>

                    <th>Customer</th>

                    <th>Vehicle Registration No.</th>

                    <th>Driver</th>
                    
                     <th>Reporting Time</th>

                     <th>Pickup Time</th>

                    <th>Return Time</th>

                    <th>Destination(Duty)</th>

                    <th>Details of Duty</th>   

                    <th>Trip Day & Date</th>
                    
                     <th>Invoice</th>
                     
                    <th>Action</th>

                  <!-- <th>Invoice</th> -->

                </tr>

                </thead>

                <tbody id="trip_duty">

               


                </tbody>

                <tfoot>

                  <tr>
                    <th>S.No</th>

                    <th>Customer</th>

                    <th>Vehicle Registration No.</th>

                    <th>Driver</th>
                    
                     <th>Reporting Time</th>

                     <th>Pickup Time</th>

                    <th>Return Time</th>

                    <th>Destination(Duty)</th>

                    <th>Details of Duty</th>   

                    <th>Trip Day & Date</th>
                    
                   
                    
                     <th>Invoice</th>
                     
                    <th>Action</th>
                    <!-- <th>Invoice</th> -->
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

  <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

  <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>



  <!-- Select2 -->

  <script src="{{asset('admin_asset/plugins/select2/js/select2.full.min.js')}}"></script>
  
  
  <script type="text/javascript">
    $("#datePicker").on("change", function(e) {

  displayDateFormat($(this), '#datePickerLbl', $(this).val());

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

  // this pattern dd/mm/yyyy
  // you can set pattern you need
  let dstring = `${("0" + d.getDate()).slice(-2)}/${("0" + (d.getMonth() + 1)).slice(-2)}/${d.getFullYear()}`;

  return dstring;
}
  </script>



 <script>

$(document).ready(function(){

   
    var batchesTable = $('#example1').DataTable({
         processing: true,
        serverSide: true,
        scrollX: true,
        "pageLength": 25,
             dom: 'Blfrtip',
       
        buttons: [
        
        { extend: 'excelHtml5', text: 'Excel' },
        { extend: 'pdfHtml5', text: 'PDF', orientation: 'landscape' },
        { extend: 'print', text: 'Print', autoPrint: true}
        ],


        "lengthMenu": [[ 25, 50, 100, -1], [25, 50, 100, 'All']],
         ajax: { url : "{{route('admin.duty.bydata')}}",  method:"POST", data: function (d) {
                d.date = $('.date_filter').val(),
                d.customer_id = $('.customer_filter').val()
            } 
          },
       
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'customer_name', name: 'customer_name'},
            {data: 'registration_no', name: 'registration_no'},
            {data: 'name', name: 'name'},
             {data: 'reporting_time', name: 'reporting_time'},
            {data: 'pick_up_time', name: 'pick_up_time'},
            {data: 'return_time', name: 'return_time'},
            {data: 'destination', name: 'destination'},
            {data: 'quotation_no', name: 'quotation_no'},
            {data: 'trip_start_date', name: 'trip_start_date'},
           
            {
                data: 'invoice', 
                name: 'invoice', 

                
            },
            
            {
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true
            },
        ]
});
 
$(".date_filter").change(function(){

    batchesTable.draw();
});

 $(".customer_filter").on("change", function() {
     
     batchesTable.draw();
     
 });
 
 

});

 </script>



<script type="text/javascript">



/*  $(function () {
    var table = $('#example1').DataTable({
        processing: true,
        serverSide: true,
        ajax: { method: 'POST', url: "{{route('admin.duty.bydata')}}" , "complete": function(response) {
                $('#trip_duty').html(response);
           } },


       
    });
    
  });*/
</script>

  <script>

        $('#datepicker').datepicker({

            uiLibrary: 'bootstrap4'

        });



    //---search trip by date---



  </script>

  <script>
  
    function updateinvoive(inqId){

      var invoiceId = $('.invoiceId'+inqId).val();
      if(invoiceId && inqId) {
        $.ajax({
          type:'POST',
          url: '<?php echo url('/') ?>/admin/update/invoice',
          headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
          data : { invoiceId : invoiceId, inqId : inqId },
          success:function(data){
            var response = JSON.parse(data);
            $('.message').show();
            if(response.status) {
              $('#success').html(response.message);
              $('#error').hide();
              
            }else{
              $('#error').html(response.message);
              $('#success').hide();
            }

            setTimeout(function(){
              location.reload(); 
            }, 1000);


          }
        });
      }
    }
  </script>

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>

  @endsection