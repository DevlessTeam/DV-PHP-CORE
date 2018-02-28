<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

    <!-- max-o-cdn -->
    <link rel="stylesheet" type="text/css" href="//oss.maxcdn.com/jquery.trip.js/3.3.3/trip.min.css"/>

    <style>
            html, body {
                background-color: #fff;
                font-family: 'Raleway', sans-serif;
                height: 100vh;
                margin: 0;
            }
            a{
                color: #FF7829;
            }
            .bg-faded{
                background-color: #156099;
            }
            .go-back {
                margin:0 0 0 70%;
            }
            .go-back a{
                color: #fff;
                padding: 10px 20px;
                background-color: rgba(219,99,30,1);
                border-radius: 8px;
                cursor: pointer;
            }
            .navbar .navbar-brand{
                font-size: 28px;
                font-style: italic;
                color: #fff;
            }
            .go-back a:hover{
                background-color: #FF7829;
            }
            .body{
                background-color:#F3F3F3;
                color: #000;
            }
            .hide{
                display: none;  
            }
            .show{
                display: block;
            }
            .fir-item{
                padding: 30px  80px 0 80px;
            }
            .sec-item{
                padding: 30px  30px 0 0px;
            }
            .sec-item form{
                padding: 5px  120px 0 120px;
            }
            .docs{
                padding: 30px 20px;
                color: #444;
                font-size: 18px;
                border-left: 1px dashed #555;
                padding-left: 50px;
            }
            .docs h3,.sec-item h3{
                color: #666;
                font-weight: bold;
                font-size: 22px;
            }
            #user-credentials{
                overflow-x: scroll;
            }
            .code{
                color: #000;
                background-color: #fff
            }

        </style>
  </head>
    <?php 
        use App\Helpers\DataStore;
        $instance = DataStore::instanceInfo();
        $app = $instance['app'];
    ?>
  <body>
        <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
          
          <a class="navbar-brand" href="#">
            Zoho
          </a>
            <div class="go-back changePostion step-one">
                 <a href="{{URL('/')}}/services">Back To Devless</a>
            </div>
        </nav>
        <div class="body">
            
            <div id="form">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 fir-item">
                            <div class="dv-notify-success" style="color: green;">Your Account has been set</div>
                            <form class="dv-add-oneto:zohoMail:settings">
                                <div class="form-group">
                                    <label for="sender_name">Pick a username</label>
                                    <input type="text" name="sender_name" class="form-control " placeholder="Enter a username" autocomplete="off" />   
                                </div>
                                <div class="form-group">
                                    <label for="sender_email">zoho Address</label>
                                    <input type="text" name="sender_email" class="form-control" placeholder="Enter you zoho address" autocomplete="off" /> 
                                </div>
                                <div class="form-group">
                                    <label for="api_secret">zoho Password</label>
                                    <input type="password" name="sender_password" class="form-control " placeholder="please provide your zoho password" autocomplete="off" />   
                                </div>
                                <button type="submit" id="submitData" class="btn btn-success">Submit</button>
                            </form>
                        </div>
                        <div class = "col-md-6 sec-item hide">
                            <table class="table table-striped" id="user-credentials">
                              <thead>
                                <tr>
                                  <th>Username</th>
                                  <th>Option</th>
                                </tr>
                              </thead>
                              <tbody class="dv-param:size:1:get-all:zohoMail:settings">
                                <tr>
                                  <td class="var-sender_name"></td>
                                  <td ><button type="submit" class="btn btn-primary dv-update">Update</button></td>
                                </tr>
                              </tbody>
                            </table>
                            <form class="dv-update-oneof:zohoMail:settings">
                                <h3 style="padding: 10px 0px 5px 0;">Update Data</h3>
                                <div class="form-group">
                                    <label for="sender_name">Pick a username</label>
                                    <input type="text" name="sender_name" class="form-control " placeholder="Enter a username" autocomplete="off" />   
                                </div>
                                <div class="form-group">
                                    <label for="sender_email">zoho Address</label>
                                    <input type="text" name="sender_email" class="form-control" placeholder="Enter you zoho address" autocomplete="off" /> 
                                </div>
                                <div class="form-group">
                                    <label for="api_secret">zoho Password</label>
                                    <input type="password" name="sender_password" class="form-control " placeholder="please provide your zoho password" autocomplete="off" />   
                                </div>
                                <button type="submit" id="submitData" class="btn btn-success">Submit</button>    
                             </form>
                        </div>  

                        <div class="col-md-6 docs">
                            <h3 class="changePostion step-two">zoho Docs</h3>
                            <p>zoho allows you to send out emails within DevLess using your zoho account.
                            <h3 class="">Getting Started With zoho</h3> 
                            <p>
                                Getting started with zoho platform is as easy as anything you can think of. 
                                <ul>
                                    <li>First and foremost, you will need a <a href="https://zoho.com" target="blank">zoho Account</a></li>
                                 
                                    <li>Next be sure to fill out the fields on the left with your email details  and save</li>
                                </ul>
                            </p>
                            <h3>Using It In The Service Rules</h3>
                            <p class="example">
                            Using it in the service rules is straightforward. The service contains the method send() which will send out the email to your the recipient. <br> 
                            <span class="code">send($subject, $message, [$recipient_email])</span> <br>    
                            <span class="code">$subject</span> - Text<br>
                            <span class="code">$message</span>  - Text<br>
                            <span class="code">$recipient_email</span> - email  you may add more than one recipient_email <br><br>
                            Example of using it in the Service Rules<br>
                                <span class="methods code">
                                    ->run('zohoMail', 'send', ['My Subject', '<b>Message Body</b>', ['edmond@devless.io', 'team@devless.io']])
                                          
                                </span>
                            </p>
                    </div> 


                    </div>
                </div>  
            </div>
                 
            

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script src="{{URL('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="<?= $app->token ?>"></script>
    <script type="text/javascript">
       

        //sdk call to  toggle hide of .sec-item and .first-item
        SDK.queryData("zohoMail","settings",{},function(resp){
            if(resp.payload.results[0].length < 0){
                $(".fir-item").addClass("hide");
                $(".sec-item").addClass("show"); 
            }else{
                $(".sec-item").removeClass("hide");
                $(".sec-item").addClass("show");
                $(".fir-item").addClass("hide");
            }
        });

        //method to reload when user enter data
        $('#submitData').click( function(){
            setTimeout(function(){
                window.location.reload();    
            },1000)
            
        })
    </script>
  </body>
</html>

