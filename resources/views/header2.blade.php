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
	<link href="{{ Request::secure(Request::root()).'/css/custom.min.css' }}" rel="stylesheet">
	<link href="{{ Request::secure(Request::root()).'/css/proxima-nova.css' }}" rel="stylesheet">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]><script src="js/html5shiv.js"></script><script src="js/respond.min.js"></script><![endif]-->

    <style type="text/css">
        .logo-wrap img {
		    height: 40px;
		    margin-left: 10px;
		    margin-top: 10px;
		}       
    </style>
    
</head>
<body class="nav-md footer_fixed">
	<div class="container body">
		<div class="main_container">