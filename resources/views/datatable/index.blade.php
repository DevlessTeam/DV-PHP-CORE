
@extends('layout')

@section('header')
      <div class="page-head">
        <h3>
            Data Table
        </h3>
        <button type="button" id="addbtn" class="btn btn-primary pull-right" style="position: relative; bottom: 23px; margin-left: 2%" disabled="true"><i class="fa fa-plus"></i> Add Data</button>
        <button type="button" onclick="refreshTable()" class="btn btn-primary pull-right" style="position: relative; bottom: 23px;" ><i class="fa fa-repeat"></i> Refresh</button>

        <span class="sub-title">Data Table/</span>
    </div>
@endsection

@section('content')
<style media="screen">
td {
    overflow: hidden;
    text-overflow: ellipsis;
}

#dtRow {
    cursor: pointer;
}

</style>

<div class="wrapper">
    <div class="row">
        <div id="datatable">
            <label for="service" class="col-lg-2 col-sm-2 control-label">Service </label>
            <div class="col-md-3">
                <select id="service" name="service" class="form-control m-b-10">
                    @if(\Request::has('service_name'))
                        <option value="{{$service->id}}">{{$service->name}}</option>
                    @else
                        <option disabled selected value> -- select a service -- </option>
                        @foreach($services as $service)
                            <option value="{{$service->id}}">{{$service->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <label for="table_name" class="col-sm-2 control-label col-md-offset-1">Table </label>
            <div class="col-md-3">
                <select id="table_name" name="table_name" class="form-control m-b-10">
                    @if(\Request::has('table_name'))
                        @foreach($tables as $table)
                            <option value="{{$table->id}}">{{json_decode($table->schema)->name}}</option>
                        @endforeach
                    @else
                        <option disabled selected value> -- select a table -- </option>
                    @endif
                </select>
            </div>
        </div>

        <div class="col-sm-12 tab" id="loader">
            <section class="panel"></section>
        </div>
    </div>
</div>

<div id="flash_msg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="container col-md-12 col-sm-12" style="color: #000; background-color: #f3f3f3; padding: 10px;">
        <form id="modalform" action="" method="post">
          <div class="form-group" id="formData">
          </div>
          <button type="submit" class="btn btn-default" name="Update">Update</button>
          <button type="submit" class="btn btn-danger" name="Delete">Delete</button>
          <button type="submit" class="btn btn-warning" name="Cancel" id="cancel">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="add_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="container col-md-12 col-sm-12" style="color: #000; background-color: #f3f3f3; padding: 10px;">
        <form id="modaladd" action="" method="post">
          <div class="form-group" id="addform">
          </div>
          <button type="submit" id="submit" class="btn btn-default" name="Submit">Submit</button>
          <button type="submit" class="btn btn-warning" name="Cancel">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="error_flash" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content col-md-12">
        {{--  <textarea id="error_display" cols="30" rows="5" wrap="hard"></textarea>  --}}
        <pre style="margin-top: 10px; padding: 20px; border: 0px; background: #f5f5f5"></pre>
    </div>
  </div>
</div>

<?php

    use App\Helpers\DataStore;
    $instance = DataStore::instanceInfo();
    $app  = $instance['app'];

?>
<script src="{{URL('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="<?= $app->token ?>"></script>
<script src="{{URL('/')}}/js/framework/datatable.js"></script>

@endsection
