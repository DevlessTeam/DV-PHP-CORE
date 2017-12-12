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
              <img src="<?= DvAssetPath($payload,'/logo.png') ?>" alt="cloudinary image" height="40">
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
                            <div class="dv-notify-success" style="color: green;">Your data has been entered</div>
                            <form class="dv-add-oneto:uploader:cloud_details">
                                <div class="form-group">
                                    <label for="cloud_name">Cloud Name</label>
                                    <input type="text" name="cloud_name" class="form-control " placeholder="Enter Cloud Name" autocomplete="off" />   
                                </div>
                                <div class="form-group">
                                    <label for="api_key">API Key</label>
                                    <input type="text" name="api_key" class="form-control" placeholder="Enter API Key" autocomplete="off" /> 
                                </div>
                                <div class="form-group">
                                    <label for="api_secret">API Secret</label>
                                    <input type="text" name="api_secret" class="form-control " placeholder="Enter API Secret" autocomplete="off" />   
                                </div>
                                <button type="submit" id="submitData" class="btn btn-success">Submit</button>
                            </form>
                        </div>
                        <div class = "col-md-6 sec-item hide">
                            <table class="table table-striped" id="user-credentials">
                              <thead>
                                <tr>
                                  <th>Cloud Name</th>
                                  <th>Option</th>
                                </tr>
                              </thead>
                              <tbody class="dv-get-all:uploader:cloud_details">
                                <tr>
                                  <td class="var-cloud_name"></td>
                                  <td ><button type="submit" class="btn btn-primary dv-update">Update</button></td>
                                </tr>
                              </tbody>
                            </table>
                            <form class="dv-update-oneof:uploader:cloud_details">
                                <h3 style="padding: 10px 0px 5px 0;">Update Data</h3>
                                <div class="form-group">
                                    <label for="cloud-name">Cloud Name</label>
                                    <input type="text" name="cloud_name" class="form-control " placeholder="Enter Cloud Name">   
                                </div>
                                <div class="form-group">
                                    <label for="cloud-name">API Key</label>
                                    <input type="text" name="api_key" class="form-control " placeholder="Enter API Key"> 
                                </div>
                                <div class="form-group">
                                    <label for="cloud-name">API Secret</label>
                                    <input type="text" name="api_secret" class="form-control" placeholder="Enter API Secret">   
                                </div>
                                
                                <button type="submit" class="btn btn-success">Update</button>
                            </form>
                        </div>  

                        <div class="col-md-6 docs">
                            <h3 class="changePostion step-two">Cloudinary Docs</h3>
                            <p>The cloudinary service contains a method that allows for easy uploading of images to the the <a href="https://cloudinary.com" target="_blank">cloudinary</a> platform. The method can be called within the rules section or try using them via the <a href="https://devless.gitbooks.io/devless-docs-1-3-0/content/sdks.html" target="_blank">SDK</a></p>
                            <h3 class="">Getting Started With Cloudinary</h3> 
                            <p>
                                Getting started with cloudinary platform is as easy as anything you can think of. 
                                <ul>
                                    <li>First and foremost, Login to <a href="https://cloudinary.com/console">cloudinary</a> to access your cloud name, API key and API secret (nb: these are credentials that help us commmunicate with the cloudinary API)</li>
                                    <li>Copy and paste these values to the form on your left</li>
                                </ul>
                            </p>
                            <h3>Using It In The Service Rules</h3>
                            <p class="example">
                            Using it in the service rules is straightforward. The service contains the method upload() which helps in uploading your images online. <br> 
                            upload($image,[ $height , $width ]) <br>    
                            <span class="code">$image</span> - base64 image<br>
                            <span class="code">$height</span>  - number [optional] sets the image height<br>
                            <span class="code">$width</span> - number [optional] set the image width <br><br>
                            Example of using it in the Service Rules<br>
                                <span class="methods code">
                                    ->run('uploader', 'upload', [$input_image, 400, 400])->storeAs($input_image)
                                          
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
    <script src="<?= DvAssetPath($payload, 'js/trip.min.js') ?>"></script>   

    <script type="text/javascript">
        var changePostion = new Trip([
                {sel : $(".changePostion.step-one"),   content : "Welcome, to the cloudinary service, Let's take a tour", position : "w"},
                {sel : $(".changePostion.step-one"),   content : "Click on this button to go back", position : "s"},
                {sel : $(".changePostion.step-two"),   content : "Take a look at the docs. It was crafted with a beginner in mind", position : "w"},
                // {sel : $(".changePostion.read-docs"),   content : "This service uses cloudinary to upload images online. Create an account online. ", position : "n"},
                // {sel : $(".changePostion.read-docs"),   content : "Learn more by reading the docs", position : "w"},
                // {sel : $(".changePostion.step-two"),   content : "Form for entering your details", position : "e"},
                // {sel : $(".changePostion.step-six"),   content : "You can now use it on the service rules", position : "w"}
            ],{
                showNavigation : true,
                showCloseBox  : true,
                delay          : -1
            });
        $(document).ready(function(){
            changePostion.start();
        });

        //sdk call to  toggle hide of .sec-item and .first-item
        SDK.queryData("uploader","cloud_details",{},function(resp){
            if(resp.payload.results[0].got_data){
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

