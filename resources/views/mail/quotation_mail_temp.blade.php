<!DOCTYPE html>
<html>
<body>
	<img src="{{url('').'/admin_asset/dist/img/bugler-logo.jpg'}}" class="img-fluid" >
	<h3>Bugler Coach Hire Quotation</h3>
<div>
	<p>Quotation No: {{$data['quotation_no']}}</p>
	<p>Customer Name: {{ $data['name'] }}</p>
<p>Address: {{ $data['address'] }}</p>
<p>Date: {{ $data['trip_start_date'] }}</p>
<p>Quotation given by: Bugler Coches</p>
<table border="1" style="display: inline-block;">
	<tr>
		<td>Contact Name</td>
		<td>{{ $data['name'] }}</td>
	</tr>
	<tr>
		<td>Destination</td>
		<td>{{ $data['destination'] }}</td>
	</tr>
	<tr>
		<td>Pick up Point</td>
		<td>{{ $data['pick_up_point'] }}</td>
	</tr>
	<tr>
		<td>Pick up time</td>
		<td>{{ $data['pick_up_time'] }}</td>
	</tr>
	<tr>
		<td>Outward Day & Date </td>
		<td>{{ $data['trip_start_date'] }} </td>
	</tr>
	<tr>
		<td>Return Depart Day & Date</td>
		<td>{{ $data['return_date'] }}</td>
	</tr>
	<tr>
		<td>Return Time</td>
		<td>{{ $data['return_ctime'] }}</td>
	</tr>
	<tr>
		<td>Number of Passengers</td>
		<td>{{ $data['no_of_passengers'] }}</td>
	</tr>
	<tr>
		<td>Number of Wheelchairs</td>
		<td>{{ $data['no_of_wheelchairs'] }}</td>
	</tr>	
</table>
<h4>Coaches Detail</h4>
<table border="1" style="display: inline-block;">
	<thead>
		<th>Coach Type</th>
		<th>Number of coaches/journeys required</th>
		
	</thead>
	<tbody>
		@foreach($data['coach_detail'] as $coach)
		<tr>
			<td>{{ $coach->type }}</td>
			<td style="text-align: center;">{{ $coach->no_of_coach }}</td>
		</tr>
		@endforeach
		<tr>
			<th>Total charges</th>
			<th>{!! $data['total_charge'] !!}</th>
		</tr>		
	</tbody>
</table>
<p>We require a 10% deposit to reserve this coach/coaches for you full payment  is required 14 days before the trip date unless you have an account with Bugler Coaches Limited</p>
<p>If you have an account with us we require written confirmation of your acceptance.
This can be by fax to 01225 466664 or by e-mail to info@buglercoaches.co.uk .
</p>
<p>Unless otherwise agreed in writing all parking costs are the responsibility for the hirer</p>
<p>The hirer agrees by accepting this quotation that he/she is responsible for any loss or damage caused to the vehicle by any person travelling on this trip.</p>
<p>If the hire is extended at the request of the hirer an additional charge will be made for the extra time.</p>
<p>Whilst we make every attempt to arrive at the destination at the arranged time we cannot be held responsible for any loss caused by late arrival whatever the cause. Our liability is limited to the coast of returning the passengers to their pick up point by whatever means is available and reasonable under the circumstances and the Company's discretion in this matter is final.</p>
<br>
<p>VAT is not chargeable on this work</p>
<p><b>Tyne Depot, Stowey Road, Clutton, Bristol, BS39 5TG</b></p>
<p>Telephone 01225 444422</p>
<p>Thank You.</p>
<p>Regards</p>
<p>Bugler Coaches</p>
</body>
</html>
