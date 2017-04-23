@extends('service_views.notify.master_layout')

@section('content')
<div class="action-content">
    <div class="withInfo">
    	<h4 class="action-title">Send Emails with User Info</h4>
    	<hr>
    	<div class="form-container">
    		<form id="EmailWithInfo" action="#" method="POST" class="form-horizontal" role="form">
    				<input type="text" name="" id="usersEmailColumn" class="form-control" value="" required="required" placeholder="Email Column" title=""
                    data-step="4" data-intro="Enter the column of the table that contain user emails" data-position='right' />
					<br>
    				<input type="text" name="" id="userInfoColumns" required="required" class="form-control" value=""  placeholder="Enter extra user infomation column names" title="" />
					<br>
					<input type="text" name="" id="condition" class="form-control" value=""  placeholder="Query Condition" title="" />
					<br>
    				<input type="text" name="" id="limit" class="form-control" value=""  placeholder="Query Limit" title="" />
    				<br>
    				<input type="text" name="" id="serviceName" class="form-control" value="" required="required" placeholder="Service Name" title="" />
					<br>
    				<input type="text" name="" id="table" class="form-control" value="" required="required" placeholder="Table Name" title="" />
					<br>
			
    				<input type="text" name="" id="emailsubject" class="form-control" value="" required="required" placeholder="eMail Subject" title=""
                    data-step="5" data-intro="Ente email subject" data-position='right'>
					<br>
    				<textarea name="" id="message" class="form-control" rows="6" required="required" placeholder="Enter Message"
                    data-step="6" data-intro="Ente email message" data-position='right'></textarea>
    				<br> <br>
    				<div class="form-group">
    					<div class="col-sm-12 text-center">
    						<button type="submit" class="btn btn-lg btn-primary"
                            data-step="7" data-intro="click here to send emails" data-position='right'> 
    						<i class="fa fa-paper-plane" aria-hidden="true"></i> Send Email</button>
    					</div>
    				</div>
    		</form>
    	</div>
    </div>
    <div class="noInfo">


    	<h4 class="action-title">Send Emails to random Users</h4>
    	<hr>
    	<div class="form-container">
    		<form id="randomEmailForm" action="" method="POST" class="form-horizontal" role="form">
    		    <label>Emails</label>
    			<textarea name="" id="randomEmails" class="form-control" rows="3" required="required" placeholder="Enter Emails sperated by a comma ( , )"
                data-step="8" data-intro="Enter user emails" data-position='left'></textarea>
    				<br> <br>
    			<label>Subject</label>
    			<input type="text" name="" id="randomSubject" class="form-control" value="" required="required" placeholder="Enter Email subject " title=""
                data-step="9" data-intro="Ente email subject" data-position='left'>
					<br> <br>
    			 <label>Message</label>
  				<textarea name="" id="randomMessage" class="form-control" rows="6" required="required" placeholder="Enter Message" 
                data-step="10" data-intro="Enter email message" data-position='left'></textarea>
					<br><br>
    				<div class="form-group">
    					<div class="col-sm-12 text-center">
    						<button type="submit" class="btn btn-lg btn-primary"
                            data-step="11" data-intro="click here to send emails" data-position='left'> 
    						<i class="fa fa-paper-plane" aria-hidden="true"></i> Send Email</button>
    					</div>
    				</div>
    		</form>
    	</div>
    </div>
</div>
@endsection

@section('customjs')
<script type="text/javascript">
   
    var emailIntro = introJs();
   emailIntro.oncomplete(function() {
    localStorage.setItem('seenEmailtour', "true");
    });

   emailIntro.onexit(function() {
   localStorage.setItem('seenEmailtour', "true");
   });
  var doneTour = localStorage.getItem('seenEmailtour') === "true";
  if (!doneTour){   
    emailIntro.start().goToStep(4);
  }

</script>
@endsection