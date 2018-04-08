  <link rel="shortcut icon" href="{{Request::secure(Request::path()).'/favico.png'}}" type="image/png">
  <!--right slidebar-->

  <link href="{{Request::secure(Request::path()).'/css/slidebars.css'}}" rel="stylesheet"/>

  <!--switchery-->
  <link href="{{Request::secure(Request::path()).'/js/switchery/switchery.min.css'}}" rel="stylesheet" type="text/css" media="screen" />

  <!--Form Wizard-->
  <link rel="stylesheet" type="text/css" href="{{Request::secure(Request::path()).'/css/jquery.steps.css'}}" />

  <!-- datatables css -->
  <link href="{{Request::secure(Request::path()).'/css/jquery.dataTables.min.css'}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">

  <!--common style-->
  <link href="{{Request::secure(Request::path()).'/css/style.css'}}" rel="stylesheet">
  <link href="{{Request::secure(Request::path()).'/css/style-responsive.css'}}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{Request::secure(Request::path()).'/css/custom.css'}}">
  @if( \Request::path() == 'privacy' || \Request::path() == 'datatable' || \Request::path() == 'services')
  <link rel="stylesheet" href="{{Request::secure(Request::path()).'/css/helper.css'}}" media="screen" title="no title" charset="utf-8">
  <script src="{{Request::secure(Request::path()).'/js/jquery-1.10.2.min.js'}}"></script>
  <script src="{{Request::secure(Request::path()).'/js/bootstrap.min.js'}}"></script>
  @endif

  <!-- Toastr -->
  <link rel="stylesheet" type="text/css" href="{{Request::secure(Request::path()).'/css/toastr.css'}}" />

