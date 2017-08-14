<?php
use App\Helpers\DataStore;
  $app = DataStore::instanceInfo()['app'];

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
<<<<<<< HEAD
	<tr class="dv-get-all:{{$service_name}}:{{$table_name}}">
=======
	<tbody class="dv-get-all:{{$service_name}}:{{$table_name}}">
	<tr>
>>>>>>> develop
		@foreach($fields as $field)
			@if($field != 'devless_user_id')
				<td class="var-{{$field}}"></td>

			@endif
		@endforeach
		<td><button class="dv-delete">Delete</button></td>
		<td><button class="dv-update">update</button></td>
	</tr>
<<<<<<< HEAD
</table>

<div class="dv-get-all:{{$service_name}}:{{$table_name}}"><button class="dv-delete">sdfs</button><span class="var-name"></span></div>

=======
	</tbody>
</table>

>>>>>>> develop
<script src="{{URL::to('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="{{$app->token}}"></script>

</div>
<button onclick="download()">Download</button>
<script>
	function download() {
		var aTag = document.body.appendChild(document.createElement("a"));
		aTag.href = 'data:text/html'+document.getElementById('CRUD').innerHTML;
		aTag.download = "{{$service_name}}_{{$table_name}}.html";
		aTag.click();
	}
</script>
</body>
</html>


