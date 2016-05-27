@extends('layout')

@section('header')
  <!-- page head start-->
  <div class="page-head">

    <h3>Migrate Services</h3><span class="sub-title"></span>
  </div><!-- page head end-->
@endsection

@section('content')
  @include('error')



  <!--body wrapper start-->
  <div class="wrapper no-pad col-md-10 col-md-offset-1 col-sm-12">
    <section class="isolate-tabs">
      <header class="panel-heading tab-dark ">
        <ul class="nav nav-tabs nav-justified">
          <li class="active">
            <a data-toggle="tab" href="#jus">Export Service</a>
          </li>

          <li class="">
            <a data-toggle="tab" href="#jtab">Import Service</a>
          </li>
        </ul>
      </header>
      <div class="panel-body ">
        <div class="tab-content">
          <div class="tab-pane active" id="jus">
            <form action="{{ route('migrate.store') }}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="io_type" value="export"/>
              <div class="form-group @if($errors->has('service_export')) has-error @endif">
                <br><br><br>
                <label for="g-title" class="col-lg-2 col-sm-2 control-label">Service to export</label>
                <div class="col-md-7">
                <select name="service_name" class="form-control m-b-10">
                  @foreach($services as $option_index => $option_value )
                    <option value="{{$option_value->name}}">{{$option_value->name}}</option>
                  @endforeach
                </select>
              </div>
                @if($errors->has("service_export"))
                  <span class="help-block">{{ $errors->first("service_export") }}</span>
                @endif
              </div>

              <br><br><br>
              <button class="btn btn-info" type=
              "submit">Export</button>
            </form>
          </div>
          <div class="tab-pane" id="mtab">
          </div>

          <div class="tab-pane @if($errors->has('service_import')) has-error @endif" id="jtab">

            <form enctype="multipart/form-data" action="{{ route('migrate.store') }}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="io_type" value="import"/>

              <br><br><br>
              <label for="g-title">Pick Service to import</label>
              <input class="form-control" name="service_file" id="g-title"
              placeholder="service name" type="file">
              @if($errors->has("service_import"))
                <span class="help-block">{{ $errors->first("service_import") }}</span>
              @endif

              <br><br><br><br>
              <button class="btn btn-info" type="submit">Import</button>
            </div>
          </form>
        </div>

      </div>
    </div>

    <script>
    function init(){
      @if(session('package'))auto_download('{{session('package')}}') @endif
    }
    function auto_download(package_name){

      url = 'download/'+package_name,
      win = window.open(url, '_blank');
      win.focus();
    }


    </script>
  @endsection
