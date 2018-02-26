@extends('service_views.notify.master_layout')

@section('content')
    <div id="docContainer">
        <div>
            <p class="docintro">
               <span>Notify</span> is a <b>Devless</b> service that aids in sending notification to users via SMS,Email and push notification.<br>
               This docs shows how to call the Action class methods within your 
               <b>  
               <a href="https://docs.devless.io/docs/1.0/service#scripts" target="blank">Devless scripts</a>
               </b>
            </p>
             <hr>
        </div>
        <h3>SMS</h3>
        <p>This service uses <a href="https://www.twilio.com" target="blank">Twilio</a> for sms. Please create a Twilio account and setup your credentials by going to settings</p>
        <h5>Send Sms to random Users</h5>
        <P>You can send sms to random users using this Action class method <code class="language-php">sms($numbers,$message)</code>. <code class="language-php token function">sms</code> takes two parameters, 
        <code class="language-php">$numbers</code> which is an array of user numbers and <code class="language-php">$message</code> which is a string of the message to be sent to the users. <br><br>The code below shows how to call the method in your scripts.
        </P>
        <div class="code">        
            <pre class="language-php line-numbers"><code>
    -> import('notify')
    ->sms(['+233540420521'],'Welcome to our world')
            </code>
        </pre>
        </div>

        <h5>Send Sms with User Info</h5>
        <P>Send sms with user infomation with this Action class method  <br><code class="language-php">smsFromRecords($message, $userInfoColumns, $serviceName, $table, $usersNumberColumn , $conditionArray, $limit)</code>
        <ul>
            <li><code class="language-php">$message // message to send to users</code></li>
            <li><code class="language-php">$userInfoColumns // The column name of the extra user infomation to query from the service table</code></li>
            <li><code class="language-php">$usersNumberColumn // The table column that contains users number</code></li>
            <li><code class="language-php">$serviceName // The name of the service from whoms tables you would like to query</code></li>
            <li><code class="language-php">$table // The table name to query from</code></li>
            <li><code class="language-php">$conditionArray //an array of where clause conditions to attach to the query</code></li>
            <li><code class="language-php">$limit // Limit of the query results</code></li>
        </ul>
        <br>The code below shows how to call the method in your scripts.
        </P>
        <div class="code">        
            <pre class="language-php line-numbers"><code>
        -> import('notify')
        ->smsFromRecords('Hello @usernames. your email : @email and number : @phone is not valid',
        'username,id,phone,email',
        'sample_service','myappuser','phone',['username!=maxwell'],1)
            </code>
        </pre>
        </div>

        <div>
            <hr>
        </div>

        <h3>Email</h3>
        <p>This service uses <a href="https://www.sendgrid.com" target="blank">Sendgrid<sup>&reg;</sup></a> for emails. Please create a Sendgrid<sup>&reg;</sup> account and setup your credentials by going to settings</p>
        <h5>Send Email to random Users</h5>
        <P>You can send email to random users using this Action class method <code class="language-php">email($emails, $subject, $message)</code>.
         <code class="language-php token function">email</code> takes three parameters, 
        <code class="language-php">$emails</code> which is an array of user emails , <code class="language-php">$subject</code> which is the subject of the message to be sent to the users.
         <code class="language-php">$message</code> which the message body of the email. <br><br>The code below shows how to call the method in your scripts.
        </P>
        <div class="code">        
            <pre class="language-php line-numbers"><code>
        -> import('notify')
        ->email(['fizzbuzz@gmail.com','foobar@gmail.com'],'just trying','email working hurrey')
            </code>
        </pre>
        </div>

        <h5>send Email with User Info</h5>
        <P>Send sms with user infomation with this Action class method  <br><code class="language-php">emailFromRecords($mailsubject, $message, $userInfoColumns, $serviceName, $table, $usersEmailColumn, $conditionArray, $limit)</code>
        <ul>
            <li><code class="language-php">$mailsubject // email subject</code></li>
            <li><code class="language-php">$message // message to send to users</code></li>
            <li><code class="language-php">$userInfoColumns // The column name of the extra user infomation to query from the service table</code></li>
            <li><code class="language-php">$usersEmailColumn // The table column that contains users email</code></li>
            <li><code class="language-php">$serviceName // The name of the service from whoms tables you would like to query</code></li>
            <li><code class="language-php">$table // The table name to query from</code></li>
            <li><code class="language-php">$conditionArray //an array of where clause conditions to attach to the query</code></li>
            <li><code class="language-php">$limit // Limit of the query results</code></li>
        </ul>
        <br>The code below shows how to call the method in your scripts.
        </P>
        <div class="code">        
            <pre class="language-php line-numbers"><code>
        -> import('notify')
        ->emailFromRecords('sample subject','Hello @usernames. your email : @email and number : @phone is not valid',
        'username,id,phone,email',
        'sample_service','myappuser','email',['id=1'],1)
            </code>
        </pre>
        </div>

        <div>
            <hr>
        </div>

         <h3>Push</h3>
         <p>This service uses <a href="https://www.pusher.com" target="blank">Pusher<sup>&reg;</sup></a> for push notification. Please create a Pusher<sup>&reg;</sup> account and setup your credentials by going to settings.</p>
        <h5>send Push to random usuers</h5>
        <P>You can send push notification to random users using this Action class method <code class="language-php">push($channel, $event,$message)</code>.
         <code class="language-php token function">push</code> takes three parameters, 
        <code class="language-php">$channel</code> which is the channel the user listens to , <code class="language-php">$event</code> which is the event to trigger at the user's end.
         <code class="language-php">$message</code> which is the message body of the push notification. <br><br>The code below shows how to call the method in your scripts.
        </P>
        <div class="code">        
            <pre class="language-php line-numbers"><code>
        -> import('notify')
        ->push('my-channel','my-event','sample message')
            </code>
        </pre>
        </div>
        
        <h5>send Push with User Info</h5>
        <P>Send push with user infomation with this Action class method  <br><code class="language-php">pushFromRecords($event ,$message, $userInfoColumns, $serviceName, $table, $usersChannelColumn, $conditionArray, $limit)</code>
        <ul>
         
            <li><code class="language-php">$event // event to trigger at user's end</code></li>

            <li><code class="language-php">$message // message body of push notification</code></li>
            <li><code class="language-php">$userInfoColumns // The column names of the extra user infomation to query from the service table</code></li>
            <li><code class="language-php">$usersChannelColumn // The table column that contains users push channel ID</code></li>
            <li><code class="language-php">$serviceName // The name of the service from whoms tables you would like to query</code></li>
            <li><code class="language-php">$table // The table name to query from</code></li>
            <li><code class="language-php">$conditionArray //an array of where clause conditions to attach to the query</code></li>
            <li><code class="language-php">$limit // Limit of the query results</code></li>
        </ul>
        <br>The code below shows how to call the method in your scripts.
        </P>
        <div class="code">        
            <pre class="language-php line-numbers"><code>
        -> import('notify')
        ->pushFromRecords('app-passwordChangeEvent',
        'Hello @usernames. your email : @email and number : @phone is not valid',
        'usernames,id,phone,email','notify','myappuser','pusherid',['username!=james'],1)
            </code>
        </pre>
        </div>

    </div>
@endsection

