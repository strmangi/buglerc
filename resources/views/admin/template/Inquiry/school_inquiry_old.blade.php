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



                <tbody>



                  @if(count($inquiries)>0)



                 



                  @foreach($inquiries as $inquiry)

                                   <?php $select_driver = App\User::where('id', $inquiry->driver_id)->first();  ?>


                   <tr>

                    <th><input type="checkbox" name="id[]" value="{{$inquiry->id}}"></th>

                    <td>{{$inquiry->quotation_no}}</td>



                     <td>

                        @if(!empty($inquiry->trip_start_date))

                          {{ date('d-M-y', strtotime($inquiry->trip_start_date)) }}

                        @endif

                    </td>



                    <td>

                      {{$inquiry->cr_name}}

                    </td>

                


                     <td>
                         @php 
                            $selectdriver = array();
                            
                            $select_driver = App\Models\DriverTrip::where('trip_id', $inquiry->id)->get();

                            foreach($select_driver as $driver){
                                 $select_drivers = App\user::where('id', $driver->driver_id)->get();
                                    foreach($select_drivers as $dirvers){
                                    
                                    @endphp
                                        
                                         {{  $dirvers->name }}, 
                                        @php
                                    }
                                   
                            }
                            
                         
                         
                         
                         @endphp

                  

                    </td>
                     

               

                    <td>{{ $inquiry->pick_up_point }}</td>



                    <td>

                    @if($inquiry->pick_up_time)

                      {{ date('H:i', strtotime($inquiry->pick_up_time))}}

                    @endif

                    </td>


                     <td>

                     {{ date('H:i', strtotime($inquiry->report_time))}}

                       

                      

                    </td> 

                    <td>

                     {{ date('H:i', strtotime($inquiry->job_end_time))}}

                      

                      

                    </td>

                   


                    <td>

                      @if($inquiry->status=='Confirmed')

                        <span class="badge badge-success">Pending</span>

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

                       <!-- <a href="{{route('admin.inquiry.clone', $inquiry->id)}}" title="Clone Enquiry" id="{{$inquiry->id}}" class="edit text-light btn btn-success btn-sm" style="margin-right: 2px;"><i class="far fa-clone"></i></a> -->



                       <a href="{{route('admin.inquiry.print', $inquiry->id)}}" name="print" title="print" id="{{$inquiry->id}}" class="edit text-light btn btn-primary btn-sm" style="margin-right: 2px;"><i class="fas fa-print"></i></a>



                       <a href="{{url('admin/inquiry_school/edit', $inquiry->id)}}" name="edit" title="edit" id="{{$inquiry->id}}" class="edit text-light btn btn-info btn-sm"><i class="far fa-edit"></i></a>



                       <a href="{{route('admin.inquiry.show', $inquiry->id)}}" id="{{$inquiry->id}}" title="view-details" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;"><i class="far fa-eye"></i></a>





                       <a onclick="return confirm('Are you sure you want to delete?');" href="{{url('admin/delete_enquery', $inquiry->id)}}" id="{{$inquiry->id}}" title="view-details" class="edit text-light btn btn-danger btn-sm" style="margin-left: 2px;"><i class="fa fa-trash"></i></a>



                        @if($inquiry->status=='Confirmed')



                          <a href="{{route('admin.driver.assign',$inquiry->id)}}" id="{{$inquiry->id}}" title="assign driver & coach" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Assign</a>



                        @elseif($inquiry->status=='Quotation')



                          <a href="{{route('admin.inquiry.conform',$inquiry->id)}}" id="{{$inquiry->id}}" title="Confirm Inquiry" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Confirm</a>



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

  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->

  <!-- <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script> -->

  <script src="{{ asset('admin_asset/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>



  <!-- <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script> -->



  <script>


  $(document).ready(function (){   

   var table = $('#example1').DataTable({
      "paging": true,

      "lengthChange": true,

      "lengthMenu": [[ 25, 50, 100, -1], [ 25, 50, 100, "All"]],

      "searching": true,

      "ordering": true,

      "info": true,
      "autoWidth": true,

      'order': [2, 'Desc']

   });

   $("#example-select-all").click(function(){

$("input[type=checkbox]").prop('checked', $(this).prop('checked'));

});

 



   // Handle click on checkbox to set state of "Select all" control

   $('#example tbody').on('change', 'input[type="checkbox"]', function(){

      // If checkbox is not checked

      if(!this.checked){

         var el = $('#example-select-all').get(0);

         // If "Select all" control is checked and has 'indeterminate' property

         if(el && el.checked && ('indeterminate' in el)){

            // Set visual state of "Select all" control 

            // as 'indeterminate'

            el.indeterminate = true;

         }

      }

   });

   



});



  </script>















  @endsection