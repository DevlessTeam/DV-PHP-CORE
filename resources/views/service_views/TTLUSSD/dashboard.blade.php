<?php 

$user_profile = \Session::get('user_profile');
$user_id = ($user_profile != null)? $user_profile->id : '';
($user_id == '')? DvRedirect(DvNavigate($payload, 'index'), 0):'';

?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TTL Admin</title>
    <!-- Bootstrap Styles-->
    <link href="<?= DvAssetPath($payload, "css/bootstrap.css"); ?>" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="<?= DvAssetPath($payload, "js/morris/morris-0.4.3.min.css"); ?>" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="<?= DvAssetPath($payload, "css/custom-styles.css"); ?>" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><img src="<?= DvAssetPath($payload, "images/logo.png"); ?>" style="height: 50px; position: relative; bottom: 10px;"></a>
            </div>

            <ul class="nav navbar-top-links navbar-right">


                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-truck fa-fw"></i> New Tractor Request
                                </div>
                            </a>
                        </li>
                        <!-- <li class="divider"></li> -->
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">

                        <li>
                            <a href="#" onclick="SDK.call('TTLUSSD', 'logout', [], function(res) {
            if(res.payload.result){ window.location = '/service/TTLUSSD/view/index'; }
        })"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>
        <!--/. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">

                    <li>
                        <a class="active-menu" href="<?= DvNavigate($payload, "dashboard"); ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="<?= DvNavigate($payload, "tractor_requests"); ?>"><i class="fa fa-truck"></i> Tractor Requests</a>
                    </li>
                    <li>
                        <a href="<?= DvNavigate($payload, "farmers"); ?>"><i class="fa fa-user"></i> Farmers</a>
                    </li>
                    <li>
                        <a href="<?= DvNavigate($payload, "fbo"); ?>"><i class="fa fa-building-o"></i> FBO</a>
                    </li>
                    <li>
                         <a href="<?= DvNavigate($payload, "tractors"); ?>"><i class="fa fa-truck"></i> Tractors</a>
                    </li>
                    <li>
                        <a href="<?= DvNavigate($payload, "owners"); ?>"><i class="fa fa-group"></i> Tractor Owners</a>
                    </li>

                    <!-- <li>
                        <a href="<?= DvNavigate($payload, "transportation"); ?>"><i class="fa fa-car"></i> Transportation</a>
                    </li>-->

                    <li>
                        <a href="<?= DvNavigate($payload, "transactions"); ?>"><i class="fa fa-money"></i> Transactions</a>
                    </li>
                    <li>
                       <!-- <a href="<?= DvNavigate($payload, "map"); ?>"><i class="fa fa-map"></i> Map</a> -->
                       <a href="http://miitown.com" target="_blank"><i class="fa fa-map"></i>Map</a>
                    </li>

                </ul>

            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div class="row">
                    <div style="height: 300px;
    padding-left: 20px;
    padding-top: 40px;
    background-color: green;
    background: -webkit-linear-gradient(hsla(120,3%,87%,.45),rgba(9,148,13,.45)),url(<?= DvAssetPath($payload, "images/welcome.jpg");?>);
    background: linear-gradient(hsla(120,3%,87%,.45),rgba(9,148,13,.45)),url(<?=DvAssetPath($payload, "images/welcome.jpg"); ?>);
    background-position: 0 71%;
    background-size: cover;
    color: #fff;
    padding: 80px 30px;
    margin-bottom: 40px;">
                        <h1 style="font-size: 56px; margin-top: 50px;">Welcome <?= $user_profile->first_name." ".$user_profile->last_name ?>, here's what's new today:</h1>
                    </div>
            </div>
            <div id="page-inner">

                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Dashboard <small>Summary of operations</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-paper-plane fa-5x"></i>
                                <h3 id="pending_requests"></h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                                Pending Tractor Requests

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-truck fa-5x"></i>
                                <h3 id="available_trucks"></h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                                Available tractors

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-users fa-5x"></i>
                                <h3 id="farmers"></h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                                Farmers

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-paper-plane fa-5x"></i>
                                <h3 id="requests"></h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                                Total Tractor Requests

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-truck fa-5x"></i>
                                <h3 id="trucks"></h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                                Registered tractors

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-users fa-5x"></i>
                                <h3 id="owners"></h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                                Tractor Owners

                            </div>
                        </div>
                    </div>
                </div>
                 <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-building-o fa-5x"></i>
                                <h3 id="fbo"></h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                                FBO
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="<?= DvAssetPath($payload, "js/jquery-1.10.2.js"); ?>"></script>
    <!-- Bootstrap Js -->
    <script src="<?= DvAssetPath($payload, "js/bootstrap.min.js"); ?>"></script>
    <!-- Metis Menu Js -->
    <script src="<?= DvAssetPath($payload, "js/jquery.metisMenu.js"); ?>"></script>
    <!-- Custom Js -->
    <script src="<?= DvAssetPath($payload, "js/custom-scripts.js"); ?>"></script>
    <script src="<?= DvAssetPath($payload, "js/dashboard.js"); ?>"></script>
    <!-- DevLess JS SDK -->
    <?php 
        
        use App\Helpers\DataStore; 
        use App\Http\Controllers\ServiceController as service; 
        $instance = DataStore::instanceInfo();
        $app  = $instance['app'];

    ?>
    <script src="{{URL('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="<?= $app->token ?>"></script>

    <script>
        SDK.setToken("<?= \Session::get('user_token'); ?>");
    </script>

</body>

</html>
