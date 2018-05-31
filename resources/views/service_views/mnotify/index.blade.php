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
          
          <a class="navbar-brand" href="https://www.mnotify.com/">
            Mnotify
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
                            <form class="dv-add-oneto:mnotify:settings">
                                <div class="form-group">
                                    <label for="api_key">Set your Mnotify Key</label>
                                    <input type="text" name="api_key" class="form-control " placeholder="Enter your key" autocomplete="off" />   
                                </div>
                                
                                <button type="submit" id="submitData" class="btn btn-success">Save</button>    
                            </form>
                        </div>
                        <div class = "col-md-6 sec-item hide">
                            <table class="table table-striped" id="user-credentials">
                              <thead>
                                <tr>
                                  <th>Api Key</th>
                                  <th>Option</th>
                                </tr>
                              </thead>
                              <tbody class="dv-param:size:1:get-all:mnotify:settings">
                                <tr>
                                  <td class="var-api_key"></td>
                                  <td ><button type="submit" class="btn btn-primary dv-update">Update</button></td>
                                </tr>
                              </tbody>
                            </table>
                            <form class="dv-update-oneof:mnotify:settings">
                                <h3 style="padding: 10px 0px 5px 0;">Update Api Key</h3>
                                <div class="form-group">
                                    <input type="text" name="api_key" class="form-control " placeholder="Enter your key" autocomplete="off" />   
                                </div>
                                
                                <button type="submit" id="submitData" class="btn btn-success">Upadate</button>    
                             </form>
                        </div>  

                        <div class="col-md-6 docs">
                            <h3 class="changePostion step-two"><a href="https://www.mnotify.com" >Mnotify</a> Docs</h3>
                            <p><a href="https://www.mnotify.com">Mnotify</a> is a platform for sending SMS. This service leverages on the platform to send out SMSs.
                            <h3 class="">Getting Started With <a href="https://www.mnotify.com">MNotify</a></h3> 
                            <p>
                                Follow the following steps to integrate <a href="https://www.mnotify.com">MNotify</a> into your DevLess app . 
                                <ul>
                                    <li>First you will have to create an accouint with <a href="https://www.mnotify.com">Mnotify</a></li>
                                    <li>You will also need to generate an API from the Dashboard</li>
                                    <li>Next be sure to fill out the fields on the left and save</li>
                                    <li>Finally set this API using the form to the left side <-</li>
                                </ul>
                            </p>
                            <h3>Using It In The Service Rules</h3>
                            <p class="example">
                            Using it in the service rules is straightforward. The service contains the method sendSMS($sender_name, $phone, $message) which will send out the SMS to your the recipient. <br> 
                            send($sender_name, $message, $phone_number) <br>    
                            <span class="code">$sender_name</span> - Text<br>
                            <span class="code">$message</span>  - Text<br>
                            <span class="code">$phone_number</span> - phone number you may add more than one phone number <br><br>
                            Example of using it in the Service Rules<br>
                                <span class="methods code">
                                    ->run('mnotify', 'sendSMS', ['Devless', 'message goes here', '02405434354', '02723432432'])
                                          
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
        SDK.queryData("mnotify","settings",{},function(resp){
            if(resp.payload.results.length > 0){
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

