@extends(".admin.layout.master")
@section("title", "Bugler | Enquiry Details")
@section("body")
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Enquiry Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Enquiry</li>
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
              <h3 class="card-title">Enquiry Details</h3>
              <a href="{{route('admin.inquiry.index')}}" class="text-light btn btn-info float-sm-right">< Back</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
              <div class="col-sm-6">
                <table id="" class="table table-bordered table-striped">
                  <thead>
                  <tr class="bg-primary">
                    <th colspan="3" class="text-center">Customer Details</th>
                  </tr>
                  <tr>
                    <th>S.No.</th>
                    <th style="width: 255px;">Item</th>
                    <th>Value</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>Customer Name</td>
                      <td>
                        {{$inquiries[0]->cr_name}}
                      </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td>Customer Email</td>
                      <td>
                        {{$inquiries[0]->cr_email}}
                      </td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td>Customer Phone</td>
                      <td>
                        {{$inquiries[0]->cr_phone}}
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
                <table id="" class="table table-bordered table-striped">
                  <thead>
                  <tr class="bg-primary">
                    <th colspan="3" class="text-center">Trip Details</th>
                  </tr>
                  <tr>
                    <th>S.No.</th>
                    <th style="width: 255px;">Item</th>
                    <th>Value</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>Quotation No</td>
                      <td>{{$inquiries[0]->quotation_no}}</td>
                    </tr>
                      <tr>
                        <td>2</td>
                        <td>Pickup Point</td>
                        <td>{{$inquiries[0]->pick_up_point}}</td>
                      </tr>
                      <tr>
                        <td>3</td>
                        <td>Reporting Time</td>
                        <td>
                        @if($inquiries[0]->pick_up_time)
                        {{date('H:i',strtotime($inquiries[0]->report_time))}}
                        @endif
                        </td>
                      </tr>
                      <tr>
                        <td>4</td>
                        <td>Pickup Time</td>
                        <td>
                        @if($inquiries[0]->pick_up_time)
                        {{date('H:i',strtotime($inquiries[0]->pick_up_time))}}
                        @endif
                        </td>
                      </tr>
                      <tr>
                        <td>5</td>
                        <td>Destination</td>
                        <td>{{$inquiries[0]->destination}}</td>
                      </tr>
                      <tr>
                        <td>6</td>
                        <td>Trip Start Date</td>
                        <td>
                        @if($inquiries[0]->trip_start_date)
                          {{date('D d-m-Y',strtotime($inquiries[0]->trip_start_date))}}
                        @endif
                        </td>
                      </tr>
                      <tr>
                        <td>7</td>
                        <td>Return Date</td>
                        <td>
                        @if($inquiries[0]->return_date)
                        {{date('D d-m-Y',strtotime($inquiries[0]->return_date))}}
                        @elseif($inquiries[0]->trip_end_date)

                         {{date('D d-m-Y',strtotime($inquiries[0]->trip_end_date))}}

                        @endif
                        </td>
                      </tr>
                      <tr>
                        <td>8</td>
                        <td>Is One Way</td>
                        <td>
                          @if($inquiries[0]->is_one_way)
                              Yes
                          @else
                              No
                          @endif
                        </td>
                      </tr>
                      <tr>
                        <td>9</td>
                        <td>Return Time</td>
                        <td>
                           {{date('H:i',strtotime($inquiries[0]->return_time))}}
                         
                        </td>
                      </tr>
                      <tr>
                        <td>10</td>
                        <td>End Time</td>
                        <td>
                           {{date('H:i',strtotime($inquiries[0]->job_end_time))}}
                         
                        </td>
                      </tr>
                      <tr>
                        <td>11</td>
                        <td>Booking Date</td>
                        <td>{{date('D d-m-Y',strtotime($inquiries[0]->booking_date))}}</td>
                      </tr>
                      <tr>
                        <td>12</td>
                        <td>Purchase Order No</td>
                        <td>{{$inquiries[0]->purchase_order_no}}</td>
                      </tr>
                      <tr>
                        <td>13</td>
                        <td>No of Passengers</td>
                        <td>{{$inquiries[0]->no_of_passengers}}</td>
                      </tr>
                      <tr>
                        <td>14</td>
                        <td>No of Wheelchairs</td>
                        <td>{{$inquiries[0]->no_of_wheelchairs}}</td>
                      </tr>
                      <tr>
                        <td>15</td>
                        <td>Driver Sheet Notes</td>
                        <td>{{$inquiries[0]->driver_sheet_notes}}</td>
                      </tr>
                      <tr>
                        <td>16</td>
                        <td>Luggage</td>
                        <td>{{$inquiries[0]->luggage}}</td>
                      </tr>
                      <tr>
                        <td>17</td>
                        <td>Status</td>
                        <td>{{$inquiries[0]->status}}</td>
                      </tr>
                    </tr>
                  </tbody>
                </table>

                <table id="" class="table table-bordered table-striped">
                  <thead>
                  <tr class="bg-primary">
                    <th colspan="3" class="text-center">Trip Charges</th>
                  </tr>
                  <tr>
                    <th>S.No.</th>
                    <th style="width: 255px;">Item</th>
                    <th>Value</th>
                  </tr>
                  </thead>
                  <tbody>
                   
                    
                      <td>1</td>
                      <td>Deposit Required</td>
                      <!--<td>{{round(number_format((float)$inquiries[0]->deposit_required, 2, '.', ''))}}</td>-->
                      <td>{{ number_format((float)$inquiries[0]->deposit_required, 2, '.', '') }}</td>
                      
                    </tr>
                    <tr>
                      <td>2</td>
                      <td>Deposit Received</td>
                      <td>{{ number_format((float)$inquiries[0]->deposit_received, 2, '.', '') }}</td>
                    </tr>
                    <tr>
                    <td>3</td>
                      <td>Balance Outstanding</td>
                      <td>{{ number_format((float)$inquiries[0]->balance_outstanding,2,'.','') }}</td>
                    </tr>
                    <!-- <tr>-->
                    <!--<td>4</td>-->
                    <!--  <td> Total Coach </td>-->
                    <!--  <td>{{ number_format((float)$inquiries[0]->charge_for_this_coach,2,'.','') }}</td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                      <td>4</td>
                      <td>Supplemental Costs - 1</td>
                      <td>{{ number_format((float)$inquiries[0]->supplemental_costs_1,2,'.','') }}</td>
                    </tr>
                    <tr>
                      <td>5</td>
                      <td>Supplemental Costs - 2</td>
                      <td>{{ number_format((float)$inquiries[0]->supplemental_costs_2,2,'.','') }}</td>
                    </tr>
                    <tr>
                      <td>6</td>
                      <td>Supplemental Costs - 3</td>
                      <td>{{ number_format((float)$inquiries[0]->supplemental_costs_3,2,'.','') }}</td>
                    </tr>
                    <tr>
                      <td>7</td>
                      <td>Total Charge</td>
                      <td>{{ number_format((float)$inquiries[0]->total_charge,2,'.','') }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-sm-6">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr class="bg-primary">
                      <th colspan="3" class="text-center">Coach</th>
                    </tr>
                    <th>S.No</th>
                    <th style="width: 205px;">Item</th>
                    <th>Value</th>
                  </thead>
                  <tbody>

                    @foreach($inquiries as $coach)
                      @php $i = 1; @endphp
                      <tr>
                        <td>{{$i++}}</td>
                        <td>Coach Type</td>
                        <td>{{$coach->coachType}}</td>
                      </tr>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>Number of Coaches</td>
                        <td>{{$coach->no_of_coach}}</td>
                      </tr>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>Total Charge for this Coach </td>
                        <td>{{ number_format((float) $coach->charge_for_this_coach,2,'.','') }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
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

  @endsection
