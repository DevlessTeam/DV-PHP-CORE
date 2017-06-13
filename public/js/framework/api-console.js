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

    var request_type;
    
    //Handles table change
    $('#table').change(function() {
        $('#operation').prop('selectedIndex',0);
        $('#body_params').hide();
        $('#query').hide();
        $('#response').hide();
        request_type = null;
    });
    
    //texteditor for payload
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/xcode");
    editor.getSession().setMode("ace/mode/json");
    
    // Handles the form rendering
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
                    var json = JSON.stringify(JSON.parse('['+JSON.stringify(values)+']'), null, 4);
                } else if (request_type === 'update') {
                    var json = JSON.stringify(JSON.parse('[{"where":"id, ","data":[{"key":"value"}]}]'), null, 4);
                } else if (request_type === 'delete') {
                    var json = JSON.stringify(JSON.parse('[{"delete":"true","where":"id, "}]'), null, 4);
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