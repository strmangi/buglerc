@extends(".admin.layout.master")
@section("title", "Bugler | Purchase Order")

@section("page_css")
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
@endsection
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
              <h3 class="card-title">Bugler Coaches Limited - Purchase Order</h3>
              <button class="btn btn-primary text-light float-sm-right" onclick="PrintDiv();">Print <i class="fas fa-print"></i></button>
              <a class="btn btn-info text-light float-sm-right" href="{{route('admin.purchase_order.index')}}">< Back</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body col-sm-10">
                  <table class="table table-borderless display">
                    <tbody>
                      <tr>
                        <td><label>Date:</label></td>
                        <td><input readonly="" type="date" value="{{$order[0]->date}}" name="date" class="form-control"></td>
                        <td><label>Purchase Order</label></td>
                        <td>
                         <input type="text" readonly=""   value="{{$order[0]->order_no}}" class="form-control" name="order_no">
                        </td>
                      </tr>
                      <tr>
                        <td><label>Supplier</label></td>
                        <td><input type="text" readonly=""   name="supplier" value="{{$order[0]->name}}" class="form-control"></td>
                        <td><label>Supplier Contact Number</label></td>
                        <td><input readonly="" type="text" name="supplier_contact_number" value="{{$order[0]->phone}}"  class="form-control"></td>
                      </tr>
                      <tr>
                        <td><label>Supplier Address</label></td>
                        <td><textarea readonly="" class="form-control" name="supplier_address">{{$order[0]->address}}"</textarea></td>
                        
                      </tr>
                      <tr>
                        <td><label>Supplier Email</label></td>
                        <td><textarea readonly="" class="form-control" name="supplier_fax">{{$order[0]->email}}</textarea></td>
                       {{--  <td><label>Supplier Contact Number</label></td>
                        <td><input   type="text" name="" value="" class="form-control"></td> --}}
                      </tr>
                      <tr>
                        <td>Ordered By</td>
                        <td><input type="text" readonly=""  name="ordered_by" value="{{$order[0]->ordered_by}}"  class="form-control"></td>
                      </tr>
                      
                    </tbody>
                  </table>
                  <table border="1">
                    <thead>
                      <tr>
                        <td><label>Quantity</label></td>
                        <td><label>Description</label></td>
                        <td><label>Vehicle No</label></td>
                        <td><label>Unit Price</label></td>
                        <td><label>Extended price</label></td>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($order as $item)
                      <tr>
                        <td><input type="text" readonly=""  value="{{$item->quantity}}" name="quantity[]" class="form-control"></td>
                        <td><input type="text" readonly=""  value="{{$item->description}}" name="description[]" class="form-control"></td>
                        <td><input type="text" readonly=""  value="{{$item->vehicle_no}}" name="vehicle_no[]" class="form-control"></td>
                        <td><input type="text" readonly=""  value="{{$item->unit_price}}" name="unit_price[]" class="form-control"></td>
                        <td><input type="text" readonly=""  value="{{$item->extended_price}}" name="extended_price[]" class="form-control"></td>
                      </tr>
                      @endforeach
                     
                        <td colspan="3"></td>
                        <td>Subtotal</td>
                        <td><input type="text" readonly=""  value="{{$order[0]->subtotal}}" name="subtotal" class="form-control"></td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>carriage</td>
                        <td><input type="text" readonly=""  value="{{$order[0]->carriage}}" name="carriage" class="form-control"></td>
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td>Total exc VAT</td>
                        <td><input type="text" readonly=""  value="{{$order[0]->total_exc_vat}}" name="total_exc_vat" class="form-control"></td>
                     </tr>
                     <tr>
                        <td>Payment Method</td>
                        <td colspan="2"><input type="text" readonly=""  value="{{$order[0]->payment_method}}" class="form-control" name="payment_method"></td>
                        <td>VAT</td>
                        <td><input type="text" readonly=""  value="{{$order[0]->vat}}" name="vat" class="form-control"></td>
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td>Total inc VAC</td>
                        <td><input type="text" readonly=""  name="total_inc_vac" value="{{$order[0]->total_inc_vac}}" class="form-control"></td>
                     </tr>
                    </tbody>
                  </table>
                <div>
                  <label>Special Instuctions</label>
                  <textarea readonly="" class="form-control" name="special_instuctions">{{$order[0]->special_instuctions}}</textarea>
                </div>
             
                <br><br><br>
                <div class="text-centar">
                  <p class="text-center">VAT NO: 302746873</p>
                  <p class="text-center">Bugler Coaches Limited</p>
                  <p  class="text-center">Tyne depot stawey road clutton Bristol BS39 5TG Telephone 01225/ 444422 Fax 01225 / 466665</p>
                  <p class="text-center">Registered in England No:4907826</p>
                </div>
            </div>
            <!-- /.card-body -->
              <div class="card-footer">
                  
                </div>
          
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
<script src="http://parsleyjs.org/dist/parsley.js"></script>
  <script>
    function PrintDiv() {
      window.print();
    }

    // form validation start
    $('#validate_form').on('submit', function(event)
    {
      event.preventDefault();
      $('#validate_form').parsley();
      if($('#validate_form').parsley().isValid())
      {
        $.ajax({
          url: '{{route('admin.purchase_order.store')}}',
          method:"POST",
          data:new FormData(this),
          dataType:"json",
          contentType: false,
          cache: false,
          processData: false,
          beforeSend:function()
          {
           $('#submit').attr('disabled', 'disabled');
           $('#submit').html('Submitting...');
          },
          success:function(data)
          {
            console.log(data);
           
            // $('#validate_form')[0].reset();
            $('#validate_form').parsley().reset();
            $('#submit').attr('disabled', false);
            $('#submit').html('Submit');
            if(data.success){
              //alert(data.success);
              errorsHtml = '<div class="alert alert-success"><ul>';
              $.each(data.success,function (k,v) {
                     errorsHtml += '<li>'+ v + '</li>';
              });
              errorsHtml += '</ul></di>';
              $('#error_message').html(errorsHtml);
              //appending to a <div id="error_message"></div> inside form 
              $('#error_message').hide(3000);
            }else{
              //console.log(data.error);
              errorsHtml = '<div class="alert alert-danger"><ul>';
              $.each(data.error,function (k,v) {
                     errorsHtml += '<li>'+ v + '</li>';
              });
              errorsHtml += '</ul></di>';
              $('#error_message').html(errorsHtml);

              //appending to a <div id="error_message"></div> inside form 
            }
          }
        });
      }
    });

  </script>
@endsection
