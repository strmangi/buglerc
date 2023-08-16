<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<img src="{{url('').'/admin_asset/dist/img/bugler-logo.jpg'}}" class="img-fluid" >
	<p>Hi, {{ $data['name'] }}</p>
	<p>You have successfully registered on Bugler Coaches</p>
	<p>Your login details is:</p>
	<p>Email:{{ $data['email'] }}</p>
	<p>Password:{{ $data['password'] }}</p>
	<p>Thank You.</p>
	<p>Regards</p>
	<p>Bugler Coaches.</p>

</body>
</html>
