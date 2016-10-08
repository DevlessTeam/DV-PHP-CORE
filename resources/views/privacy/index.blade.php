@extends('api_docs.apidoc')

@section('header')

<!-- page head start-->
    <div class="page-head">
        <h3>
          Privacy
        </h3>
        <span class="sub-title">Privacy/</span>

    </div>
    <!-- page head end-->
@endsection

@section('content')
  <section>
    <div class="wrapper">
      {{-- <div class="row"> --}}
        <div class="col-lg-9 col-md-offset-1">
          <form id="form_data" class="form-horizontal" method="post" action="/privacy/0">
            <input type="hidden" name="_method" value="PUT">
            <section class="panel">
              <header class="panel-heading">
                ENDPOINTS ACCESS RULES
              </header>
              <div class="panel-body">
                <div class="form-group" >
                  <div class="row">
                  <label for="service" class="col-lg-2 col-sm-2 control-label">Service</label>
                  <div class="col-lg-9 col-md-9 col-sm-10">
                    <select id="service" name="service" class="form-control m-b-10">
                      <option disabled selected value> -- select a service -- </option>
                      @foreach($services as $service)
                        <option value="{{$service->id}}">{{ucwords($service->name)}}</option>
                      @endforeach
                    </select>
                  </div>
                  </div>
                  <div class="row">
                    <label for="query" class="col-lg-2 col-sm-2 control-label">Query Access</label>
                    <div class="col-lg-9 col-md-9 col-sm-10">
                      <select id="query" name="query" class="form-control m-b-10">
                        <option disabled selected value> -- select a right -- </option>
                        <option value="0">PRIVATE</option>
                        <option value="1">PUBLIC </option>
                        <option value="2">AUTHENTICATE </option>
                      </select>
                    </div>
                  </div>
                  <div>
                    <div class="row">
                      <label for="create" class="col-lg-2 col-sm-2 control-label">Create Access</label>
                      <div class="col-lg-9 col-md-9 col-sm-10">
                        <select id="create" name="create" class="form-control m-b-10">
                          <option disabled selected value> -- select a right -- </option>
                          <option value="0">PRIVATE</option>
                          <option value="1">PUBLIC </option>
                          <option value="2">AUTHENTICATE </option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <label for="update" class="col-lg-2 col-sm-2 control-label">Update Access</label>
                    <div class="col-lg-9 col-md-9 col-sm-10">
                      <select id="update" name="update" class="form-control m-b-10">
                        <option disabled selected value> -- select a right -- </option>
                        <option value="0">PRIVATE</option>
                        <option value="1">PUBLIC </option>
                        <option value="2">AUTHENTICATE </option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <label for="delete" class="col-lg-2 col-sm-2 control-label">Delete Access</label>
                    <div class="col-lg-9 col-md-9 col-sm-10">
                      <select id="delete" name="delete" class="form-control m-b-10">
                        <option disabled selected value> -- select a right -- </option>
                        <option value="0">PRIVATE</option>
                        <option value="1">PUBLIC </option>
                        <option value="2">AUTHENTICATE </option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <label for="schema" class="col-lg-2 col-sm-2 control-label">Schema Access</label>
                    <div class="col-lg-9 col-md-9 col-sm-10">
                      <select id="schema" name="schema" class="form-control m-b-10">
                        <option disabled selected value> -- select a right -- </option>
                        <option value="0">PRIVATE</option>
                        <option value="1">PUBLIC </option>
                        <option value="2">AUTHENTICATE </option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <label for="view" class="col-lg-2 col-sm-2 control-label">Service Views Access</label>
                    <div class="col-lg-9 col-md-9 col-sm-10">
                      <select id="view" name="view" class="form-control m-b-10">
                        <option disabled selected value> -- select a right -- </option>
                        <option value="0">PRIVATE</option>
                        <option value="1">PUBLIC </option>
                        <option value="2">AUTHENTICATE </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-9">
                    <button id="submit_btn" type="submit" class="btn btn-info pull-right">Save</button>
                  </div>
                </div>
              </div>
            </section>
          </form>
        </div>
      </div>
    {{-- </div> --}}
  </section>

  <script>
    window.onload(function() {
      $('#service').change(function() {
        var service = $('#service').val();
        $('#form_data').attr('action', '/privacy/'+service);
        $.get('/privacy/'+service+'/get', function (data) {
          var write_access = JSON.parse(data);
          $('#query').val(write_access.query);
          $('#create').val(write_access.create);
          $('#update').val(write_access.update);
          $('#delete').val(write_access.delete);
          $('#schema').val(write_access.schema);
          $('#script').val(write_access.script);
          $('#view').val(write_access.view);
        });
      });
    }());
  </script>
@endsection
