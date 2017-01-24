@extends('layout')

@section('header')
  <!-- page head start-->
  <div class="page-head">

    <h3>Import / Export</h3><span class="sub-title">Migrate Services/</span>
  </div><!-- page head end-->
@endsection

@section('content')

  @include('error')

    <!--body wrapper start-->
    <div class="wrapper">
      <div class="row">
        <div class="col-lg-8 col-md-offset-2">

          <section class="isolate-tabs">
            <br>
            <header class="panel-heading tab-dark">
              <ul class="nav nav-tabs nav-justified">
                <li class="active">
                  <a data-toggle="tab" href="#jus">Export Service</a>
                </li>

                <li class="">
                  <a data-toggle="tab" href="#jtab">Import Service</a>
                </li>
              </ul>
            </header>
            <div class="panel-body">
              <div class="tab-content">
                <div class="tab-pane active" id="jus">
                  <form action="{{ route('migrate.store') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="io_type" value="export"/>
                    <input type="hidden" name="app_name" value="{{$app->name}}"/>
                    <div class="form-group  @if($errors->has('service_export')) has-error @endif">
                      <br><br><br>
                      <label for="g-title" class="col-lg-2 col-sm-2 control-label">Select Service/App</label>
                      <div class="col-lg-10">
                        <select name="service_name" class="form-control m-b-10">
                          @foreach($services as $option_index => $option_value )
                            <option value="{{$option_value->name}}">{{ucwords($option_value->name)}}</option>
                          @endforeach
                          @if(sizeof($services) !== 0)
                            <option value="*">Entire App ({{$app->name}})</option>
                          @endif
                        </select>
                        @if($errors->has("service_export"))
                          <span class="help-block">{{ $errors->first("service_export") }}</span>
                        @endif
                      </div>
                    </div>

                    <br><br><br>
                    <button class="btn btn-info" type="submit"><i class="fa fa-download"></i> Export</button>
                  </form>
                </div>
                <div class="tab-pane" id="mtab">
                </div>

                <div class="tab-pane @if($errors->has('service_import')) has-error @endif" id="jtab">

                  <form enctype="multipart/form-data" action="{{ route('migrate.store') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="io_type" value="import"/>
                    <div class="form-group">
                      <br><br><br>
                      <label for="g-title" class="col-lg-2 col-sm-2 control-label">Pick Service to import</label>
                      <div class="col-lg-10">
                        <input class="form-control" name="service_file" id="g-title"
                        placeholder="service name" type="file">
                        @if($errors->has("service_import"))
                          <span class="help-block">{{ $errors->first("service_import") }}</span>
                        @endif

                        <br><br>
                      </div>
                    </div>
                    <button class="btn btn-info" type="submit"><i class="fa fa-upload"></i> Import</button>
                  </form>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
      <script>
      function init(){
        @if(session('package'))auto_download('{{session('package')}}'); @endif
      }
      function auto_download(package_name){

        url = '/download/'+package_name,
        win = window.open(url, '_blank');
        win.focus();
      }


      </script>
    @endsection
