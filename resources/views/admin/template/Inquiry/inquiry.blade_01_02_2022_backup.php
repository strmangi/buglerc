@extends(".admin.layout.master")

@section("title", "Bugler | Enquiries")

@section("body")

<style type="text/css">
  .row a{
    margin-bottom: 10px;
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

            <!-- /.card-header -->
            
            <div class="card-body">

              <table id="example1" class="table table-bordered table-striped">

                <thead>

                <tr>

                  <th>Quotation No.</th>

                  <th>Customer Name</th>
                  <th>Contact Name</th>

                  <th>Contact Details</th>

                  <th>Pickup Point</th>

                  <th>Pickup Time</th>

                  <th>Trip date</th>
                  <th>Report Time</th>
                  <th>Job End Time</th>

                  <th>Status</th>

                  <th>Action</th>

                </tr>

                </thead>

                <tbody>

                  @if(count($inquiries)>0)

                 

                  @foreach($inquiries as $inquiry)
                 
                   <tr>
                    <td>{{$inquiry->quotation_no}}</td>

                    <td>
                      {{$inquiry->cr_name}}
                    </td>
                     <td>
                      {{$inquiry->cr_contact_name}}
                    </td>
                    <td>
                        {!! $inquiry->cr_email.'<br>'.$inquiry->cr_phone !!}
                    </td>
                    <td>{{ $inquiry->pick_up_point }}</td>

                    <td>
                    @if($inquiry->pick_up_time)
                      {{$inquiry->pick_up_time}}
                    @endif
                    </td>

                    <td>
                      @if($inquiry->return_date)
                      @php $returnData = ' to '. date('d-M-y',strtotime($inquiry->return_date)); @endphp
                      @else
                        @php $returnData = ' One Way'; @endphp
                      @endif

                      @if(!empty($inquiry->trip_start_date))
                        {{ date('d-M-y', strtotime($inquiry->trip_start_date)).' '. $returnData }}
                      @endif
                    </td>
                     <td>
                    
                        {{$inquiry->report_time}}
                      
                    </td> 
                    <td>
                    
                        {{$inquiry->job_end_time}}
                      
                    </td>
                    <td>
                      @if($inquiry->status=='Confirmed')
                        <span class="badge badge-success">Confirmed</span>
                      @elseif($inquiry->status=='Quotation')
                        <span class="badge badge-secondary">Quotation</span>
                      @elseif($inquiry->status=='Cancelled')
                        <span class="badge badge-danger">Cancelled</span>
                      @else
                       <span class="badge badge-info">{{$inquiry->status}}</span>
                      @endif
                    </td>
                    <td>
                      <div class="row">
                       <a href="{{route('admin.inquiry.clone', $inquiry->id)}}" title="Clone Enquiry" id="{{$inquiry->id}}" class="edit text-light btn btn-success btn-sm" style="margin-right: 2px;"><i class="far fa-clone"></i></a>

                       <a href="{{route('admin.inquiry.print', $inquiry->id)}}" name="print" title="print" id="{{$inquiry->id}}" class="edit text-light btn btn-primary btn-sm" style="margin-right: 2px;"><i class="fas fa-print"></i></a>

                       <a href="{{route('admin.inquiry.edit', $inquiry->id)}}" name="edit" title="edit" id="{{$inquiry->id}}" class="edit text-light btn btn-info btn-sm"><i class="far fa-edit"></i></a>

                       <a href="{{route('admin.inquiry.show', $inquiry->id)}}" id="{{$inquiry->id}}" title="view-details" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;"><i class="far fa-eye"></i></a>

                        @if($inquiry->status=='Confirmed')

                          <a href="{{route('admin.driver.assign',$inquiry->id)}}" id="{{$inquiry->id}}" title="assign driver & coach" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Assign</a>

                          <a href="{{route('admin.inquiry.sendConfirmMail',$inquiry->id)}}" id="{{$inquiry->id}}" title="Send Confirm Mail" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Send Confirm Mail</a>

                        @elseif($inquiry->status=='Quotation')

                          <a href="{{route('admin.inquiry.conform',$inquiry->id)}}" id="{{$inquiry->id}}" title="Confirm Inquiry" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Confirm</a>


                           <a href="{{route('admin.inquiry.SendEmailEnquery',$inquiry->id)}}" id="{{$inquiry->id}}" title="Send Mail" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Send Mail</a>

                        @endif

                      </div>

                    </td>

                  </tr>

                  @endforeach

                  

                  @else

                  <tr>No Coach found</tr>

                  @endif

                </tbody>

                <tfoot>

                <tr>

                  <th>Quotation No.</th>

                  <th>Customer Name</th>
                  <th>Contact Name</th>

                  <th>Contact Details</th>

                  <th>Pickup Point</th>

                  <th>Pickup Time</th>

                  <th>Trip date</th>
                  <th>Report Time</th>
                  <th>Job End Time</th>

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

  $(function () {
    num_of_records = 10;
    page = 1;
    if ( localStorage.getItem('num_of_records') ) {
        num_of_records = JSON.parse( localStorage.getItem('num_of_records') );
    }
    if ( localStorage.getItem('page') ) {
        page = JSON.parse( localStorage.getItem('page') );
    }
    
    var table = $('#example1').dataTable({
        dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +
"<'row'<'col-sm-12'tr>>" +
"<'row'<'col-sm-5'i><'col-sm-7'p>>",

      "paging": true,

      "lengthChange": true,

      "searching": true,

      "ordering": false,

      "info": true,

      "autoWidth": true,
      pageLength : num_of_records,
      "drawCallback" : function(settings){
          var page = this.api().page() + 1;
          localStorage.setItem('num_of_records', settings._iDisplayLength);
          localStorage.setItem('page', page);
      }

    });
    table.fnPageChange(page-1,true);
    
    $("#example1_paginate").css({"float":"right", "padding-top":"10px"});
  });
  
    

  </script>







  @endsection