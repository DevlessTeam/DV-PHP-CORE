@extends('service_views.notify.master_layout')



@section('content')
<div class="action-content">
    <div class="withInfo">
    	<h4 class="action-title">Send Sms with User Info</h4>
        <hr>
    	<div class="form-container">
    		<form  id="smsWithInfo" action="#" method="POST" class="form-horizontal" role="form">
    				<input type="text" name="" id="usersNumberColumn" class="form-control"  required="required" placeholder="User Number Column" title=""
                    data-step="4" data-intro="Enter the column of the table that contain user numbers" data-position='right'>
					<br>
    				<input type="text" name="" id="userInfoColumns" required="required" class="form-control"   placeholder="Enter extra user infomation column names" title=""
                    data-step="5" data-intro='enter user extra information to query form the table. eg. username,phone  <br> You can reference it later in your message by preceding it with "@". eg "@username"' data-position='right'>
					<br>
					<input type="text" name="" id="condition" class="form-control"   placeholder="Query Condition" title=""
                    data-step="6" data-intro='you can set query conditions to narrow your query.<br> eg "username!=kofi,email=user@mail.com" <br>
                    Dont forget to seperate each condition with a comma ( , )' data-position='right'>
					<br>
    				<input type="number" name="" id="limit" class="form-control"   placeholder="Query Limit" title=""
                    data-step="7" data-intro='enter a number to limit your query' data-position='right'>
    				<br>
    				<input type="text" name="" id="serviceName" class="form-control"  required="required" placeholder="Service Name" title=""
                    data-step="8" data-intro='enter the name of the service you want to query. Note: You can query from any table belonging to your <b>Devless</b> Instance' data-position='right'>
					<br>
    				<input type="text" name="" id="table" class="form-control"  required="required" placeholder="Table Name" title=""
                    data-step="9" data-intro='Enter the Name of the table  to query from.<br> <b>Note</b> : Table should belong to the Service Name you entered' data-position='right'>
					<br>
			
					<br>
    				<textarea name="" id="message" class="form-control" rows="6" required="required" placeholder="Enter Message"
                    data-step="10" data-intro='Enter message to send to users' data-position='right'></textarea>
    				<br> <br>
    				<div class="form-group">
    					<div class="col-sm-12 text-center">
    						<button type="submit" class="btn btn-lg btn-primary"
                            data-step="11" data-intro='Click here to send message to users' data-position='right'> 
    						<i class="fa fa-paper-plane" aria-hidden="true"></i> Send SMS</button>
    					</div>
    				</div>
    		</form>
    	</div>
    </div>
    <div class="noInfo">
    	<h4 class="action-title">Send Sms to random Users</h4>
        <hr>
    	<div class="form-container">
    		<form id="randomSmsForm" action="#" method="POST" class="form-horizontal" role="form">
    		    <label>Numbers</label>
    			<textarea name="" id="randomUserNumbers" class="form-control" rows="3" required="required" placeholder="Enter Numbers sperated by a comma ( , )"
                 data-step="12" data-intro='Enter numbers of users. eg +233279898784,+233247728987' data-position='left'></textarea>
    				<br> <br>
    			 <label>Message</label>
  				<textarea name="" id="randomMessage" class="form-control" rows="6" required="required" placeholder="Enter Message"
                data-step="13" data-intro='Enter message to send to users' data-position='left'></textarea>
					<br><br>
    				<div class="form-group">
    					<div class="col-sm-12 text-center">
    						<button type="submit" class="btn btn-lg btn-primary"
                            data-step="14" data-intro='click here to send message to users' data-position='left'> 
    						<i class="fa fa-paper-plane" aria-hidden="true"></i> Send SMS</button>
    					</div>
    				</div>
    		</form>
    	</div>
    </div>
</div>
@endsection


@section('customjs')
<script>
  var smsIntro = introJs();
   smsIntro.oncomplete(function() {
    localStorage.setItem('seenSmStour', "true");
    });

   smsIntro.onexit(function() {
   localStorage.setItem('seenSmStour', "true");
   });
  var doneTour = localStorage.getItem('seenSmStour') === "true";
  if (!doneTour){   
    smsIntro.start();
  }
</script>
@endsection