<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Devless is a backend as a service framework that provide developers an easier way to rollout their web and mobile platform ">
    <meta name="author" content="Devless">
    <meta name="keyword" content="Devless, opensource, BAAS, Backend as a service, robust, php, laravel ">
    <link rel="shortcut icon" href="javascript:;" type="image/png">

    <title>Devless 1.0.1 </title>

    <!--right slidebar-->
    <link href="{{ Request::secure(Request::root()).'/css/slidebars.css' }}" rel="stylesheet">

    <!--switchery-->
    <link href="{{ Request::secure(Request::root()).'/js/switchery/switchery.min.css'}}" rel="stylesheet" type="text/css" media="screen" />

    <!--Form Wizard-->
    <link rel="stylesheet" type="text/css" href="{{ Request::secure(Request::root()).'/css/jquery.steps.css' }}" />

    <!--common style-->
    <link href="{{ Request::secure(Request::root()).'/css/style.css' }}" rel="stylesheet">
    <link href="{{ Request::secure(Request::root()).'/css/style-responsive.css' }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ Request::secure(Request::root()).'/css/custom.css'}}">
    @if( \Request::path() == 'privacy' || \Request::path() == 'datatable' || \Request::path() == 'services')
      <link rel="stylesheet" href="{{ Request::secure(Request::root()).'/css/helper.css' }}" media="screen" title="no title" charset="utf-8">
      <script src="{{ Request::secure(Request::root()).'/js/jquery-1.10.2.min.js' }}"></script>
      <script src="{{ Request::secure(Request::root()).'/js/bootstrap.min.js' }}"></script>
    @endif
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->


</head>
<body onload="init()" class="sticky-header">

    <section>
      @if(\Request::path() != '/' && \Request::path() != 'setup')
        <div class="header-section">
          <!--toggle button start-->
          <a class="toggle-btn"><i class="fa fa-outdent"></i></a>
          <!--toggle button end-->
        </div>
        <!-- header section end-->
@endif
