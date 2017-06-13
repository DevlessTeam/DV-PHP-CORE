@extends('api_docs.apidoc')

@section('header')

    <!-- page head start-->
    <div class="page-head">
        <h3>
            API Console
        </h3>
        <span class="sub-title">Console/</span>

    </div>
    <!-- page head end-->
@endsection

@section('content')
    <section>
        <div class="wrapper">
            <div class="row">
                <div id="flash_msg" class="modal fade col-md-offset-4" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content" style="width:250px;height:3%;">
                            <div class="modal-body text-center" style="color: #fff;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-offset-2" id="con_data">
                    <form id="form_data" class="form-horizontal" action="#">
                        <section class="panel">
                            <header class="panel-heading">
                                API Console
                            </header>
                            <div class="panel-body">
                                <div class="form-group" >
                                    <label for="service" class="col-lg-2 col-sm-2 control-label">Service</label>
                                    <div class="col-lg-10">
                                        <select id="service" class="form-control m-b-10">
                                            <option disabled selected value> -- select a service -- </option>
                                            @foreach($services as $service)
                                                <option value="{{$service->id}}">{{$service->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="service" class="col-lg-2 col-sm-2 control-label">Table</label>
                                    <div class="col-lg-10">
                                        <select id="table" class="form-control m-b-10">
                                            <option disabled selected value> -- select a table -- </option>
                                        </select>
                                    </div>
                                    <label for="operation" class="col-lg-2 col-sm-2 control-label">Operation</label>
                                    <div class="col-lg-10">
                                        <select id="operation" class="form-control m-b-10">
                                            <option disabled selected value> -- select an operation -- </option>
                                            <option value="retrieve_all">QUERY TABLE (GET)</option>
                                            <option value="create">ADD RECORD (POST) </option>
                                            <option value="update">UPDATE RECORD(PATCH) </option>
                                            <option value="delete">DELETE RECORD (DELETE) </option>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="api_url" class="col-lg-2 col-sm-2 control-label">Endpoint</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="api_url" readonly="true">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button type="submit" class="btn btn-info pull-right"><i class="fa fa-play"></i> Run</button>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="query" class="panel">
                            <header class="panel-heading">
                                QUERY PARAMS
                            </header>
                            <div class="panel-body">
                                <div id="query_params" class="form-horizontal">
                                    <div class="form-group" >
                                        <label for="order" class="col-lg-2 col-sm-2 control-label">Order</label>
                                        <div class="col-lg-10">
                                            <input type="text" id="order-field" class="form-control"  name="order">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="where" class="col-lg-2 col-sm-2 control-label">Where</label>
                                        <div class="col-lg-5" >
                                            <input type="text" id="key-field" class="form-control" placeholder="key">
                                        </div>
                                        <div class="col-lg-5" >
                                            <input type="text" id="value-field" class="form-control" placeholder="value">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="size" class="col-lg-2 col-sm-2 control-label">Size</label>
                                        <div class="col-lg-10">
                                            <input type="number" id="size-field" class="form-control" name="size">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="related" class="col-lg-2 col-sm-2 control-label">Related</label>
                                        <div class="col-lg-10">
                                            <input type="text" id="related-field" class="form-control" name="related">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="search" class="col-lg-2 col-sm-2 control-label">Search</label>
                                        <div class="col-lg-5" >
                                            <input type="text" id="search-key" class="form-control" placeholder="key">
                                        </div>
                                        <div class="col-lg-5" >
                                            <input type="text" id="search-value" class="form-control" placeholder="value">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="body_params" class="panel">
                            <header class="panel-heading">
                                BODY PARAMS
                            </header>
                            <div class="panel-body">
                                <div id="req_payload" class="form-horizontal">
                                    <div class="form-group" >
                                        <label for="req_payload" class="col-lg-2 col-sm-2 control-label">Request Payload</label>
                                        <div class="col-lg-10">
                                            <div id="editor" style="height: 300px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="request" class="panel">
                            <header class="panel-heading">
                                REQUEST
                            </header>
                            <div class="panel-body">
                                <div id="req_url" class="form-horizontal">
                                    <div class="form-group" >
                                        <div class="col-lg-12">
                                            <textarea class="form-control" rows="3" cols="40" readonly=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="response" class="panel">
                            <header class="panel-heading">
                                RESPONSE
                            </header>
                            <div class="panel-body">
                                <div id="response" class="form-horizontal">
                                    <div class="form-group" >
                                        <div class="col-lg-12">
                                            <textarea id="response-field" class="form-control" rows="15" cols="40" readonly=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </form>

                </div>
            </div>
            <button type="button" id="scroll" name="button" class="btn btn-warning pull-right">Scroll Up</button>
        </div>
        <script src="{{ Request::secure(Request::root()).'/js/src-min-noconflict/ace.js' }}" type="text/javascript" charset="utf-8"></script>
        <script src="{{ Request::secure(Request::root()).'/js/ace/jquery-1.8.3.min.js' }}" type="text/javascript" charset="utf-8"></script>
        <script src="{{ Request::secure(Request::root()).'/js/framework/api-console.js'}}" type="text/javascript" charset="utf-8"></script>
    @endsection
