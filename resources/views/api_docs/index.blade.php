@extends('api_docs.apidoc')

@section('content')
  <section>

   <!--body wrapper start-->
   <div class="wrapper">
     <div class="row">
       <div class="col-lg-8 col-md-offset-2">
         <form action="{{ route('api.v1.console.store') }}" id="form" class="form-horizontal" method="POST">
         <section class="panel">
           <header class="panel-heading">
             API Console
           </header>
           <div class="panel-body">
               <div class="form-group" >
                 <label for="operation" class="col-lg-2 col-sm-2 control-label">Operation</label>
                 <div class="col-lg-10">
                   <select id="operation" name="operation" class="form-control m-b-10">
                        <option>Option 1</option>
                        <option>Option 2</option>
                        <option>Option 3</option>
                    </select>
                 </div>
               </div>

               <div class="form-group">
                 <label for="api_url" class="col-lg-2 col-sm-2 control-label">Api Url</label>
                 <div class="col-lg-10">
                   <input type="text" class="form-control" name="api_url" value="API URl">
                 </div>
               </div>

               <div class="form-group">
                 <div class="col-lg-offset-2 col-lg-10">
                   <button type="submit" class="btn btn-primary pull-right">Send</button>
                 </div>
               </div>
           </div>
         </section>

         <section class="panel">
           <header class="panel-heading">
             QUERY PARAMS
           </header>
           <div class="panel-body">
             <div id="query_params" class="form-horizontal">
               <div class="form-group" >
                 <label for="order" class="col-lg-2 col-sm-2 control-label">Order</label>
                 <div class="col-lg-10">
                   <input type="text" id="order-field" name="order" class="form-control" required="">
                 </div>
               </div>

               <div class="form-group">
                 <label for="where" class="col-lg-2 col-sm-2 control-label">Where</label>
                 <div class="col-lg-10">
                   <input type="text" class="form-control" name="where">
                 </div>
               </div>

               <div class="form-group">
                 <label for="size" class="col-lg-2 col-sm-2 control-label">Size</label>
                 <div class="col-lg-10">
                   <input type="text" class="form-control" name="size">
                 </div>
               </div>
             </div>
           </div>
         </section>

         <section class="panel">
           <header class="panel-heading">
             BODY PARAMS
           </header>
           <div class="panel-body">
             <div id="req_payload" class="form-horizontal">
               <div class="form-group" >
                 <label for="req_payload" class="col-lg-2 col-sm-2 control-label">Request Payload</label>
                 <div class="col-lg-10">
                   <div id="editor" style="height: 300px;"></div>
                   <input type="text" id="req_input" name="req_payload" class="form-control">
                 </div>
               </div>
             </div>
           </div>
         </section>

         <section class="panel">
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

         <section class="panel">
           <header class="panel-heading">
             RESPONSE
           </header>
           <div class="panel-body">
             <div id="response" class="form-horizontal">
               <div class="form-group" >
                 <div class="col-lg-12">
                   <textarea name="response" class="form-control" rows="20" cols="40" readonly=""></textarea>
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
       var editor = ace.edit("editor");
       editor.setTheme("ace/theme/xcode");
       editor.getSession().setMode("ace/mode/json");

       if (editor.length > 0) {
         $('#form').onSumbit(function() {
           var code = editor.getValue();
           $('#req_input').val(code);
         });
       }
   </script>
@endsection
