@extends(".admin.layout.master")
@section("title", "Bugler | Dashboard")
@section("body")
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="col-sm-12 text-center">
        <img src="{{asset('admin_asset/dist/img/bugler-logo.jpg')}}" class="img-fluid">
      </div>
    </div><!--/. container-fluid -->
  </section>
  <!-- /.content -->
  <br/>
  {{-- TRIP TABLE --}}
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Today's Trips</h3>
            <div class="card-tools">
              <form method="post" id="inquiry_form">
                {{ csrf_field() }}
              <div class="input-group input-group-sm" style="width: 177px;">
                <input type="date" name="inquiry_search" class="form-control" id="inquiry_search" placeholder="Search">
                <div class="input-group-append">
                 
                  <button type="button" class="btn btn-default"><i class="fas fa-search"></i></button>
                </div>
              </div>
              </form>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-striped table-responsive">
              <thead>
                <tr>
                  <th>Client Name</th>
                  <th>Contact Details</th>
                  <th>Pickup Point</th>
                  <th>Pickup Time</th>
                  <th>Trip date</th>
                  <th>Status</th>
                  @can('edit-user')
                  <th>Action</th>
                  @endcan
                </tr>
              </thead>
              <tbody id="tbody_trip">
                @if(count($inquiries)>0)
                  @foreach($inquiries as $inquiry)
                    <tr>
                      <td>{{$inquiry->cr_name}}</td>
                      <td>{!!$inquiry->email.'<br>'.$inquiry->phone!!}</td>
                      <td>{{$inquiry->pick_up_point}}</td>
                      <td>{{$inquiry->pick_up_time}}</td>
                      <td>
                        {{date('d-M-y', strtotime($inquiry->trip_start_date)).' to '.date('d-M-y',strtotime($inquiry->return_date))}}
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
                         <a href="{{route('admin.inquiry.edit', $inquiry->id)}}" name="edit" title="edit" id="{{$inquiry->id}}" class="edit text-light btn btn-info btn-sm"><i class="far fa-edit"></i></a>
                         <a href="{{route('admin.inquiry.show', $inquiry->id)}}" id="{{$inquiry->id}}" title="view-details" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;"><i class="far fa-eye"></i></a>
                          @if($inquiry->status=='Confirmed')
                            <a href="{{route('admin.driver.assign',$inquiry->id)}}" id="{{$inquiry->id}}" title="assign driver & coach" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Assign</a>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
              <tfoot>
                <tr>
                  <th>Client Name</th>
                  <th>Contact Details</th>
                  <th>Pickup Point</th>
                  <th>Pickup Time</th>
                  <th>Trip date</th>
                  <th>Status</th>
                  @can('edit-user')
                  <th>Action</th>
                  @endcan
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

  {{-- duty table --}}
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Today's Duties</h3>
            <div class="card-tools">
              <form method="post" id="duty_form">
                {{ csrf_field() }}
                <div class="input-group input-group-sm" style="width: 177px;">
                  <input type="date" name="duty_search" class="form-control" id="duty_search" placeholder="Search">
                  <div class="input-group-append">
                    <button type="button" class="btn btn-default"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-striped table-responsive">
              <thead>
                <tr>
                  <th>Destination(Duty)</th>
                  <th>Vehicle Registration No.</th>
                  <th>Driver</th>
                  <th>Reporting Time</th>
                  <th>Departure Time</th>
                  <th>Trip Day & Date</th>
                  <th>Details of Duty</th>   {{-- driver sheet notes --}}
                </tr>
              </thead>
              <tbody id="tbody_duty">
                @if(count($duties)>0)
                  @foreach($duties as $duty)
                    <tr>
                      <td>{{$duty->destination}}</td>
                      <td>{{$duty->registration_no}}</td>
                      <td>{{$duty->name}}</td>
                      <td>{{$duty->reporting_time}}</td>
                      <td>{{$duty->departure_time}}</td>
                      <td>{{date('D d-M-Y', strtotime($duty->trip_start_date))}}</td>
                      <td>
                       {{$duty->driver_sheet_notes}}
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
              <tfoot>
                <tr>
                  <th>Destination(Duty)</th>
                  <th>Vehicle Registration No.</th>
                  <th>Driver</th>
                  <th>Reporting Time</th>
                  <th>Departure Time</th>
                  <th>Trip Day & Date</th>
                  <th>Details of Duty</th>   {{-- driver sheet notes --}}
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

</div> {{--/.content-wrapper --}}
@endsection

@section("page_script")
  <script>
    //---search trip by date---
    $("#inquiry_search").on("change", function() {
      $.ajax({
        url: '{{route('admin.trip.data')}}',
        method:"POST",
        data:new FormData(document.getElementById('inquiry_form')),
        contentType: false,
        cache: false,
        processData: false,
        success:function(data)
        {
          $('#tbody_trip').html(data);
        }
      });
    });
    //---search duty by date---
    $("#duty_search").on("change", function() {
      $.ajax({
        url: '{{route('admin.duty.data')}}',
        method:"POST",
        data:new FormData(document.getElementById('duty_form')),
        contentType: false,
        cache: false,
        processData: false,
        success:function(data)
        {
          $('#tbody_duty').html(data);
        }
      });
    });
  </script>

@endsection