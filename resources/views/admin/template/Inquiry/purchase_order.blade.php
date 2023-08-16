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
            </div>
            <!-- /.card-header -->
            <div class="card-body col-sm-10">
              <div id="error_message"></div>
               <form role="form" id="validate_form" method="post" enctype="multipart/form-data">
                @csrf
                  <h4><u>For Existing Suppliers</u></h4>
                  <span class="text-danger">*</span >
                  <span><i>Choose existing supplier</i></span>
                  <div class="row">
                    <div class="form-group col-sm-6">
                      <div class="form-group">
                        <label for="supplier_id">Choose Supplier</label>
                        <select class="form-control select2bs4" name="supplier_id"  style="width: 100%;">
                          <option value="">-Choose Supplier-</option>
                          @foreach($suppliers as $supplier)
                          <option value="{{$supplier->id}}">{{$supplier->name.' || '.$supplier->email}} </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <h4><u>New Supplier</u></h4>
                  <span class="text-danger">*</span>
                  <span><i>Complete supplier details only if the supplier does not exist in in the list above.</i></span>
                  <table class="table table-borderless display">
                    <tbody>
                      <tr>
                        <td><label>Supplier</label></td>
                        <td><input type="text" name="supplier" value="" class="form-control"></td>
                        <td><label>Supplier Contact Number</label></td>
                        <td><input   type="text" name="supplier_contact_number" value="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td><label>Supplier Email</label></td>
                        <td>
                          <input type="email" name="supplier_email" value="" class="form-control">
                        </td>
                        <td><label>Supplier Address</label></td>
                        <td><textarea class="form-control" name="supplier_address"></textarea></td>
                        
                      </tr>
                      <tr>
                        
                      </tr>
                      <tr>
                      </tr>
                    </tbody>
                  </table>
                  <hr>
                  <table class="table table-borderless display">
                    <tbody>
                      <tr>
                        <td><label>Date:</label></td>
                        <td><input   type="date" name="date" class="form-control"></td>
                        <td><label>Purchase Order</label></td>
                        <td>
                         <input type="text" readonly="" value="{{$newOrderNo}}" class="form-control" name="order_no">
                        </td>
                      </tr>
                        <td><label>Ordered By</label></td>
                        <td><input type="text" name="ordered_by" class="form-control"></td>
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
                      <tr>
                        <td><input type="text" id="quantity_1" name="quantity[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" name="description[]" class="form-control"></td>
                        <td><input type="text" name="vehicle_no[]" class="form-control"></td>
                        <td><input type="text" id="unitPrice_1" name="unit_price[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" id="extendedPrice_1" name="extended_price[]" readonly="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td><input type="text" id="quantity_2" name="quantity[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" name="description[]" class="form-control"></td>
                        <td><input type="text" name="vehicle_no[]" class="form-control"></td>
                        <td><input type="text" id="unitPrice_2" name="unit_price[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" id="extendedPrice_2" name="extended_price[]" readonly="" class="form-control"></td>
                      </tr>
                      <tr>
                       <td><input type="text" id="quantity_3" name="quantity[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" name="description[]" class="form-control"></td>
                        <td><input type="text" name="vehicle_no[]" class="form-control"></td>
                        <td><input type="text" id="unitPrice_3" name="unit_price[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" id="extendedPrice_3" name="extended_price[]" readonly="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td><input type="text" id="quantity_4" name="quantity[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" name="description[]" class="form-control"></td>
                        <td><input type="text" name="vehicle_no[]" class="form-control"></td>
                        <td><input type="text" id="unitPrice_4" name="unit_price[]" onkeyup="orderCalculation()"  class="form-control"></td>
                        <td><input type="text" id="extendedPrice_4" name="extended_price[]" readonly="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td><input type="text" id="quantity_5" name="quantity[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" name="description[]" class="form-control"></td>
                        <td><input type="text" name="vehicle_no[]" class="form-control"></td>
                        <td><input type="text" id="unitPrice_5" name="unit_price[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" id="extendedPrice_5" name="extended_price[]" readonly="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td><input type="text" id="quantity_6" name="quantity[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" name="description[]" class="form-control"></td>
                        <td><input type="text" name="vehicle_no[]" class="form-control"></td>
                        <td><input type="text" id="unitPrice_6" name="unit_price[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" id="extendedPrice_6" name="extended_price[]" readonly="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td><input type="text" id="quantity_7" name="quantity[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" name="description[]" class="form-control"></td>
                        <td><input type="text" name="vehicle_no[]" class="form-control"></td>
                        <td><input type="text" id="unitPrice_7" name="unit_price[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" id="extendedPrice_7" name="extended_price[]" readonly="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td><input type="text" id="extendedPrice_8" id="quantity_8" name="quantity[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" name="description[]" class="form-control"></td>
                        <td><input type="text" name="vehicle_no[]" class="form-control"></td>
                        <td><input type="text" id="unitPrice_8" name="unit_price[]" onkeyup="orderCalculation()" class="form-control"></td>
                        <td><input type="text" name="extended_price[]" readonly="" class="form-control"></td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>Subtotal</td>
                        <td><input type="text" name="subtotal" readonly="" class="form-control" id="subtotal"></td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>carriage</td>
                        <td><input type="text" name="carriage" class="form-control" value="" 
                          id="carriage1" onkeyup="calculateExtVat()"></td>
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td>Total excl. VAT</td>
                        <td><input type="text" name="total_exc_vat" readonly="" value="" id="totalExcVat" class="form-control"></td>
                     </tr>
                     <tr>
                        <td>Payment Method</td>
                        <td colspan="2"><input type="text" class="form-control" name="payment_method"></td>
                        <td>VAT</td>
                        <td><input type="text" name="vat" id="vat" readonly="" class="form-control"></td>
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td>Total Including VAT</td>
                        <td><input type="text" id="totalIncVat" readonly="" name="total_inc_vac" class="form-control"></td>
                     </tr>
                    </tbody>
                  </table>
                <div>
                  <label>Special Instuctions</label>
                  <textarea class="form-control" name="special_instuctions"></textarea>
                </div>
             
                <br><br><br>
                <div class="text-centar">
                  <p class="text-center">VAT NO: 302746873</p>
                  <p class="text-center">Bugler Coaches Limited</p>
                  <p  class="text-center">TYNE DEPOT, STOWEY ROAD, CLUTTON, BRISTOL, BS39 5TG Telephone 01225/ 444422 Fax 01225 / 466665</p>
                  <p class="text-center">Registered in England No:4907826</p>
                </div>
            </div>
            <!-- /.card-body -->
              <div class="card-footer">
                  <button type="btn" name="submit" id="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
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
           // console.log(data);
           
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
            }
            $('html, body').animate({ scrollTop: 0 }, 0);
          }
        });
      }
    });

    //===============auto calculation============
    function orderCalculation()
    {
      var getId = event.target.id;
      var idNumber = getId.split("_");

      var quantity = $('#quantity_'+idNumber[1]).val();
      if(quantity=='')
      {
        quantity = 0;
      }

      var unitPrice = $('#unitPrice_'+idNumber[1]).val();
      if(unitPrice=='')
      {
        unitPrice = 0;
      }

      let extendedPrice = parseFloat(quantity)*parseFloat(unitPrice);
      $('#extendedPrice_'+idNumber[1]).val(parseFloat(extendedPrice).toFixed(2));

      calculateSubtotal();
      calculateExtVat();
    }

    /*
    ** Calculate subtoatal 
    */
    function calculateSubtotal()
    {
      var values = $("input[name='extended_price[]']").map(function(){return $(this).val();}).get();
      let subTotal = 0;
      for (var j = 0; j <= values.length-1; j++)
      {
         if (values[j] == '') { continue; }
         subTotal = parseFloat(subTotal)+parseFloat(values[j]);
      }
      $('#subtotal').val(parseFloat(subTotal).toFixed(2));

     
    }

     /*
    ** Calculate total ext. vat 
    */
    function calculateExtVat()
    {
      var carriage =  $('#carriage1').val();
      var subtotal =  $('#subtotal').val();
      if(carriage=='')
      {
        carriage = 0;
      }
      if(subtotal=='')
      {
        subtotal = 0;
      }
     var extvat = parseFloat(carriage) + parseFloat(subtotal);
      //console.log(extvat);
    
      $('#totalExcVat').val(parseFloat(extvat).toFixed(2));
      
      var VAT = parseFloat(extvat)*0.20;
      $('#vat').val(parseFloat(VAT).toFixed(2));

      var totalIncVat = parseFloat(extvat)+parseFloat(VAT);
      $('#totalIncVat').val(parseFloat(totalIncVat).toFixed(2));

    }


  </script>
@endsection
