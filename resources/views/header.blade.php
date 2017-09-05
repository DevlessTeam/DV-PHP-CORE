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

  <!-- start Mixpanel --><script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
mixpanel.init("ce7dd2fc4b5246ae1fa7c9d00cec362a");</script><!-- end Mixpanel -->
<script type="text/javascript">
    mixpanel.track("visit");
</script>
  <body onload="init()" class="sticky-header">
    <section>
      @if(\Request::path() != '/' && \Request::path() != 'setup' && \Request::path() != 'recover_password')
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
