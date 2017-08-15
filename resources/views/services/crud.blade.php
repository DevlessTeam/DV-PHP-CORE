<?php
use App\Helpers\DataStore;
  $app = DataStore::instanceInfo()['app'];
  // dd(scandir('.'));
 ?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div id="CRUD">
<div class="dv-notify"></div>
<div class="dv-notify-success"></div>
<div class="dv-notify-failed"></div>

Explain what this Scaffold is for 
<h3>Adding Data to the {{$table_name}} table<h3>
<form class="dv-add-oneTo:{{$service_name}}:{{$table_name}}">
	@foreach($fields as $field)<br>
		@if($field != 'id' && $field != 'devless_user_id')
			<input type="text" class="form-control" name="{{$field}}", placeholder="please enter value for {{$field}}"><br>
		@endif
	@endForeach
	<br>
	<button type="submit" class="btn btn-info">Add Data to {{$table_name}} table</button>
</form>

<h3>Rendering Data from {{$table_name}} table</h3>
<table>
	<tr>
	@foreach($fields as $field)
		@if($field != 'devless_user_id')
			<th>{{$field}}</th>
		@endif
	@endforeach
	</tr>
	<tbody  class="dv-get-all:{{$service_name}}:{{$table_name}}">
		
	<tr>
		@foreach($fields as $field)
			@if($field != 'devless_user_id')
				<td class="var-{{$field}}"></td>

			@endif
		@endforeach
		<td><button class="dv-delete btn btn-danger">Delete</button></td>
		<td><button class="dv-update btn btn-default">update</button></td>
	</tr>
	</tbody>
</table>

<h3>updating Data in the {{$table_name}} table<h3>
<form class="dv-update-oneof:{{$service_name}}:{{$table_name}}">
	@foreach($fields as $field)<br>
		@if($field != 'id' && $field != 'devless_user_id')
			<input type="text" class="form-control" name="{{$field}}", placeholder="please enter value for {{$field}}"><br>
		@endif
	@endForeach
	<button class="dv-update btn btn-info">Update Record</button>
	<br>
</form>
	
<h3>Signup Using DevLess </h3>
<form class="dv-signup">
	<input type="text" name="username" placeholder="enter your username here" class="form-control"><br>
	<input type="email" name="email" placeholder="enter your email  here" class="form-control"><br>
	<input type="number" name="phonenumber" class="form-control" placeholder="enter your phone_number here" class="form-control"><br>
	<input type="password" name="password" placeholder="enter your password  here" class="form-control"><br>
	<input type="text" name="firstname" class="form-control" placeholder="enter firstname here"><br>
	<input type="text" name="lastname" class="form-control" placeholder="enter last name here"><br>
	<button type="submit" class="btn btn-info">Sign up here </button>
</form>

<h3>Signin Using DevLess </h3>
<form class="dv-signin">
	<!-- <input type="text" name="username"  class="form-control" placeholder="enter your username here"> -->
	<!-- <input type="text" name="phonenumber" placeholder="enter phonenumber here"> -->
	<input type="email" name="email" class="form-control" placeholder="enter your email here" ><br>
	<input type="password" name="password" class="form-control" placeholder="please enter your password here" class="form-control"><br>
	<!-- Choose between username, email and phonenumber for login  -->
	<button type="submit" class="btn btn-info">Signin</button>
</form>

<h3>View Profile</h3>
<div class="dv-profile">
	<span class="var-username"></span>
	<span class="var-email"></span>
	<span class="var-phonenumber"></span>
	<span class="var-firstname"></span>
	<span class="var-lastname"></span>
</div>

<h3>Edit Profile</h3>
<form class="dv-updateProfile">
	<input type="text" name="username" placeholder="please enter username here" class="form-control"><br>
	<input type="email" name="email" class="form-control" placeholder="enter email here to update"><br>
	<input type="number" name="phonenumber" placeholder="enter phonenumber to update" class="form-control"><br>
	<input type="text" name="firstname" placeholder="enter firstname to update" class="form-control"><br>
	<input type="text" name="lastname" class="form-control" placeholder="please enter last name to update "><br>
	<input type="password" name="password" class="form-control" placeholder="please enter new password here"><br>
	<button type="submit" class="btn btn-info">Update Profile</button>
</form>
<script src="{{URL::to('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="{{$app->token}}"></script>

</div>
</body>
</html>





