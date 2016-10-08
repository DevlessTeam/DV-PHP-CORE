@extends('layout')

@section('header')
   <!-- page head start-->
            <div class="page-head">
                <h3>Service</h3><span class="sub-title">{{ucwords($service->name)}}</span>            </div><!-- page head end-->
@endsection

@section('content')
    @include('error')
<div id="schema-table" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Tables</h4>
      </div>
      <div class="modal-body">
          <form id="form">
         <div  class="form-group">
            <label for="name-field">Table Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Table Name"/>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
                <textarea class="form-control" id="description" rows="3" name="description"></textarea>
        </div>
          <HR>
          <center> Add Fields </center>

          <div class="removeIndicator" id="fields" >
            <div  class="form-group">
            <label for="name-field">Field Name</label>
            <button type="button" class="btn btn-danger pull-right" id="delete-field" onclick="destroy_field('removeIndicator')">Remove</button>
            <input type="text" id="field-name"  name="field-name" class="form-control" placeholder="Field Name"/>
             </div>
           <div  class="form-group">
            <label for="field-type">Field Type</label>
            
            <?php /*'REFERENCE'*/$options = ['TEXT','TEXTAREA','INTEGER','DECIMALS','PASSWORD','URL','EMAIL', 'REFERENCE'] ?>
            <select class="form-control"  name="field-type" id="field-type">
                @foreach($options as  $option)
                <option value="{{$option}}">{{$option}}</option>
                @endforeach
            </select>
        </div>
        <div  class="form-group">
            <div style="display:block;" >
            <label for="field-reference">Reference Table</label>
            <select class="form-control"  name="field-reference" id="field-reference" >
                @foreach($table_meta as $table_data)
                <option value="{{$table_data['name']}}">{{$table_data['name']}}</option>
                @endforeach
            </select>
            </div> 
        </div>
          <div class="form-group">
           <label for="default-field">Default Value(optional)</label>
            <input type="text" id="default" name="default" class="form-control" />
           </div>

             <div class="form-group">
                <label for="option-field">Field Options</label>

                  <input type="checkbox" id="required"  name="required"/>REQUIRED?


                 <div style="display:none;">
                     <input type="checkbox" id="validate" name="validate"/>VALIDATE?
                 </div>


                    <input type="checkbox" id="unique" name="unique"/> UNIQUE FIELD?
            </div>
      </div>
              <div class="dynamic-space"></div>

      <div class="modal-footer">
          <button type="button" onclick="append_field()" class="btn btn-info pull-left" >Add a Field</button>
          <button type="button" id="crt-tbl" onclick="create_table('{{$service->name}}')" class="btn btn-info pull-right" >Create Table</button>
      </div>
      </div></form></div>
  </div>
