

<style type="text/css">

</style>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customer confirmation instruction</h1>
          </div>
         
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="buglercoahes">
    <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Customer Instruction Sheet</h3>
              <a href="{{route('admin.instructionsheet.edit',$coach_driver_trip_id)}}" class="btn btn-info text-light float-sm-right" style="    margin-left: 3px;">Edit <i class="far fa-edit"></i></a>
              <button class="btn btn-primary text-light float-sm-right" onclick="PrintDiv();">Print <i class="fas fa-print"></i></button>
           </div>
         </div>
       </div>
     </div>
  
   <div class="container report_left_inner">

<table class="email-table" style=" font-family: 'Montserrat', sans-serif; max-width: 800px; margin: 0 auto; width: 100%; text-align: left; padding-bottom: 20px;">
      <tbody>
        <tr><td align="center" style=" font-family: 'Montserrat', sans-serif; padding-bottom: 36px; padding-top: 33px; width: 100%;"><img src="{{ asset('admin_asset/dist/img/bugler-logo.jpg')}}" alt="logo" style=" font-family: 'Montserrat', sans-serif; width: 100%;" /></td></tr>

        <tr>
           <td class="two-column" style=" font-family: 'Montserrat', sans-serif; ">
               <table  style=" font-family: 'Montserrat', sans-serif; width: 100%; padding-bottom: 60px; ">
                  <tr class="flex-colums">
                    <td class="w-100">
                      <table style=" font-family: 'Montserrat', sans-serif; width: 100%;padding-right: 40px;">
                        <tr>
                          <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;padding-bottom: 8px;">Customer Name and Address</td>
                        </tr>
                        <tr>
                          <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;border: 1px solid; padding: 5px;">{{$data->name}}</td>
                        </tr>
                        <td>
                          <table style=" font-family: 'Montserrat', sans-serif; width: 100%; border: 1px solid; padding: 5px;min-height: 182px;display: flex; box-shadow: 8px 9px 0px #a8a7a7;">
                          @php
                          $str  = $data->address;

                          $address = explode('/n', $str);

                          foreach($address as $addrss){
                          @endphp

                          <tr>

                          <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;">{{ $addrss }}</td>
                          </tr>
                          @php
                          }

                          @endphp
              
             
                          </table>
                        </td>
                      </table>
                    </td>
                     <td class="w-100">
                      <table>
                        <tr>
                          <td style=" font-family: 'Montserrat', sans-serif; font-size: 24px;font-weight: 800; padding-bottom: 12px;">Coach Hire Confirmation</td>
                        </tr>
                        <table style=" font-family: 'Montserrat', sans-serif; padding-left: 31px;">
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 80px;">Quotation<br>Number</td>
                              @php
                            $number  = $data->quotation_no;

                           $array  = array_map('intval', str_split($number));

                             foreach($array as $array){
                          
                          @endphp
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;">{{ $array  }}</td>

                            @php

                          }

                            @endphp
                          
                          </tr>
                        </table>
                        <table style=" font-family: 'Montserrat', sans-serif; width: 100%">
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 14px;font-weight: 600; padding-bottom: 5px;">Date</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;border: 1px solid; padding: 5px; box-shadow: 8px 9px 0px #a8a7a7;">{{date('D d-M-y',strtotime($data->trip_start_date))}}</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 14px;font-weight: 600; padding-bottom: 5px; padding-top: 20px;">Quotation given by</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;border: 1px solid; padding: 5px; box-shadow: 8px 9px 0px #a8a7a7;">Matt</td>
                          </tr>
                        </table>

                      </table>
                       
                        <table style=" font-family: 'Montserrat', sans-serif; width: 100%; padding-bottom: 30px;">
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 15%;"> Contact Name</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width: 32%;">{{$data->name}}</td>
                            <td style=" font-family: 'Montserrat', sans-serif; padding-left: 20px;font-size: 15px;font-weight: 600;width: 25%;"> Contact Phone Number</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width: 33%;">{{$data->phone}}</td>
                            
                          </tr>
                        </table>
                          
                        <table style=" font-family: 'Montserrat', sans-serif; width: 100%;">
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 27%"> Deposit Required</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width: 73%;">{{$data->deposit_required}}</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 27%"> Deposit Received</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width: 73%;">{{$data->deposit_received}}</td>
                          </tr>

                          <tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 27%"> Balance Outstanding</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width:73%;">{{ $data->balance_outstanding }}</td>
                          </tr>
                         
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 27%">Total Charge</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width: 73%;">{{$data->total_charge}}</td>
                          </tr>
                        </table>

                        <table style=" font-family: 'Montserrat', sans-serif; width: 100%;">
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 27%"> Destination</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width:73%;">{{ $data->destination }}</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 27%"> Pick up points & times</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width: 73%;">{{$data->pick_up_point.' - '.date('h:i',strtotime($data->reporting_time))}}</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 27%">Outward Day & Date of Trip</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width: 73%;">{{date('D d-M-y',strtotime($data->trip_start_date))}}</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 27%"> Return Depart Time & Date</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width: 73%;">{{date('H:i',strtotime($data->return_time))}}  {{date('D d-M-y',strtotime($data->return_date))}} </td>
                          </tr>
                        </table>
                        <table style=" font-family: 'Montserrat', sans-serif; width: 100%; padding-top: 10px;">
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 27%"> Number of Passengers</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width: 33%;">{{ $data->no_of_passengers }}</td>
                            <td style=" font-family: 'Montserrat', sans-serif; padding-left: 20px;font-size: 15px;font-weight: 600;width: 25%;"> Number of Wheelchairs</td>
                            <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width: 33%;">{{$data->no_of_wheelchairs}}</td>
                            
                          </tr>
                        </table>
                        <!--<table style=" font-family: 'Montserrat', sans-serif; width:60%; padding-bottom: 30px;">-->
                        <!--  <tr>-->
                        <!--    <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;width: 27%; "> Purchase Order Number</td>-->
                        <!--    <td style=" font-family: 'Montserrat', sans-serif; border: 1px solid;font-weight: 600;font-size: 15px;padding: 10px;width: 33%; height: 16px;">{{ $data->registration_no }}</td>-->
                        <!--  </tr>-->
                        <!--</table>-->

                        <table style=" font-family: 'Montserrat', sans-serif; width: 100%; ">
                          <tr class="flex-colums" >
                            <td style=" font-family: 'Montserrat', sans-serif; width: 100%;" class="w-100"> 
                              <table style=" font-family: 'Montserrat', sans-serif; width: 100%;padding-right: 30px;  padding-top: 30px; ">
                                <tr>
                                  <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;text-align: center;padding-bottom: 10px;">Please Note</td>
                                </tr>
                                <tr>
                                  <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;border: 1px solid; padding: 5px;height: 180px;">{{$data->driver_sheet_notes}}</td>
                                </tr>
                              </table>
                            </td>
                       
                          </tr>
                        </table>
                        <table>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;text-align: center;padding-top: 25px;">We require a 10% deposit to reserve this coach/coaches for you</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;text-align: center;padding-bottom: 25px;">Full payment is required 14 days before the trip date unless you have an account with Bugler Coaches LImited</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;text-align: center;">Unless otherwise agreed in writing all parking costs are the responsibility of the hirer</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;text-align: center;">The hirer agrees by accepting this quotation that he/she is responsible for any loss or damage caused</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;text-align: center;">to the vehicle by any person travelling on this trip.</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;text-align: center;">If the hire is extended at the request of the hirer an additional charge will be made for the extra time.</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;text-align: center;">Whilst we make every attempt to arrive at the destination at the arranged time we cannot be held responsible for any loss</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;text-align: center;">caused by late arrival whatever the cause. Our liability is limited to the cost of returning the passengers to their pick up points by</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;text-align: center;">whatever means is available and reasonable under the circumstances and the Company's discretion in this matter is final.</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 600;text-align: center;">Our full Terms and Conditions are available upon request</td>
                          </tr>
                        </table>
                        <table style=" font-family: 'Montserrat', sans-serif; width:100%;padding-top: 50px;">
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 800;text-align: center;">Tyne Depot Stowey Road Clutton Bristol BS39 5TG</td>
                          </tr>
                          <tr>
                            <td style=" font-family: 'Montserrat', sans-serif; font-size: 15px;font-weight: 800;text-align: center;">Telephone 01225 444422</td>
                          </tr>
                        </table>

                  </tbody>
    </table>
    </div>

   </section> 
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
