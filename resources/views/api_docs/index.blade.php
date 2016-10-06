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
                                        <select id="service" name="service" class="form-control m-b-10">
                                            <option disabled selected value> -- select a service -- </option>
                                            @foreach($services as $service)
                                                <option value="{{$service->id}}">{{$service->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="service" class="col-lg-2 col-sm-2 control-label">Table</label>
                                    <div class="col-lg-10">
                                        <select id="table" name="table" class="form-control m-b-10">
                                            <option disabled selected value> -- select a table -- </option>
                                        </select>
                                    </div>
                                    <label for="operation" class="col-lg-2 col-sm-2 control-label">Operation</label>
                                    <div class="col-lg-10">
                                        <select id="operation" name="operation" class="form-control m-b-10">
                                            <option disabled selected value> -- select an operation -- </option>
                                            <option value="retrieve_all">QUERY TABLE (GET)</option>
                                            <option value="create">ADD RECORD (POST) </option>
                                            <option value="update">UPDATE RECORD(PATCH) </option>
                                            <option value="delete">DELETE RECORD (DELETE) </option>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="api_url" class="col-lg-2 col-sm-2 control-label">Api Url</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="api_url" name="api_url" readonly="true">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button type="submit" class="btn btn-info pull-right">Run</button>
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
                                            <input type="text" id="key-field" class="form-control" name="key" placeholder="key">
                                        </div>
                                        <div class="col-lg-5" >
                                            <input type="text" id="value-field" class="form-control" name="value" placeholder="value">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="size" class="col-lg-2 col-sm-2 control-label">Size</label>
                                        <div class="col-lg-10">
                                            <input type="number" id="size-field" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="related" class="col-lg-2 col-sm-2 control-label">Related</label>
                                        <div class="col-lg-10">
                                            <input type="text" id="related-field" class="form-control" >
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
                                            <textarea name="req_url" class="form-control" rows="3" cols="40" readonly=""></textarea>
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
                                            <textarea id="response-field" name="response" class="form-control" rows="15" cols="40" readonly=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </form>

                </div>
            </div>
        </div>
        <script src="{{ url('/js/src-min-noconflict/ace.js') }}" type="text/javascript" charset="utf-8"></script>
        <script src="{{ url('/js/ace/jquery-1.8.3.min.js') }}" type="text/javascript" charset="utf-8"></script>
        <script>

        window.onload(function () {
            document.getElementById('query').style.display = 'none';
            document.getElementById('body_params').style.display = 'none';
            document.getElementById('request').style.display = 'none';
            document.getElementById('response').style.display = 'none';

            //Handles URL generation
            var service_name;
            var service_id;
            $('#service').change(function () {
                $('#table').html('');
                $('#operation').prop('selectedIndex',0);
                service_name = $('#service option:selected').text();
                service_id = $('#service option:selected').val();

                $.get('console/'+service_id, function(data) {
                    var table = data;
                    for (var i = 0; i < table.length; i++) {
                        $("#table").append("<option>"+$.parseJSON(table[i].schema).name+"</option>");
                    }
                })
            });

            //Handles table change
            $('#table').change(function() {
            $('#operation').prop('selectedIndex',0);
            $('#body_params').hide();
            $('#response').hide();
            });

            //texteditor for payload
            var editor = ace.edit("editor");
            editor.setTheme("ace/theme/xcode");
            editor.getSession().setMode("ace/mode/json");

            // Handles the form rendering
            var request_type;
            var table_name;
            $('#operation').change(function () {
                $('#api_url').val('api/v1/service/'+service_name+'/db');
                request_type = $(this).val();
                table_name = $('#table option:selected').text();
                if (request_type == "retrieve_all") {
                    $('#query').show();
                    $('#body_params').hide();
                    $('#response').hide();

                } else {
                    $.get('/console/'+service_id+'/'+service_name+'/'+table_name, function (data) {
                      console.log(data);
                        var schema = data;
                        var values = {};
                        for (var i = 0; i < schema.length; i++) {
                            values[schema[i].name] = "";
                        }
                        if (request_type === 'create'){
                            // var json = JSON.stringify(JSON.parse('{"resource":[{"name":"'+table_name+'","field":['+JSON.stringify(values)+']}]}'), undefined, 4);
                            var json = JSON.stringify(JSON.parse('['+JSON.stringify(values)+']'), undefined, 4);
                        } else if (request_type === 'update') {
                            var json = JSON.stringify(JSON.parse('[{"where":"id, ","data":[{"key":"value"}]}]'), undefined, 4);
                        } else if (request_type === 'delete') {
                            var json = JSON.stringify(JSON.parse('[{"delete":"true","where":"id, "}]'), undefined, 4);
                        }
                        editor.setValue(json);
                    });

                    $('#body_params').show();
                    $('#query').hide();
                    $('#response').hide();
                }

            });

            // Handling requests and response
            $('#form_data').submit(function(e){
                e.preventDefault();
                $('#response-field').text('');

                // Handles GET requests
                if (request_type === "retrieve_all") {
                    var order = $('#order-field').val();
                    var key = $('#key-field').val();
                    var value = $('#value-field').val();
                    var size = $('#size-field').val();
                    var related = $('#related-field').val();

                    if (size == '' && order == '' && key != '' && value != '') {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&where='+key+','+value, function(data) {
                            statuscheck(data);
                        });
                    } else if (size == '' && order != '' && key != '' && value != '') {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&where='+key+','+value+'&order='+order, function(data) {
                            statuscheck(data);
                        });
                    } else if (size != '' && key == '' && value == '' && order == '') {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&size='+size, function(data) {
                            statuscheck(data);
                        });
                    } else if (size != '' && order != '' & key == '' && value == '') {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&size='+size+'&order='+order , function(data) {
                            statuscheck(data);
                        });
                    } else if (size != '' && key != '' && value == '') {
                        $('#response').show();
                        flash('error');
                        $('#response-field').text(JSON.stringify(JSON.parse('{"status_code":612,"message":"query parameters not set","payload":[]}'), undefined, 4));

                    } else if (size == '' && key != '' && value == '') {
                        $('#response').show();
                        flash('error');
                        $('#response-field').text(JSON.stringify(JSON.parse('{"status_code":612,"message":"query parameters not set","payload":[]}'), undefined, 4));

                    } else if(related != '' && key == '' && value == '' && order == '' && size == '') {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&related='+related, function(data){
                            statuscheck(data);
                        });
                    } else if(related != '' && key == '' && value == '' && order == '' && size != '') {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&related='+related+'&size='+size, function(data){
                            statuscheck(data);
                        });
                    } else if(related != '' && key == '' && value == '' && order != '' && size == '') {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&related='+related+'&order='+order, function(data){
                            statuscheck(data);
                        });
                    } else if (related != '' && key != '' && value != '' && size == '' && order == '') {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&related='+related+'&where='+key+','+value, function(data){
                            statuscheck(data);
                        });
                    } else if (related != '' && key != '' && value != '' && size != '' && order == '') {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&related='+related+'&where='+key+','+value+'&size='+size, function(data){
                            statuscheck(data);
                        });
                    } else if (related != '' && key != '' && value != '' && size != '' && order != '') {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&related='+related+'&where='+key+','+value+'&size='+size+'&order='+order, function(data){
                            statuscheck(data);
                        });
                    } else if (key == '' && order == '' && size == '' && value == '' && related == '') {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name, function(data) {
                            statuscheck(data);
                        });

                    } else {
                        $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&where='+key+','+value+'&size='+size, function(data) {
                            if (data.status_code == 700){
                                $('#response').show();
                                $('#response-field').text(JSON.stringify(data.payload.message));
                            } else {
                                $('#response').show();
                                $('#response-field').text(JSON.stringify(JSON.parse(data), undefined, 4));
                            }
                        });
                    }

                } else if (request_type === "create"){
                    payload = JSON.parse(editor.getValue());

                    var promises = [];
                    for (var i = 0; i < payload.length; i++) {
                        var info = {resource:[{name:table_name, field: [payload[i]]}]};

                        promises.push($.post("api/v1/service/"+service_name+"/db", info).success(function(data){
                          console.log(data);

                            $('#response-field').text(data);
                            statuscheck(data);
                        }));
                    }

                    $.when.apply(promises).done(function() {
                        $('#response').show();
                    });

                } else if (request_type === "update") {
                    payload = JSON.parse(editor.getValue());
                    var promises = [];

                    for (var i = 0; i < payload.length; i++) {
                        var info = {resource:[{name:table_name,params: [payload[i]]}]};
                        promises.push($.ajax({
                            url: "api/v1/service/"+service_name+"/db",
                            type: "PATCH",
                            data: info
                        })
                        .done(function(data) {
                            statuscheck(data);
                        }));
                    }

                    $.when.apply(promises);

                } else if (request_type === "delete") {
                    payload = JSON.parse(editor.getValue());
                    var promises = [];

                    for (var i = 0; i < payload.length; i++) {
                        var info = {resource:[{name:table_name,params: [payload[i]]}]};
                        promises.push($.ajax({
                            url: "api/v1/service/"+service_name+"/db",
                            type: "DELETE",
                            data: info
                        })
                        .done(function(data) {
                            statuscheck(data);
                        }));
                    }

                } else {

                    var method_type = $('#script_method').val();
                    var json = '{"resource":['+editor.getValue()+']}';

                    $.ajax({
                        url: "/api/v1/service/"+service_name+"/script",
                        type: method_type,
                        data: json,
                    })
                    .done(function(data) {
                        if(data.status_code == 700){
                            $('#response').show();
                            $('#response-field').text(data);
                            flash('error');
                        } else {
                            $('#response').show();
                            $('#response-field').text(data);
                            flash('success');
                        }
                    });
                }

            });

            function statuscheck(data) {
                if(data.status_code == 700){
                    $('#response').show();
                    $('#response-field').text(JSON.stringify(data, undefined, 4));
                    flash('error');
                } else {
                    $('#response').show();
                    // $('#response-field').text(JSON.stringify(JSON.parse(data), undefined, 4));
                    $('#response-field').text(JSON.stringify(data, undefined, 4));
                    flash('success');
                }
            }
            function flash(alert) {
                if (alert == 'success') {
                    $('.modal-body').html('Operation Successful');
                    $('.modal-body').css('background-color', '#7BE454');
                    $('#flash_msg').modal({
                        show: true
                    });
                    $('.modal-backdrop').removeClass("modal-backdrop");
                } else {
                    $('.modal-body').html('Operation Failed');
                    $('.modal-body').css('background-color', '#EA7878');
                    $('#flash_msg').modal({
                        show: true
                    });
                    $('.modal-backdrop').removeClass("modal-backdrop");
                }
                modalHide();
                $('html, body').animate({
                  scrollTop: $('#response-field').offset().top
                }, 1000, function(){
                  window.location = "#response";
                });
            }
            function modalHide() {
                setTimeout(function(){
                    $('#flash_msg').modal('hide');
                }, 3000);
            }


        }());

        </script>
    @endsection
