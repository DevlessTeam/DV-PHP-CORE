@extends('service_views.notify.master_layout')

@section('content')
<div class="action-content">
    <div class="withInfo">
    <h4 class="action-title">Send Push Notification with User Info</h4>
    <hr>
    	<div class="form-container">
    		<form id="pushWithInfo" action="#" method="POST" class="form-horizontal" role="form">
    				<input type="text" name="" id="usersChannelColumn" class="form-control" value="" required="required" placeholder="Channel column" title=""
                    data-step="4" data-intro="Enter the column of the table that contain user pusher channel" data-position='right'>
                    <br>

                    <!-- <input type="text" name="" id="usersChannel" class="form-control" value="" required="required" placeholder="Channel column" title="">
                                        <br> -->
    				<input type="text" name="" id="event" class="form-control" value="" required="required" placeholder="Event" title=""
                    data-step="5" data-intro="Enter event name to trigger at users end" data-position='right'>
					<br>
    				<input type="text" name="" id="userInfoColumns" class="form-control" value=""  required="required" placeholder="Enter extra user infomation column names" title="">
					<br>
					<input type="text" name="" id="condition" class="form-control" value="" placeholder="Query Condition" title="">
					<br>
    				<input type="text" name="" id="limit" class="form-control" value="" placeholder="Query Limit" title="">
    				<br>
    				<input type="text" name="" id="serviceName" class="form-control" value="" required="required" placeholder="Service Name" title="">
					<br>
    				<input type="text" name="" id="table" class="form-control" value="" required="required" placeholder="Table Name" title="">
					<br>
    				<textarea name="" id="usermessage" class="form-control" rows="6" required="required" placeholder="Enter Message"></textarea>
    				<br> <br>
    				<div class="form-group">
    					<div class="col-sm-12 text-center">
    						<button type="submit" class="btn btn-lg btn-primary" data-step="6" data-intro="click to push notification to users" data-position='right'> 
    						<i class="fa fa-paper-plane" aria-hidden="true"></i> Push</button>
    					</div>
    				</div>
    		</form>
    	</div>
    </div>
    <div class="noInfo">
      <h4 class="action-title">Send Push Notification to random Users</h4>
      <hr>
    	<div class="form-container">
    		<form  id="randomPushForm" action="" method="POST" class="form-horizontal" role="form">
    		    <label>Channel</label>
    			<input type="text" name="" id="randomUserChannel" class="form-control" value="" required="required" placeholder="Enter User Channel" title=""
                data-step="7" data-intro="Enter user pusher channel" data-position='left'>
    				<br> <br>
    			<label>Event</label>
    			<input type="text" name="" id="randomEvent" class="form-control" value="" required="required" placeholder="Enter Event Name" title=""
                 data-step="8" data-intro="Enter event to trigger at user end" data-position='left'>
					<br> <br>
    			 <label>Message</label>
  				<textarea name="" id="randomMessage" class="form-control" rows="6" required="required" placeholder="Enter Message"
                data-step="9" data-intro="Enter message to user" data-position='left'></textarea>
					<br><br>
    				<div class="form-group">
    					<div class="col-sm-12 text-center">
    						<button type="submit" class="btn btn-lg btn-primary"
                            data-step="10" data-intro="click to push notifications to users" data-position='left'> 
    						<i class="fa fa-paper-plane" aria-hidden="true"></i> Push</button>
    					</div>
    				</div>
    		</form>
    	</div>
    </div>
</div>
@endsection


@section('customjs')
<script type="text/javascript">
 var pushIntro = introJs();
   pushIntro.oncomplete(function() {
    localStorage.setItem('seenPushtour', "true");
    });

   pushIntro.onexit(function() {
   localStorage.setItem('seenPushtour', "true");
   });
  var doneTour = localStorage.getItem('seenPushtour') === "true";
  if (!doneTour){   
    pushIntro.start().goToStep(4);
  }

  

</script>
@endsection