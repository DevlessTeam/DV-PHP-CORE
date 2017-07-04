<?php
use App\Helpers\DataStore;
  $app = DataStore::instanceInfo()['app'];
  
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="DevLess is a backend as a service framework that provide developers an easier way to rollout their web and mobile platform ">
  <meta name="author" content="DevLess">
  <meta name="keyword" content="DevLess, opensource, BAAS, Backend as a service, robust, php, laravel ">

  <link rel="shortcut icon" href="/favico.png" type="image/png">

  <title>DevLess {{config('devless')['version']}}</title>

  <!--right slidebar-->
  <link href="{{ Request::secure(Request::root()).'/css/slidebars.css' }}" rel="stylesheet">

  <!--switchery-->
  <link href="{{ Request::secure(Request::root()).'/js/switchery/switchery.min.css'}}" rel="stylesheet" type="text/css" media="screen" />

  <!--Form Wizard-->
  <link rel="stylesheet" type="text/css" href="{{ Request::secure(Request::root()).'/css/jquery.steps.css' }}" />

  <!-- datatables css -->
  <link href="{{ Request::secure(Request::root()).'/css/jquery.dataTables.min.css'  }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css" rel="stylesheet">

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
        <br>
        <button class="btn btn-sm btn-info pull-right" data-toggle="modal" data-target="#sdk-connect" style="margin-right: 20%"> <i class="fa fa-plug"></i> Connect to App</button>
      <a class="btn btn-sm btn-warning pull-right" data-toggle="modal" href="https://devless.gitbooks.io/devless-docs-1-3-0/content/building-an-app-with-devless.html" target="blank" style="margin-right: 2%"><i class="fa fa-book"></i> Quick Guide</a>
        <!--toggle button end-->
      </div>
      <!-- header section end-->
      
    <div class="modal fade" id="sdk-connect" tabindex="-1" role="dialog" aria-labelledby="quickGuideLabel">
     <div class="modal-dialog" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plug"></i> SDK Options</h4>
         </div>
         <div class="modal-body">

          <ul class="nav nav-tabs" id="options-tab">
            <li><a data-target="#web" data-toggle="tab">Web</a></li>
            <li><a data-target="#android" data-toggle="tab">Android</a></li>
            <li><a data-target="#raw" data-toggle="tab">Raw</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="web">
<pre><code class="language-markup"><xmp style="display: inline;">
<script src="{{URL::to('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="{{$app->token}}"></script></xmp></code>
</pre>

        </div>
        <div class="tab-pane" id="android"><center>NA</center></div>
        <div class="tab-pane" id="raw"><center>
          Domain URL: {{URL::to('/')}}<br>
          Token: {{$app->token}}
        </center></div>
      </div>

    </div>
  </div>
</div>
</div>
@endif
 disabled">Connect to my App</button> at the bottom to get the connection details.</li>
              <br>
              <li>Load <code>sample.html</code> in your browser of choice.</li>
            </ol>
          </div>
          <div class="modal-footer">
            <a href="#" target="_blank" class="btn btn-primary">Watch it all <i class="fa fa-video-camera"></i></a>
            <a href="https://docs.devless.io/docs/1.0/html-sdk" target="_blank" class="btn btn-primary">Learn More <i class="fa fa-book"></i></a>

          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="sdk-connect" tabindex="-1" role="dialog" aria-labelledby="quickGuideLabel">
     <div class="modal-dialog" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plug"></i> SDK Options</h4>
         </div>
         <div class="modal-body">
{{--
          <ul class="nav nav-tabs" id="options-tab">
            <li><a data-target="#web" data-toggle="tab">Web</a></li>
            <li><a data-target="#android" data-toggle="tab">Android</a></li>
            <li><a data-target="#ios" data-toggle="tab">IOS</a></li>
            <li><a data-target="#raw" data-toggle="tab">Raw</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="web">
<pre><code class="language-markup"><xmp style="display: inline;">
<script src="{{URL::to('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="{{$app->token}}"></script></xmp></code>
</pre>

        </div>
        <div class="tab-pane" id="android"><center>NA</center></div>
        <div class="tab-pane" id="ios"><center>NA</center></div>
        <div class="tab-pane" id="raw"><center>
          Domain URL: {{URL::to('/')}}<br>
          Token: {{$app->token}}
        </center></div>
      </div>
--}}
    </div>
    <div class="modal-footer">
      // TODO provide links to how to use each SDK to access and work with DevLess 
    </div>
  </div>
</div>
</div>
@endif

<div id="sample-frontend" style="display: none">

  <!-- //devless notifications -->
  <div class="dv-notify-success">Address action successfully</div> <!--Shows success message only if action is successful-->

  <!--Shows failure message only if action is fails-->
  <div class="dv-notify-failed">Address action could not be added</div>

  <!--Shows Debug message from DevLess-->
  <div class="dv-notify"></div>
  <br><br>
  <!-- dv-add-oneto:service_name:table_name //this will allow the form submit data to DevLess-->
  <h3>Start by adding Addresses</h3>
  <form class="dv-add-oneto:addressbook:addresses">
    <input type="text" name="name" placeholder="Please enter name here">
    <input type="email" name="email" placeholder="Please provide email">
    <input type="text" name="location">
    <button type="submit">Add Address</button>
  </form>

  <br><br><br><br>

  <h3>Addresses from DevLess</h3>
  <table>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Location</th>
      <th>Options</th>
    </tr>
    <!-- To query data from Devless use the dv-get-all:service_name:table_name class-->
    <tbody class="dv-get-all:addressbook:addresses">
      <tr>
        <td class="var-name">...</td><!-- Prefixing the fields with var will render them on screen eg: var-name -->
        <td class="var-email">...</td>
        <td class="var-location">...</td>
        <td>
          <button class="dv-update">Update</button>
          <button class="dv-delete">Delete</button>
        </td>
      </tr>
    </tbody>
  </table>

  <br><br><br><br>

  <!-- Clicking the update button from any of the table results will auto fill this for submission-->
  <h3>Update Form</h3>
  <form class="dv-update-oneof:addressbook:addresses">
    <input type="text" name="name" placeholder="Please enter name here">
    <input type="email" name="email" placeholder="Please provide email">
    <input type="text" name="location">
    <button type="submit">Update Address</button>
  </form>


</div>
