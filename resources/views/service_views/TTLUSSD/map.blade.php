<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TTL Admin - Map</title>
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
                            <a href="/"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
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
                        <a href="<?= DvNavigate($payload, "index"); ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="<?= DvNavigate($payload, "tractor_requests"); ?>"><i class="fa fa-truck"></i> Tractor Requests</a>
                    </li>
                    <li>
                        <a href="<?= DvNavigate($payload, "farmers"); ?>"><i class="fa fa-user"></i> Farmers</a>
                    </li>
                    <li>
                         <a href="<?= DvNavigate($payload, "tractors"); ?>"><i class="fa fa-truck"></i> Tractors</a>
                    </li>
                    <li>
                        <a href="<?= DvNavigate($payload, "owners"); ?>"><i class="fa fa-group"></i> Tractor Owners</a>
                    </li>

                    <li>
                        <a href="<?= DvNavigate($payload, "transportation"); ?>"><i class="fa fa-car"></i> Transportation</a>
                    </li>

                    <li>
                        <a href="<?= DvNavigate($payload, "transactions"); ?>"><i class="fa fa-money"></i> Transactions</a>
                    </li>
                    <li>
                        <a class="active-menu" href="<?= DvNavigate($payload, "map"); ?>"><i class="fa fa-map"></i> Map</a>
                    </li>

                </ul>

            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <iframe src="http://www.miitown.com/" frameborder="0" style="width: 100%; height: 1000px;"></iframe>      
                </div>
            </div>
            <div id="page-inner">

              

          

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

</body>

</html>
