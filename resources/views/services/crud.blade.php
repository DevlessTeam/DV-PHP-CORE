<?php
use App\Helpers\DataStore;
$app = DataStore::instanceInfo()['app'];
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet"></head>
	<body>
		<style type="text/css">
			body{
				font-family: 'Raleway', sans-serif;        
				font-size: 17px;
			}
		</style>

		<div id="CRUD">


			<div class="dv-notify-success">
				<div class="alert alert-success alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
					<strong>Data has been added to devless table successfully</strong>    </div>
				</div>

				<div class="dv-notify-failed">
					<div class="alert alert-danger alert-dismissable">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
						<strong>Data has been added to devless table successfully</strong>    </div>
					</div>

					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<div class="panel panel-primary">
								<div class="panel-heading" style="text-align: center;">SignIn Using DevLess</div>
								<div class="panel-body" style="text-align: center;">
								<h3>Adding Data to the test_table table<h3>
										<form class="dv-add-oneto:{{$service_name}}:{{$table_name}}">
											@foreach($fields as $field)
												@if($field != "devless_user_id")
													<input type="text" class="form-control" name="{{$field}}", placeholder="please enter value for {{$field}}"><br>
												@endif
											@endforeach
											<br>
											<button type="submit" class="btn btn-info">Add Data to {{$table_name}} table</button>
										</form>

										<h3>Rendering Data from {{$table_name}} table</h3>
										<br/>
										<table class="table" style="text-align:center;">
											<tr>
												@foreach($fields as $field)
													@if($field != "devless_user_id")
														<th>{{$field}}</th>
													@endif
												@endforeach
												<th>Delete</th>
												<th>Update</th>
											</tr>
											<tbody  class="dv-get-all:{{$service_name}}:{{$table_name}}">

												<tr>
												@foreach($fields as $field)
													@if($field != "devless_user_id")
														<td class="var-{{$field}}"></td>
													@endif
												@endforeach												
													<td><button class="dv-delete btn btn-danger">Delete</button></td>
													<td><button class="dv-update btn btn-default">update</button></td>
												</tr>
											</tbody>
										</table>
									</div></div></div></div>


									<div class="row">
										<div class="col-md-8 col-md-offset-2">
											<div class="panel panel-primary">
												<div class="panel-heading" style="text-align: center;">updating Data in the {{$table_name}} table</div>
												<div class="panel-body" style="text-align: center;">
													<form class="dv-update-oneof:{{$service_name}}:{{$table_name}}">
														@foreach($fields as $field)
															@if($field != "devless_user_id" && $field != "id")																									
														<input type="text" class="form-control" name="{{$field}}" placeholder="please enter value for {{$field}}"><br>
															@endif
														@endforeach												
		
														<button class="dv-update btn btn-info">Update Record</button>
														<br>
													</form>
												</div></div></div></div>

												<div class="row">
													<div class="col-md-8 col-md-offset-2">
														<div class="panel panel-primary">
															<div class="panel-heading" style="text-align: center;">Signup Using DevLess</div>
															<div class="panel-body">
																<form class="dv-signup">
																	<input type="text" name="username" placeholder="enter your username here" class="form-control"><br>
																	<input type="email" name="email" placeholder="enter your email  here" class="form-control"><br>
																	<input type="number" name="phonenumber" class="form-control" placeholder="enter your phone_number here" class="form-control"><br>
																	<input type="password" name="password" placeholder="enter your password  here" class="form-control"><br>
																	<input type="text" name="firstname" class="form-control" placeholder="enter firstname here"><br>
																	<input type="text" name="lastname" class="form-control" placeholder="enter last name here"><br>
																	<div class="col-md-6 col-md-offset-6">
																		<button type="submit" class="btn btn-info">Sign up here </button>
																	</div>
																</form>
															</div></div></div></div>

															<div class="row">
																<div class="col-md-8 col-md-offset-2">
																	<div class="panel panel-primary">
																		<div class="panel-heading" style="text-align: center;">SignIn Using DevLess</div>
																		<div class="panel-body">
																			<form class="dv-signin">
																				<!-- <input type="text" name="username"  class="form-control" placeholder="enter your username here"> -->
																				<!-- <input type="text" name="phonenumber" placeholder="enter phonenumber here"> -->
																				<input type="email" name="email" class="form-control" placeholder="enter your email here" ><br>
																				<input type="password" name="password" class="form-control" placeholder="please enter your password here" class="form-control"><br>
																				<!-- Choose between username, email and phonenumber for login  -->
																				<div class="col-md-6 col-md-offset-6">
																					<button type="submit" class="btn btn-info">SignIn here </button>
																				</div>
																			</form>

																		</div></div></div></div>

																		<div class="row">
																			<div class="col-md-8 col-md-offset-2">
																				<div class="panel panel-primary">
																					<div class="panel-heading" style="text-align: center;">View Profile</div>
																					<div class="panel-body">	
																						<div class="dv-profile">
																							<div class="container" style="width: 100%">
																								<table class="table">
																									<thead class=" table-inverse">
																										<tr>
																											<th>Username</th>
																											<th>Email</th>
																											<th>Phone number</th>
																											<th>First Name </th>
																											<th>last Name</th>
																										</tr>
																									</thead>
																									<tbody>
																										<tr>
																											<td><span class="var-username"></span></td>
																											<td><span class="var-email"></span></td>
																											<td><span class="var-phonenumber"></span></td>
																											<td><span class="var-firstname"></span></td>
																											<td><span class="var-lastname"></span></td>
																										</tr>

																									</tbody>
																								</table>

																							</div>	
																						</div>
																					</div></div></div></div>
																					<div class="row">
																						<div class="col-md-8 col-md-offset-2">
																							<div class="panel panel-primary">
																								<div class="panel-heading" style="text-align: center;">Edit Profile</div>
																								<div class="panel-body">

																									<form class="dv-updateProfile">
																										<input type="text" name="username" placeholder="please enter username here" class="form-control"><br>
																										<input type="text" name="email" class="form-control" placeholder="enter email here to update"><br>
																										<input type="number" name="phonenumber" placeholder="enter phonenumber to update" class="form-control"><br>
																										<input type="text" name="firstname" placeholder="enter firstname to update" class="form-control"><br>
																										<input type="text" name="lastname" class="form-control" placeholder="please enter last name to update "><br>
																										<input type="password" name="password" class="form-control" placeholder="please enter new password here"><br>
																										<div class="col-md-6 col-md-offset-6">
																											<button type="submit" class="btn btn-info"> Update
																											</div>
																										</form>
																									</div></div></div></div>

<?= DvJSSDK(); ?>
																									<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
																									<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
																								</div>
																							</body>
																							</html>