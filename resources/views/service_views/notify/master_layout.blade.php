<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="">
    <!-- Latest compiled and minified CSS & JS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400|Sansita+One" rel="stylesheet">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link href="<?= DvAssetPath($payload, 'css/toastr.min.css'); ?>" rel="stylesheet" />
    <link href="<?= DvAssetPath($payload, 'css/introjs.min.css'); ?>" rel="stylesheet" />
    <link href="<?= DvAssetPath($payload, 'css/notify.css'); ?>" rel="stylesheet" />
    <link href="<?= DvAssetPath($payload, 'css/prism.css'); ?>" rel="stylesheet" />

    
    </style>
</head>
<body>
<nav class="navbar">
<span>
<h3 class="banner" data-step="1" data-intro='<b>Welcome to Notify</b>. <br> Hope its ok to walk you through. (or not. then skip)' data-position='right'>Notify</h3>
<h6 class="mini-text">A Devless service</h6>
</span>
    <div id="state" class="grid_4 alpha">
        <div class="gps_ring"></div>          
     </div>
     <div class="fill"></div>
     <a  href="{{URL('/services')}}" class="back" 
     data-step="3" data-intro='When you are all done. press here to go back to devless' data-position='left'><h3>Back To Devless</h3></a>
</nav>
  <div class="main-container">
    <div class="side-bar" data-step="2" data-intro='You can navigate to each settings by clicking the respective icon on the side bar' data-position='right'>
        <ul>
            <li><a href="<?= DvNavigate($payload, 'index'); ?>">
            <i class="fa fa-commenting-o fa-2x" aria-hidden="true"></i>
             <h4 class="menu-text">SMS</h4> </a></li>

             <li><a href="<?= DvNavigate($payload, 'email'); ?>">
             <i class="fa fa-envelope-o fa-2x" aria-hidden="true"></i>
             <h4 class="menu-text">Email</h4> </a></li>

             <li>
             <a href="<?= DvNavigate($payload, 'push'); ?>">
             <i class="fa fa-podcast fa-2x" aria-hidden="true"></i>
             <h4 class="menu-text">Push</h4> </a></li><li>

             <a href="<?= DvNavigate($payload, 'settings'); ?>">
             <i class="fa fa-cogs fa-2x" aria-hidden="true"></i>
             <h4 class="menu-text">Settings</h4> </a></li>

             <li><a href="<?= DvNavigate($payload, 'docs'); ?>">
             <i class="fa fa-book fa-2x" aria-hidden="true"></i>
             <h4 class="menu-text">Docs</h4> </a></li>
        </ul>
    </div>
    <div class="main-content">
     <div class="loader">
        <div id="fountainG">
            <div id="fountainG_1" class="fountainG"></div>
            <div id="fountainG_2" class="fountainG"></div>
            <div id="fountainG_3" class="fountainG"></div>
            <div id="fountainG_4" class="fountainG"></div>
            <div id="fountainG_5" class="fountainG"></div>
            <div id="fountainG_6" class="fountainG"></div>
            <div id="fountainG_7" class="fountainG"></div>
            <div id="fountainG_8" class="fountainG"></div>
        </div>
     </div>
        

            @yield('content')
            
        
    </div>

  </div>
<?php 
        
        use App\Helpers\DataStore; 
        $instance = DataStore::instanceInfo();
        $app  = $instance['app'];

?>
<!-- jQuery Js -->
<script src="<?= DvAssetPath($payload, 'js/jquery-1.10.2.js'); ?>"></script>
<!-- bootstrap js-->  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<script src="{{URL('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="<?= $app->token ?>"></script>
<!-- notify Js -->
    <script src="<?= DvAssetPath($payload, 'js/toastr.min.js'); ?>"></script>
    <script src="<?= DvAssetPath($payload, 'js/intro.min.js'); ?>"></script>
    <script src="<?= DvAssetPath($payload, 'js/notify.js'); ?>"></script>
    <script src="<?= DvAssetPath($payload, 'js/prism.js'); ?>"></script>
     @yield('customjs');

</body>
</html>
