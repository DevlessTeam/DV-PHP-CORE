<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<meta name="description" content="DevLess is a backend as a service framework that provide developers an easier way to rollout their web and mobile platform ">
	<meta name="author" content="DevLess">
	<meta name="keyword" content="DevLess, opensource, BAAS, Backend as a service, robust, php, laravel ">
	<link rel="shortcut icon" href="favico.png" type="image/png">

	<title>DevLess - {{config('devless')['version']}}</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.css" rel="stylesheet">
	<link href="{{ Request::secure(Request::root()).'/css/gent-custom.min.css' }}" rel="stylesheet">
	<link href="{{ Request::secure(Request::root()).'/css/proxima-nova.css' }}" rel="stylesheet">
	<link href="{{ Request::secure(Request::root()).'/css/shCore.css' }}" rel="stylesheet">
	<link href="{{ Request::secure(Request::root()).'/css/shCoreDefault.css' }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
	<link href="{{ Request::secure(Request::root()).'/css/dv-style.css' }}" rel="stylesheet">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]><script src="js/html5shiv.js"></script><script src="js/respond.min.js"></script><![endif]-->

	<style type="text/css">
		body{
			font-size: 14px;
			font-family: 'Open Sans', "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
			background: #F5F5F7;
		}
		.main_menu {
			color: #797979;
			font-family: "Varela Round",sans-serif;
			font-size: 14px;
		}
		.nav_menu {
			background: #fff;
		}
		.logo-wrap img {
    height: 35px;
    margin-left: 41px;
    margin-top: 15px;
    opacity: 0.8;
}
.sidebar-footer {
    background: #313a46;
}
.sidebar-footer a {
    background: #2c3541;
    color: #98a6ad;
}
.nav.side-menu > li > a:hover, .nav.side-menu > li.active > a {
    background: #313A46;
    box-shadow: none;
    color: #fff;
}
.nav.child_menu li {
    padding-left: 50px;
}
		.nav_title {
			background: #172d44;
		}
		h1, h2, h3, h4, h5, h6 {
			color: #4e5b68;
			letter-spacing: -0.2px;
			font-family: "Proxima Nova",Helvetica,Arial,sans-serif;
		}
		h3 {
			font-size: 25px;
		}
		body .container.body .right_col {
			background: #f5f5f7;
		}
		.x_panel {
			background-color: white;
			border: 1px solid rgba(43, 50, 57, 0.15);
			border-radius: 3px;
			margin-bottom: 40px;
		}
		.page-title .title_left h3 {
			margin-top: 15px;
		}
		.addon-btn i.pull-right {
			border-radius: 0 2px 2px 0;
		}

		.addon-btn i {
			background-color: rgba(0, 0, 0, 0.1);
			border-radius: 2px 0 0 2px;
			height: 35px;
			line-height: 35px;
			margin: -6px 10px -6px -12px;
			text-align: center;
			width: 35px;
		}
		.title-btn {
			padding: 0;
		}
		.page-title .title_right .title-btn {
			margin: 0 0 10px;
		}
		.user-profile.dropdown-toggle {
			padding-right: 25px;
		}
		.main_menu .fa {
		    margin-right: 15px;
		    text-align: center;
		    vertical-align: middle;
		    width: 20px;
		}
		.left_col, .nav_title {
			background: #36404E;
		}
		.nav.child_menu > li > a, .nav.side-menu > li > a {
		    color: #98a6ad;
		    font-weight: 500;
		}
		.main_menu .fa-chevron-right {
		    margin-right: 0;
		}
		#sidebar-menu .nav > li > a {
		   padding: 13px 20px 12px;
		}
		footer {
			border-top: 1px solid #d9dee4;
		}
		.x_title {
			border-width: 1px;
		}

	</style>

</head>
<body class="nav-md footer_fixed">
	<div class="container body">
		<div class="main_container">