</div>
            <!--body wrapper start-->
            <div class="wrapper no-pad">
                <div class="profile-desk">
                    <aside class="p-short-info">
                        <div class="widget">
                            <div class="title">
                                <h1>Service Details</h1>
                            </div>
                            <form action="{{ route('services.update', $service->id) }}" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div  class="form-group @if($errors->has('name')) has-error @endif">
                                    <label for="name-field">Name</label>
                                    <input type="text" readonly id="name-field" name="name" class="form-control" value="{{ $service->name }}"/>
                                     @if($errors->has("name"))
                                        <span class="help-block">{{ $errors->first("name") }}</span>
                                     @endif
                                </div>
                                 <div  class="form-group @if($errors->has('active')) has-error @endif">
                                    <label for="active-field">State</label>
                                    <?php $options = ['Inactive','Active'] ?>
                                    <select class="form-control"  name="active" id="active-field">
                                        @foreach($options as $option_index => $option)
                                        <option @if($service->active == $option_index )selected @endif value="{{$option_index}}">{{$option}}</option>
                                        @endforeach
                                    </select>
                                     @if($errors->has("active"))
                                        <span class="help-block">{{ $errors->first("active") }}</span>
                                     @endif
                                </div>


                                <div class="form-group">
                                    <label for="description-field">Description</label>
                                    <div class="">
                                        <textarea class="form-control" id="description-field" rows="3" name="description">{{ $service->description }}</textarea>
                                        @if($errors->has("description"))
                                        <span class="help-block">{{ $errors->first("description") }}</span>
                                        @endif
                                    </div>
                                </div>
                        </div>
                    </aside>
                    <aside class="p-aside">
                        <section class="isolate-tabs">
                            <header class="panel-heading tab-dark ">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active">
                                        <a data-toggle="tab" href="#jus">Database</a>
                                    </li>
                                    <li class="">
                                        <a data-toggle="tab" href="#mtab">Tables</a>
                                    </li>
                                    <li class="">
                                        <a data-toggle="tab" onclick="set_script()" href="#jtab">Script</a>
                                    </li>
                                </ul>
                            </header>

                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="jus">
                                        <form role="form">
                                            <div class="form-group">
                                                <label for="g-title">Name</label>
                                                <input class="form-control" name="database" id="database"
                                                placeholder="Database Name" value="{{$service->database}}" type="text">

                                        @if($errors->has("database"))
                                        <span class="help-block">{{ $errors->first("database") }}</span>
                                        @endif
                                         </div>

                                            <div class="form-group">
                                                <label for="g-txt" >Database Type</label>
                                                <select id="db-type" name="driver"  class="form-control m-b-10">
                                                    <?php $options = ['Default'=>'default','Sqlite'=>'sqlite',
                                                        'MySql'=>'mysql','Postgres'=>'Pgsql','SQL Server'=>'sqlsrv'];?>
                                                    @foreach($options as $option_index => $option_value )
                                                    <option value="{{$option_value}}" @if($option_value == $service->driver)selected @endif >{{$option_index}}</option>
                                                    @endforeach
                                                </select>
                                                 @if($errors->has("driver"))
                                                  <span class="help-block">{{ $errors->first("driver") }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Host Name</label>
                                                <input class="form-control" name="hostname" id="hostname"
                                                       type="text" placeholder="127.0.0.1" value="{{$service->hostname}}">
                                                 @if($errors->has("hostname"))
                                                  <span class="help-block">{{ $errors->first("hostname") }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Username</label>
                                                <input class="form-control" name="username" id="username"
                                                        type="text" placeholder="root" value="{{$service->username}}">
                                                @if($errors->has("username"))
                                                  <span class="help-block">{{ $errors->first("username") }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Password</label>
                                                <input class="form-control" id="password" name="password" placeholder="password" value="{{$service->password}}" type="password">
                                                @if($errors->has("password"))
                                                  <span class="help-block">{{ $errors->first("password") }}</span>
                                                @endif
                                            </div>
                                            <button class="btn btn-info" type=
                                            "submit">Update</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="mtab">
                                                <section class="panel">
                    <header class="panel-heading head-border">

                    </header>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                 @if(sizeOf($table_meta) > 0)
                                <tr>
                                    <th>#</th>
                                    <th>Table Name</th>
                                    <th>Description</th>
                                    <th>#N0 of Fields</th>

                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php $count = 0; ?>
                                    @foreach($table_meta as $table_data)
                                <tr id="{{$table_data['name']}}">
                                    <th scope="row">{{$count}}</th>
                                    <td>{{$table_data['name']}}</td>
                                    <td>{{$table_data['description']}}</td>
                                    <td>{{sizeOf($table_data['field'])}} field(s)</td>
                                    <td><a href="/datatable?service_name={{$service->name}}&table_name={{$table_data['name']}}" class="btn btn-default">View Data</a>
                                        <button onclick="destroy_table('{{$table_data['name']}}','{{$service->name}}')" class="btn btn-danger btn-sm">Delete Table</button></td>
                                </tr>
                                    <?php $count++; ?>
                                    @endforeach
                                 @else
                                <h3 class="text-center alert alert-info">Empty!</h3>
                                @endif
                            </tbody>
                        </table>
                        <br>
                        <button type="button" class="btn btn-info " data-toggle="modal" data-target="#schema-table">New Table</button>
                    </div>
                </section>
                                    </div>
                                                                   <div class="tab-pane" id="jtab">


<textarea class="code-box" name="script" rows="20" style="width: 100%">
{{$service->script}}
</textarea>
                                                                       <br>

                                                                       <button class="btn btn-info " onclick="run_script()" type="button"> Save </button><br><br>
<span id="code-console" class="code-console" style="background-color:black;margin-left:14%;width:400px;height:300px;color:greenyellow">
   </span>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>
            </div><!--body wrapper end-->
<!--body wrapper end-->
<script>
    //page init function
    function init(){
        window.count = 0;
        window.main_old_fields =  $('.removeIndicator').clone();
        window.schema_json = {"resource":[{"name":"","description":"","field":[]}]  }
    }
    //destroy table
    function destroy_table(table_name, service_name){
        if(confirm('Are you sure you want to delete '+table_name+' table?')){
            var settings = {
           "async": true,
           "crossDomain": true,
           "url": "/api/v1/service/"+service_name+"/db",
           "method": "DELETE",
           "headers": {
             "content-type": "application/json",
             "cache-control": "no-cache",
           },
           "processData": false,
            "data": "{\"resource\":[{\"name\":\""+table_name+"\",\"params\":[{\"drop\":\"true\"}]}]}"
           }
         $.ajax(settings).done(function (response) {
             console.log(response)
           //response_object = JSON.parse(response);
           status_code = response.status_code;
           if (status_code == 613) {
                $("#"+table_name).remove();
           }
           else
           {
               alert('could not delete table ');
           }
         });}
    }
  function append_field(){
    field_names = ['name', 'description', 'field-name', 'field-type', 'field-reference',
                'default','required', 'validate', 'unique'];
    old_fields = window.main_old_fields.clone();
          field_names.forEach(
    function(i){
              field_name = i+window.count;
              old_fields.find('#'+i).attr('name', field_name ).attr('id', field_name );
         }
    )
    new_fields = old_fields;
    $( ".dynamic-space").append(new_fields);
    old_fields.attr('class', 'fields'+window.count);
    old_fields.contents().each(function () {
        if (this.nodeType === 3) this.nodeValue = $.trim($(this).text()).replace(/removeIndicator/g, "fields"+window.count)
        if (this.nodeType === 1) $(this).html( $(this).html().replace(/removeIndicator/g, "fields"+window.count) )
        })
    window.count = window.count + 1 ;
    
    }
    
    function create_table(service_name){
         $('#crt-tbl').prop('disabled', true);
         $.fn.serializeObject = function()
        {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
        object = $('#form').serializeObject();
        var array = $.map(object, function(value, index) {
        return [value];
        });
        count = 0
        jQuery(
            function($)
            {
              count = 0 ;
              form_array = [];
              $.each($('#form')[0].elements,
                     function(i,o)
                     {
                      var _this=$(o);
                      field_id = _this.attr('id');
                       if(typeof field_id == "string" && field_id.indexOf("validate")>= 0){
                           form_array[count] = $('#'+_this.attr('id')).is(':checked');
                       }
                       else if(typeof field_id == "string" && field_id.indexOf("required")>= 0){
                           form_array[count] = $('#'+_this.attr('id')).is(':checked');
                       }
                       else if(typeof field_id == "string" && field_id.indexOf("unique")>= 0){
                           form_array[count] = $('#'+_this.attr('id')).is(':checked');
                       }else{
                           form_array[count] = $('#'+_this.attr('id')).val();
                       }
                    count++;
                     })
            }
          );
            if(form_array.length > 4){
                function trim(str){
                    console.log(str);
                    if(typeof str == "string"){
                        
                        return str.replace(/\s+/g, '').toLowerCase();
                    }else{
                        return str;
                    }
                }    
                window.schema_json.resource[0].name = trim(form_array[0]);
                window.schema_json.resource[0].description = form_array[1]  ;
                var len = ((form_array.length)-4)/8;
                
                for (var i = 1; i <= len; i++) {
                    position = ((len-i)*8)
                    if(form_array[6+position] == ""){ _default = null;}else{_default = form_array[6+position]; }
                    console.log("field tpe", trim(form_array[4+position]));
                    if(trim(form_array[4+position]) == "reference")
                    {
                        console.log('appended service')
                        referenced_table_name = service_name+'_'+trim(form_array[5+position])+'_id';
                        
                    }else{
                        referenced_table_name = trim(form_array[3+position]);
                        console.log('went for else instead')
                    }
                    console.log('ref after passing', referenced_table_name);
                    window.schema_json.resource[0].field[i-1] = {
                        "name":    referenced_table_name,
                        "field_type":trim(form_array[4+position]),
                        "ref_table":trim(form_array[5+position]),
                        "default":_default,
                        "required":trim(form_array[7+position]),
                        "validation":trim(form_array[8+position]),
                        "is_unique":trim(form_array[9+position]),
                     };
                }
                
                if (len => 1) {
                   table_schema =   JSON.stringify(window.schema_json);
                   var settings = {
                    "async": true,
                    "crossDomain": true,
                    "url": "/api/v1/service/"+service_name+"/schema",
                    "method": "POST",
                    "headers": {
                      "content-type": "application/json",
                      "cache-control": "no-cache",
                    },
                    "processData": false,
                    "data": table_schema
                  }
                $.ajax(settings).done(function (response) {
                  console.log(response);
                  if(typeof(response) == "string")
                  {
                      response = JSON.parse(response); 
                  }
                  status_code = response.status_code;
                  message = response.message;
                  payload = response.payload;
                  
                  if(status_code == 700){
                      alert( payload.message);
                  }
                  else if(status_code == 606){
                        window.location.href = "/services/"+{{$service->id}}+"/edit";
                        
                  }else{
                        alert(message);
                        
                  }
                });} else {
                     
                     alert('Please add at least a field');
                     $('#crt-tbl').prop('disabled', false);
                }
            }
            else{
                alert('Sorry seems like you have no fields set ');
                $('#crt-tbl').prop('disabled', false);
            }
            
            
    }
   function destroy_field(field_id){
       $('.'+field_id).remove();
   }
   function set_script(){
       setTimeout(function(){ $('.code-box').ace({ theme: 'github', lang: 'php'}); }, 1);
   }
   function run_script(){
       var form = new FormData();
form.append("call_type", "solo");
form.append("script", $('.code-box').val());
form.append("_method", "PUT");
var settings = {
  "async": true,
  "crossDomain": true,
  "url": "{{ route('services.update', $service->id) }}",
  "method": "POST",
  "headers": {
    "cache-control": "no-cache",
  },
  "processData": false,
  "contentType": false,
  "mimeType": "multipart/form-data",
  "data": form
}
$.ajax(settings).done(function (response) {
  result = JSON.parse(response);
  console.log(result);
   (result.status_code == 626)?$('.code-console').css('color','greenyellow')
 : $('.code-console').css('color','red');
  $('.code-console').html('<font size="3">'+result.message+'</font>');
});
   }
</script>
@endsection
