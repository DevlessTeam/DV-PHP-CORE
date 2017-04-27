<?php 

$user_profile = \Session::get('user_profile');
$user_id = ($user_profile != null)? $user_profile->id : '';
($user_id == '')? DvRedirect(DvNavigate($payload, 'index'), 0):'';

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TTL Admin - FBOs</title>
	<!-- Bootstrap Styles-->
    <link href="<?= DvAssetPath($payload, "css/bootstrap.css"); ?>" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="<?= DvAssetPath($payload, "css/custom-styles.css"); ?>" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
	<!-- TABLE STYLES-->
    <link href="<?= DvAssetPath($payload, "js/dataTables/dataTables.bootstrap.css"); ?>" rel="stylesheet" />
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
                <a class="navbar-brand" href="<?= DvNavigate($payload, "index"); ?>"><img src="<?= DvAssetPath($payload, "images/logo.png"); ?>" style="height: 50px; position: relative; bottom: 10px;"></a>
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
                                    <i class="fa fa-car fa-fw"></i> New Tractor Request
                                </div>
                            </a>
                        </li>
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
                        <a href="<?= DvNavigate($payload, "dashboard"); ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="<?= DvNavigate($payload, "tractor_requests"); ?>"><i class="fa fa-truck"></i> Tractor Requests</a>
                    </li>
                    <li>
                        <a href="<?= DvNavigate($payload, "farmers"); ?>"><i class="fa fa-user"></i> Farmers</a>
                    </li>
                    <li>
                        <a class="active-menu" href="<?= DvNavigate($payload, "fbo"); ?>"><i class="fa fa-building-o"></i> FBO</a>
                    </li>
                    <li>
                         <a href="<?= DvNavigate($payload, "tractors"); ?>"><i class="fa fa-truck"></i> Tractors</a>
                    </li>
                    <li>
                        <a href="<?= DvNavigate($payload, "owners"); ?>"><i class="fa fa-group"></i> Tractor Owners</a>
                    </li>

                   <!-- <li>
                        <a href="<?= DvNavigate($payload, "transportation"); ?>"><i class="fa fa-car"></i> Transportation</a>
                    </li> -->

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
        <div id="page-wrapper" >
            <div id="page-inner">
			 <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Tractor Owners <small>All FBOs under TTL.</small>
                            <button class="btn btn-danger pull-right" style="background: #ff5722" data-toggle="modal" data-target="#addFbo">Add FBO</button>
                        </h1>
                    </div>
                </div> 
                 
                <div class="row">
                <div class="col-md-12">
                    <!-- Add farmers modal -->
                    <div class="modal fade-scale" tabindex="-1" role="dialog" id="addFbo">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content" style="background-color: whitesmoke;">
                            <div class="modal-header" style="background: #388e3c;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" style="color: white;">Add FBO</h4>
                            </div>
                            <div class="modal-body">
                               <form class="form-horizontal" id="fbo-form" method="post" action="#">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-10">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Adam Muhideen">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="col-sm-2 control-label">Phone</label>
                                        <div class="col-sm-10">
                                        <input type="text" class="form-control" name="phone" id="phone" placeholder="0266783477">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="location" class="col-sm-2 control-label">Location</label>
                                        <div class="col-sm-10">
                                        <input type="text" class="form-control" name="location" id="location" placeholder="East Legon">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="acres" class="col-sm-2 control-label">No. of Farmers</label>
                                        <div class="col-sm-10">
                                        <input type="number" class="form-control" name="no_of_farmers" id="no_of_farmers" placeholder="20" min="0">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-success" value="Save" id="save_farmer">
                                    </div>
                                </form>
                            </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             FBOs
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="fbos-table" width="100%">
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
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
    <!-- Custom JS -->
    <script src="<?= DvAssetPath($payload, "js/fbo.js"); ?>"></script>
     <!-- DATA TABLE SCRIPTS -->
    <script src="<?= DvAssetPath($payload, "js/dataTables/jquery.dataTables.js"); ?>"></script>
    <script src="<?= DvAssetPath($payload, "js/dataTables/dataTables.bootstrap.js"); ?>"></script>
    <!-- DevLess JS SDK -->
    <?php 
        
        use App\Helpers\DataStore; 
        use App\Http\Controllers\ServiceController as service; 
        $instance = DataStore::instanceInfo();
        $app  = $instance['app'];

    ?>
    <script src="{{URL('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="<?= $app->token ?>"></script>
    
   
</body>

</html>
