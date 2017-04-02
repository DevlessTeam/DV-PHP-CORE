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
        <script>
        window.onload(function () {
            document.getElementById('query').style.display = 'none';
            document.getElementById('body_params').style.display = 'none';
            document.getElementById('request').style.display = 'none';
            document.getElementById('response').style.display = 'none';
            document.getElementById('scroll').style.display = 'none';

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

            // Collect form data into object
            function jQExtn() {
              $.fn.serializeObject = function()
              {
                var obj = {};
                var arr = this.serializeArray();
                $.each(arr, function() {
                  if (obj[this.name] !== undefined) {
                    if (!obj[this.name].push) {
                      obj[this.name] = [obj[this.name]];
                    }
                    obj[this.name].push(this.value || '');
                  } else {
                    obj[this.name] = this.value || '';
                  }
                });
                return obj;
              };
            }


            // Handling requests and response
            $('#form_data').submit(function(e){
                e.preventDefault();
                jQExtn();

                $('#response-field').text('');
                
                switch(request_type) {
                    case 'retrieve_all':

                        key_field = $('#key-field').val();
                        value_field = $('#value-field').val();
                        search_key = $('#search-key').val();
                        search_value = $('#search-value').val();

                        query_url = 'api/v1/service/'+service_name+'/db?table='+table_name;
                        
                        if (search_key !== '' && search_value !== '' && key_field !== '' && value_field !== ''){
                            query_url = 'api/v1/service/'+service_name+'/db?table='+table_name+'&where='
                                +key_field+','+value_field+'&search='+search_key+','+search_value;
                        } else if (key_field !== '' && value_field !== ''){
                            query_url = 'api/v1/service/'+service_name+'/db?table='+table_name+'&where='+key_field+','+value_field;
                        } else if ( search_key !== '' && search_value !== ''){
                            query_url = 'api/v1/service/'+service_name+'/db?table='+table_name+'&search='+search_key+','+search_value;  
                        }
                        
                        payload = $(this).serializeObject();
                        
                        for(var key in payload) {
                            if(payload.hasOwnProperty(key)) {
                                if (payload[key] !== ''){
                                    query_url += '&'+key+'='+payload[key];
                                }
                            }
                        }

                        $.get(query_url, function(data) {
                            statuscheck(data);
                        });

                        break;

                    case 'create':
                        create_update_delete ("POST");
                        
                        break;

                    case 'update':
                        create_update_delete ("PATCH");
                        
                        break;

                    case 'delete':
                        create_update_delete ("DELETE");
                        
                        break;
                }

            });


            // Handles update or destroy
            function create_update_delete (method){
                
                var payload = JSON.parse(editor.getValue());
                var info = {resource:[{name:table_name,params: []}]};

                for (var i = 0; i < payload.length; i++) {
                    promises = [];
                    if (method !== "POST"){
                        info = {resource:[{name:table_name, params: [payload[i]]}]};
                    } else {
                        info = {resource:[{name:table_name, field: [payload[i]]}]};
                    }
                    promises.push($.ajax({
                        url: "api/v1/service/"+service_name+"/db",
                        type: method,
                        data: info
                    })
                    .done(function(data) {
                        statuscheck(data);
                    }));
                }
            }

            function statuscheck(data) {
                if(data.status_code == 700){
                    $('#response').show();
                    $('#response-field').text(JSON.stringify(data, undefined, 4));
                    flash('error');
                } else {
                    $('#response').show();
                    $('#response-field').text(JSON.stringify(data, undefined, 4));
                    flash('success');
                }
            }

            function flash(alert) {
                if (alert == 'success') {
                    backdrop('Operation Successful', '#7BE454');
                } else {
                    backdrop('Operation Failed', '#EA7878');
                }

                modalHide();
                
                $('html, body').animate({
                      scrollTop: $('#response-field').offset().top
                    }, 1000, function(){
                      window.location = "#response";
                });
                $('#scroll').show();
            }

            function backdrop(message, color){
                $('.modal-body').html(message);
                $('.modal-body').css('background-color', color);
                $('#flash_msg').modal({
                    show: true
                });
                $('.modal-backdrop').removeClass("modal-backdrop");
            }

            function modalHide() {
                setTimeout(function(){
                    $('#flash_msg').modal('hide');
                }, 3000);
            }

            $('#scroll').click(function(){
                $('html, body').animate({
                  scrollTop: $('html, body').offset().top
                }, 1000, function(){
                  window.location = "#";
                })
            })

        }());
        </script>
    @endsection
