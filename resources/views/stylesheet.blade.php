@if (Request::secure())
  <link rel="shortcut icon" href="{{ secure_asset('favico.png') }}" type="image/png">
<!--right slidebar-->
  <link href="{{ secure_asset('/css/slidebars.css') }}" rel="stylesheet">

  <!--switchery-->
  <link href="{{ secure_asset('/js/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" media="screen" />

  <!--Form Wizard-->
  <link rel="stylesheet" type="text/css" href="{{ secure_asset('/css/jquery.steps.css') }}" />

  <!-- datatables css -->
  <link href="{{ secure_asset('/css/jquery.dataTables.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css" rel="stylesheet">

  <!--common style-->
  <link href="{{ secure_asset('/css/style.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('/css/style-responsive.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ secure_asset('/css/custom.css') }}">
  @if( \Request::path() == 'privacy' || \Request::path() == 'datatable' || \Request::path() == 'services')
  <link rel="stylesheet" href="{{ secure_asset('/css/helper.css') }}" media="screen" title="no title" charset="utf-8">
  <script src="{{ secure_asset('/js/jquery-1.10.2.min.js') }}"></script>
  <script src="{{ secure_asset('/js/bootstrap.min.js') }}"></script>
  @endif
@else

  <link rel="shortcut icon" href="{{ asset('favico.png') }}" type="image/png">
    <!--right slidebar-->
  <link href="{{asset('/css/slidebars.css') }}" rel="stylesheet">

  <!--switchery-->
  <link href="{{ asset('/js/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" media="screen" />

  <!--Form Wizard-->
  <link rel="stylesheet" type="text/css" href="{{ asset('/css/jquery.steps.css') }}" />

  <!-- datatables css -->
  <link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css" rel="stylesheet">

  <!--common style-->
  <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('/css/style-responsive.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('/css/custom.css') }}">
  @if( \Request::path() == 'privacy' || \Request::path() == 'datatable' || \Request::path() == 'services')
  <link rel="stylesheet" href="{{ asset('/css/helper.css') }}" media="screen" title="no title" charset="utf-8">
  <script src="{{ asset('/js/jquery-1.10.2.min.js') }}"></script>
  <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
  @endif

@endif

