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
                        <option value="retrieve_all">GET</option>
                        <option value="create">POST </option>
                        <option value="update">UPDATE </option>
                        <option vallue="delete">DELETE </option>
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
                   <button type="submit" class="btn btn-primary pull-right">Send</button>
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
                 <div class="col-lg-10" >
                   <input type="text" id="key-field" class="form-control" name="key" placeholder="key">
                   <input type="text" id="value-field" class="form-control" name="value" placeholder="value">
                 </div>
               </div>

               <div class="form-group">
                 <label for="size" class="col-lg-2 col-sm-2 control-label">Size</label>
                 <div class="col-lg-10">
                   <input type="text" id="size-field" class="form-control" >
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

         //JSON schema
         var json = {"resource":[{"name":"","field":[]}]  }
         console.log(json);

         //Handles URL generation
         var service_name;
         $('#service').change(function () {

           service_name = $('#service option:selected').text();
           var service_id = $('#service option:selected').val();

           $.get('console/'+service_id, function(data) {
             var table = data;
             var table_name = [];
             for (var i = 0; i < table.length; i++) {
               $("#table").append("<option>"+$.parseJSON(table[i].schema).name+"</option>");
             }
           })
           $('#api_url').val('api/v1/service/'+service_name+'/db');
         });

         //text for payload
         var editor = ace.edit("editor");
         editor.setTheme("ace/theme/xcode");
         editor.getSession().setMode("ace/mode/json");

         // Handles the form rendering
         var request_type;
         $('#operation').change(function () {
           request_type = $(this).val();

           if (request_type == "retrieve_all") {
             $('#query').show();
             $('#body_params').hide();
             $('#response').hide();

           } else {
            //  $.get('/api/v1/schema/'+service_name, function(data){
            //    editor.setValue(
            //      '{"resource":[{"name":"authentication","params":[{"where":"id,1","data":[{"username": "james"}]}]}]}'
            //    );
            //  });

             $('#body_params').show();
             $('#query').hide();
             $('#response').hide();
           }

         });


        // Handling requests and response
         $('#form_data').submit(function(e){
           e.preventDefault();

           // Handles GET requests
           if (request_type == "retrieve_all") {

             var name = $('#table option:selected').text();
             var order = $('#order-field').val();
             var key = $('#key-field').val();
             var value = $('#value-field').val();
             var size = $('#size-field').val();

             $.get('api/v1/service/'+service_name+'/db?table='+name+'&where='+key+','+value+'&size='+size, function(data) {
               $('#response').show();
               $('#response-field').html(JSON.stringify(JSON.parse(data), undefined, 4));
             });
           }

         });

       }());

   </script>
@endsection
