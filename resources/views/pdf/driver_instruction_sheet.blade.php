<!DOCTYPE html>
<html>
<head>
	<title>Dirver Instruction Sheet</title>
	

</head>
<body>
	<div class="jumbotron text-center" style="margin-bottom:0">
		{{-- <img src="{{asset('admin_asset/dist/img/bugler-logo.jpg')}}" height="100" width="600"> --}}
		<h1>Bugler Coaches</h1>
		<h3>Dirver Instruction Sheet</h3>
	</div>
	<hr>
	<div class="container" style="margin-top:30px">
		<table>
			<tbody>
				<tr>
					<td>Trip day & date:</td>
					<td><input type="text" name="" value="{{date('D d-M-y',strtotime($data->trip_start_date))}}" class="form-control"></td>
					<td>Driver</td>
					<td><input type="text" name="" value="{{$data->driver_name}}" class="form-control"></td>
				</tr>
				<tr>
					<td><label>Vehicle Registration No</label></td>
					<td><input type="text" name="" value="{{$data->registration_no}}" class="form-control"></td>
					<td><label>Number & Type of Wheelchairs</label></td>
					<td><input type="text" name="" value="{{$data->no_of_wheelchairs}}" class="form-control"></td>
				</tr>
				<tr>
					<td><label>Seeting Configuration:</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
				</tr>
				<tr>
					<td><label>Clint Name</label></td>
					<td><input type="text" name="" value="{{$data->name}}" class="form-control"></td>
					<td><label>Contact Number</label></td>
					<td><input type="text" name="" value="{{$data->phone}}" class="form-control"></td>
				</tr>
				<tr>
					<td><label>Email</label></td>
					<td><input type="text" name="" value="{{$data->email}}" class="form-control"></td>
				</tr>
				<tr>
					<td><label>Address</label></td>
					<td><input type="text" name="" value="{{$data->address}}" class="form-control"></td>
				</tr>
				<tr>
					<td><label>Pickup point and time</label></td>
					<td><input type="text" name="" value="{{$data->pick_up_point.'||'.$data->pick_up_time}}" class="form-control"></td>
				</tr>
				<tr>
					<td><label>Destination</label></td>
					<td><input type="text" name="" value="{{$data->destination}}" class="form-control"></td>
				</tr>
				<tr colspan="2">
					<td><label>Return Time (Departure Time)</label></td>
					<td><input type="text" name="" value="{{$data->departure_time}}" class="form-control"></td>
				</tr>
				<tr>
					<td><label>Stops Requested</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
					<td><label>Other Requests</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
				</tr>
				
				<tr>
					<td><label>Notes for Driver</label></td>
					<td><input type="text" name="" value="{{$data->driver_sheet_notes}}"></td>
				</tr>
				<tr>
					<td><label>Driver to collect cash/cheque for:</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
					<td><label>Driver sign coach was clean before</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
				</tr>
				<tr>
					<td><label>Any incident on trip?</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
					<td><label>Any Accidents during this trip?</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
				</tr>
			
				<tr>
					<td><label>Any lost Property ?</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
					<td><label>Please attach Parking Tickets for</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
				</tr>
				
				<tr>
					<td><label>Was fuel required away from</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
					<td><label>Record any damage to coach</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
				</tr>
				<tr>
					<td><label>Record any bad behaviour</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
					<td><label>Record any Complaints</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
				</tr>
				
				<tr colspan="2">
					<td><label>Record any vehicle checks</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
					<td><label>Record any Parking or other fines</label></td>
					<td><input type="text" name="" value="" class="form-control"></td>
				</tr>
			</tbody>
		</table>
		

<div class="jumbotron text-center" style="margin-bottom:0">
  <p>if you have been involved in an accident you must get wirness names and address's and contact phone numbers, and you will need to complate an accident statement as soon as possible upon your return to the depot.
If there has been any kind of incident during the trip plase hand in a statement as soon as possible.</p>
</div>
</body>
</html>