  @extends('api_docs.apidoc')

@section('content')
  <section>

   <!--body wrapper start-->
   <div class="wrapper">
     <div class="row">
       <div class="col-lg-8 col-md-offset-2">
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
                   <button type="submit" class="btn btn-info pull-right">Send</button>
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
         $('#service').change(function () {

           service_name = $('#service option:selected').text();
           var service_id = $('#service option:selected').val();

           $.get('console/'+service_id, function(data) {
             var table = data;
             for (var i = 0; i < table.length; i++) {
               $("#table").append("<option>"+$.parseJSON(table[i].schema).name+"</option>");
             }
           })
           $('#api_url').val('api/v1/service/'+service_name+'/db');
         });

         //texteditor for payload
         var editor = ace.edit("editor");
         editor.setTheme("ace/theme/xcode");
         editor.getSession().setMode("ace/mode/json");

         // Handles the form rendering
         var request_type;
         var table_name;
         $('#operation').change(function () {
          //  $('#response-field').val("");
           request_type = $(this).val();
           table_name = $('#table option:selected').text();
           if (request_type == "retrieve_all") {
             $('#query').show();
             $('#body_params').hide();
             $('#response').hide();

           } else {
             $.get('/console/'+table_name+'/schema', function(data){
               var schema = data;
               var values = {};
               for (var i = 0; i < schema.length; i++) {
                 values[schema[i].name] = "";
               };
               if (request_type === 'create'){
                 var json = JSON.stringify(JSON.parse('{"resource":[{"name":"'+table_name+'","field":['+JSON.stringify(values)+']}]}'), undefined, 4);
               } else if (request_type === 'update') {
                 var json = JSON.stringify(JSON.parse('{"resource":[{"name":"'+table_name+'","params":[{"where":"id, ","data":[{"key":"value"}]}]}]}'), undefined, 4);
               } else {
                 var json = JSON.stringify(JSON.parse('{"resource":[{"name":"'+table_name+'","params":[{"delete":"true","where":"id, "}]}]}'), undefined, 4);
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

               if (size == '' && order == '' && key != '' && value != '') {
                 $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&where='+key+','+value, function(data) {
                   $('#response').show();
                   $('#response-field').text(JSON.stringify(JSON.parse(data), undefined, 4));
                 });
               } else if (size == '' && order != '' && key != '' && value != '') {
                 $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&where='+key+','+value+'&order='+order, function(data) {
                   $('#response').show();
                   $('#response-field').text(JSON.stringify(JSON.parse(data), undefined, 4));
                 });
               } else if (size != '' && key == '' && value == '' && order == '') {
                 $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&size='+size, function(data) {
                   $('#response').show();
                   $('#response-field').text(JSON.stringify(JSON.parse(data), undefined, 4));
                 });
               } else if (size != '' && order != '' & key == '' && value == '') {
                 $.get('api/v1/service/'+service_name+'/db?table='+table_name+'&size='+size+'&order='+order , function(data) {
                   $('#response').show();
                   $('#response-field').text(JSON.stringify(JSON.parse(data), undefined, 4));
                 });
               } else if (size != '' && key != '' && value == '') {
                   $('#response').show();
                   $('#response-field').text(JSON.stringify(JSON.parse('{"status_code":612,"message":"query parameters not set","payload":[]}'), undefined, 4));

               } else if (key == '' && order == '' && size == '' && value == '') {
                 $.get('api/v1/service/'+service_name+'/db?table='+table_name, function(data) {
                   $('#response').show();
                   $('#response-field').text(JSON.stringify(JSON.parse(data), undefined, 4));
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
             $.post('api/v1/service/'+service_name+'/db', JSON.parse(editor.getValue()))
                .done(function(data){
                  console.log(data);
                  if (JSON.parse(data).status_code == 609) {
                    $('#response').show();
                    $('#response-field').text(JSON.stringify(JSON.parse(data), undefined, 4));
                  } else {
                    $('#response').show();
                    $('#response-field').text(JSON.stringify(data.payload.message, undefined, 4));
                  }
                });
           } else if (request_type === "update") {
             $.ajax({
               url: "api/v1/service/"+service_name+"/db",
               type: "PATCH",
               data: JSON.parse(editor.getValue())
             })
              .done(function(data) {
                $('#response').show();
                $('#response-field').text(JSON.stringify(JSON.parse(data), undefined, 4));
             })

           } else {
             $.ajax({
               url: "api/v1/service/"+service_name+"/db",
               type: "DELETE",
               data: JSON.parse(editor.getValue())
             })
             .done(function(data) {
               $('#response').show();
               $('#response-field').text(JSON.stringify(JSON.parse(data), undefined, 4));
             })
           }

         });

       }());

   </script>
@endsection
