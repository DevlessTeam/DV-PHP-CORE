@extends('service_views.notify.master_layout')

@section('content')
<div class="action-content">
    <div class="settings left-border">
        <h4 class="action-title">Twilio settings <sub><a href="https://www.twilio.com/">create account</a></sub></h4>
        <hr>
        <div class="form-container">
            <form id="smsSettings" action="#" method="POST" class="form-horizontal" role="form">
                    <label>Sender Number</label>
                    <input type="text" name="" id="senderNumber" class="form-control" value="" required="required" placeholder="Your Twilio sender number" title="">
                    <br>
                    <label>Authentication Token</label>
                    <textarea name="" id="authToken" class="form-control" rows="2" required="required" placeholder="Twilio authentication Token"></textarea>
                    <br>
                    <label>Account ID</label>
                    <textarea name="" id="accountId" class="form-control" rows="2" required="required" placeholder="Twilio Account ID"></textarea>
                    <br> <br>
                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-lg btn-success"> 
                            <i class="fa fa-check" aria-hidden="true"></i> Update Sms</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    <div class="settings left-border">
        <h4 class="action-title">SendGrid Settings <sub><a href="https://sendgrid.com/">create account</a></sub></h4>
        <hr>
        <div class="form-container">
            <form id="emailSettings" action="" method="POST" class="form-horizontal" role="form">
                <label>Sender Email</label>
                    <input type="text" name="" id="senderEmail" class="form-control" value="" required="required" placeholder="Sender Email" title="">
                    <br>
                    <label>Sender Name</label>
                    <input type="text" name="" id="senderName" class="form-control" value="" required="required" placeholder="Sender Name" title="">
                    <br>
                    <label>API KEY</label>
                    <textarea name="" id="apiKey" class="form-control" rows="2" required="required" placeholder="Sendgrid API key"></textarea>
                    <br> <br>
                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-lg btn-success"> 
                            <i class="fa fa-check" aria-hidden="true"></i> Update Email</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    <div class="settings left-border">
        <h4 class="action-title">Push Settings<sub><a href="https://pusher.com/">create account</a></sub></h4>
        <hr>
        <div class="form-container">
            <form id="pushsettings" action="" method="POST" class="form-horizontal" role="form">
                <label>APP ID</label>
                    <input type="text" name="" id="appID" class="form-control" value="" required="required" placeholder="Pusher APP ID" title="">
                    <br>
                    <label>APP KEY</label>
                    <input type="text" name="" id="appkey" class="form-control" value="" required="required" placeholder="Pusher App Key" title="">
                    <br>
                    <label>APP SECRET</label>
                    <input type="text" name="" id="appsecrete" class="form-control" value="" required="required" placeholder="Pusher App Secret" title="">
                    <br>
                    <label>APP OPTION</label>
                    <select name="" id="appOptions" class="form-control" required="required">
                        <option value="eu">Ireland (eu)</option>
                        <option value="us2">US East Coast(us2)</option>
                        <option value="mt1">US East Coast(mt1)</option>
                        <option value="ap1">Singapore(ap1)</option>
                        <option value="ap2">Mumbai India(ap2)</option>
                    </select>
                    <br>
                    <label>Broadcast Event</label>
                    <input type="text" name="" id="brodEvent" class="form-control" value="" required="required" placeholder="General Broadcast Event" title="">
                    <br>
                    <label>Broadcast Channel</label>
                    <input type="text" name="" id="brodChannel" class="form-control" value="" required="required" placeholder="General Broadcast Channel" title="">
                    <br>
                    
                    <br> <br>
                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-lg btn-success"> 
                            <i class="fa fa-check" aria-hidden="true"></i> Update Push</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
@endsection

